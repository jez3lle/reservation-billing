<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

ob_start(); // Start output buffering
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_error.log');
error_log("Form submission received: " . json_encode($_POST));

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
$conn = require "database.php";

// Check if the database connection was successful
if (!$conn) {
    error_log("Database connection failed.");
    die("Database connection failed.");
}

// Initialize response variables
$success = false;
$message = '';
$booking_reference = '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("POST data: " . print_r($_POST, true));
    $conn->begin_transaction();

    try {
        // Generate unique booking reference
        $booking_reference = 'P2' . time() . rand(1000, 9999);

        // Get form data
        $check_in_date = isset($_POST['check_in']) ? htmlspecialchars($_POST['check_in']) : '';
        $check_out_date = isset($_POST['check_out']) ? htmlspecialchars($_POST['check_out']) : '';
        $adults = isset($_POST['adults']) ? (int)$_POST['adults'] : 0;
        $children = isset($_POST['children']) ? (int)$_POST['children'] : 0;
        $first_name = isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '';
        $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
        $contact_number = isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number']) : '';
        $special_requests = isset($_POST['special_requests']) ? htmlspecialchars($_POST['special_requests']) : '';
        $total_amount = isset($_POST['total_amount']) ? (float)$_POST['total_amount'] : 0;
        $payment_method = isset($_POST['payment_method']) ? htmlspecialchars($_POST['payment_method']) : '';

        // Validate required fields
        if (empty($check_in_date) || empty($check_out_date) || empty($first_name) || empty($last_name) || empty($email)) {
            throw new Exception("All required fields must be filled out.");
        }

        // Process payment details based on method
        $payment_details = '';
        if ($payment_method == 'gcash') {
            $gcash_number = isset($_POST['gcash_number']) ? htmlspecialchars($_POST['gcash_number']) : '';
            $gcash_name = isset($_POST['gcash_name']) ? htmlspecialchars($_POST['gcash_name']) : '';
            $payment_details = "GCash Number: $gcash_number, Name: $gcash_name";
        } else if ($payment_method == 'bank-transfer') {
            $payment_details = "Bank transfer selected";
        }

        // Calculate stay duration
        $check_in = new DateTime($check_in_date);
        $check_out = new DateTime($check_out_date);

        if ($check_out <= $check_in) {
            throw new Exception("Check-out date must be after check-in date.");
        }

        $nights = $check_in->diff($check_out)->days;
        if ($nights <= 0) {
            throw new Exception("Invalid date range selected.");
        }

        error_log("Check-in: " . $check_in->format('Y-m-d') . ", Check-out: " . $check_out->format('Y-m-d') . ", Nights: " . $nights);

        // Convert DateTime objects to strings for SQL
        $check_in_str = $check_in->format('Y-m-d');
        $check_out_str = $check_out->format('Y-m-d');

        // Insert main reservation record
        $stmt = $conn->prepare("INSERT INTO p2_guest_reservation (booking_reference, check_in, check_out, 
            adults, children, total_amount, first_name, last_name, email, 
            contact_number, special_requests, payment_method, payment_details, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $status = 'Pending'; // Default status
        $stmt->bind_param("sssiidsssissss",
            $booking_reference, $check_in_str, $check_out_str, // Use the string variables here
            $adults, $children, $total_amount, $first_name, $last_name, $email,
            $contact_number, $special_requests, $payment_method, $payment_details, $status);

        if (!$stmt->execute()) {
            error_log("Database error: " . $stmt->error);
            throw new Exception("Error executing reservation query: " . $stmt->error);
        }

        $reservation_id = $conn->insert_id;
        if (!$reservation_id) {
            throw new Exception("Failed to create reservation record.");
        }

        error_log("Reservation created with ID: " . $reservation_id);
        
        // Process selected rooms
        if (isset($_POST['room_id']) && is_array($_POST['room_id'])) {
            $room_ids = $_POST['room_id'];
            $room_quantities = $_POST['room_quantity'];
            $room_prices = $_POST['room_price'];

            if (count($room_ids) !== count($room_quantities) || count($room_ids) !== count($room_prices)) {
                throw new Exception("Room selection data is inconsistent.");
            }

            $rooms_added = false;

            for ($i = 0; $i < count($room_ids); $i++) {
                $room_id = (int)$room_ids[$i];
                $quantity = (int)$room_quantities[$i];
                $price = (float)$room_prices[$i];

                if ($quantity > 0) {
                    error_log("Inserting room: reservation_id=$reservation_id, room_id=$room_id, quantity=$quantity, price=$price");

                    // Insert into p2_guest_reservation_room
                    $stmt = $conn->prepare("INSERT INTO p2_guest_reservation_room 
                        (reservation_id, room_id, quantity, price_per_night) 
                        VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiid", $reservation_id, $room_id, $quantity, $price);

                    if (!$stmt->execute()) {
                        error_log("Database error: " . $stmt->error);
                        throw new Exception("Error executing room query: " . $stmt->error);
                    }

                    // Update the room quantity in the rooms table
                    $stmt = $conn->prepare("UPDATE rooms SET quantity = quantity - ? WHERE id = ?");
                    $stmt->bind_param("ii", $quantity, $room_id);

                    if (!$stmt->execute()) {
                        error_log("Database error while updating room quantity: " . $stmt->error);
                        throw new Exception("Error updating room quantity: " . $stmt->error);
                    }

                    $rooms_added = true;
                }
            }

            if (!$rooms_added) {
                throw new Exception("No rooms were added to the reservation.");
            }
        } else {
            throw new Exception("No rooms selected.");
        }

        // Link reservation to user if logged in
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            $stmt = $conn->prepare("INSERT INTO p2_user_reservation (user_id, reservation_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $reservation_id);

            if (!$stmt->execute()) {
                error_log("Database error: " . $stmt->error);
                throw new Exception("Error linking reservation to user: " . $stmt->error);
            }
        }

        $conn->commit();
        $success = true;
        $message = 'Booking successfully created!';
        error_log("Transaction committed successfully. Booking reference: " . $booking_reference);

    } catch (Exception $e) {
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
        error_log("Booking error: " . $e->getMessage());
    }

    // Handle response based on request type
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // Return JSON response for AJAX request
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'booking_reference' => $booking_reference,
            'message' => $message
        ]);
        exit;
    } else {
        // Regular form submission - redirect based on result
        if ($success) {
            header("Location: p2_booking_success.php?ref=" . urlencode($booking_reference));
            exit;
        } else {
            header("Location: booking_error.php?msg=" . urlencode($message));
            exit;
        }
    }
}
?>