<?php
// Include the database connection
$conn = require "database.php"; // Assuming this file returns the $conn object

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to handle check-in
function checkIn($guestData) {
    global $conn;

    // Insert reservation into the p2_guest_reservation table
    $stmt = $conn->prepare("INSERT INTO p2_guest_reservation (check_in, check_out, adults, children, first_name, last_name, email, contact_number, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("ssiiisss", $guestData['check_in'], $guestData['check_out'], $guestData['adults'], $guestData['children'], $guestData['first_name'], $guestData['last_name'], $guestData['email'], $guestData['contact_number']);

    if ($stmt->execute()) {
        $reservation_id = $conn->insert_id; // Get the last inserted ID

        // Insert into p2_guest_reservation_room
        $stmt_room = $conn->prepare("INSERT INTO p2_guest_reservation_room (reservation_id, room_id, quantity, price_per_night) VALUES (?, ?, ?, ?)");
        $quantity = 1; // Assuming 1 room per reservation
        $price_per_night = 100; // Example price, you can fetch this from the rooms table
        $stmt_room->bind_param("iiid", $reservation_id, $guestData['room_id'], $quantity, $price_per_night);
        $stmt_room->execute();

        // Update room status to 'Occupied'
        $stmt_update_room = $conn->prepare("UPDATE rooms SET status = 'Occupied' WHERE id = ?");
        $stmt_update_room->bind_param("i", $guestData['room_id']);
        $stmt_update_room->execute();

        echo "Check-In successful! Reservation ID: " . $reservation_id;
    } else {
        echo "Error during check-in: " . $stmt->error;
    }

    // Close statements
    $stmt->close();
}

// Function to handle check-out
function checkOut($reservationId) {
    global $conn;

    // Update reservation status to 'CheckedOut'
    $stmt_checkout = $conn->prepare("UPDATE p2_guest_reservation SET status = 'CheckedOut' WHERE id = ?");
    $stmt_checkout->bind_param("i", $reservationId);
    if ($stmt_checkout->execute()) {
        // Restore room availability
        $stmt_room_update = $conn->prepare("UPDATE rooms SET status = 'Available' WHERE id IN (SELECT room_id FROM p2_guest_reservation_room WHERE reservation_id = ?)");
        $stmt_room_update->bind_param("i", $reservationId);
        $stmt_room_update->execute();

        echo "Check-Out successful! Reservation ID: " . $reservationId;
    } else {
        echo "Error during check-out: " . $stmt_checkout->error;
    }

    // Close statements
    $stmt_checkout->close();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] === 'checkin') {
        // Gather check-in data
        $guestData = [
            'first_name' => htmlspecialchars($_POST['first_name']),
            'last_name' => htmlspecialchars($_POST['last_name']),
            'email' => htmlspecialchars($_POST['email']),
            'contact_number' => htmlspecialchars($_POST['contact_number']),
            'adults' => (int)$_POST['adults'],
            'children' => (int)$_POST['children'],
            'room_id' => (int)$_POST['room_id'],
            'check_in' => $_POST['check_in'],
            'check_out' => $_POST['check_out']
        ];
        checkIn($guestData);
    } elseif ($_POST['action'] === 'checkout') {
        // Gather check-out data
        $reservationId = (int)$_POST['reservation_id'];
        checkOut($reservationId);
    }
}

// Close the database