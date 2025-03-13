<?php
session_start();
include 'database.php';
include 'email_config.php';

// Debug session data (remove this in production)
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page instead of just showing an error
    header("Location: index.php");
    exit;
}

// No need to check for email in session if you're already checking user_id
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $email = $_POST['email']; // This is the guest's email from the form
    $contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : $_POST['contact_number']; // Handle both possible field names
    $special_requests = isset($_POST['special_requests']) ? $_POST['special_requests'] : NULL;
    $status = 'Pending';
    $payment_status = 'No Payment'; // Default payment status

    // Validate dates
    if (strtotime($check_out) <= strtotime($check_in)) {
        die("Error: Check-out date must be after check-in date.");
    }

    // Calculate total price (Example: $100 per night)
    $nightly_rate = 100;
    $days = (strtotime($check_out) - strtotime($check_in)) / 86400;
    $total_amount = $days * $nightly_rate;

    // Start transaction to ensure both reservation & bill are inserted
    $mysqli->begin_transaction();

    try {
        // Insert reservation with guest details
        $sql = "INSERT INTO reservations 
                (user_id, check_in, check_out, last_name, first_name, email, contact_number, status, payment_status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("issssssss", $user_id, $check_in, $check_out, $last_name, $first_name, $email, $contact_number, $status, $payment_status);

        $stmt->execute();
        $reservation_id = $stmt->insert_id;
        $stmt->close();

        // Insert bill for this reservation
        $sql = "INSERT INTO bills (reservation_id, user_id, total_amount) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iid", $reservation_id, $user_id, $total_amount);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $mysqli->commit();

        // Send confirmation email - use the email from the form
        $subject = "Reservation Confirmation";
        $body = "<p>Dear $first_name,</p>
                 <p>Your reservation from <strong>$check_in</strong> to <strong>$check_out</strong> has been successfully booked.</p>
                 <p>Total Amount: <strong>$" . number_format($total_amount, 2) . "</strong></p>
                 <p>Please complete your payment to confirm your booking.</p>
                 <p>Thank you for choosing Rainbow Resort!</p>";

        send_email($email, $subject, $body); // Use the email from the form

        // Redirect to the bill view page
        header("Location: view_bill.php?reservation_id=$reservation_id");
        exit();

    } catch (Exception $e) {
        $mysqli->rollback(); // Rollback transaction on error
        echo "Error: Could not complete reservation. " . $e->getMessage();
    }
}

$mysqli->close();
?>