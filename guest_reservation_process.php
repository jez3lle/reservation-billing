<?php
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the database connection
    $mysqli = require 'database.php';
    
    // Sanitize and validate the input data
    $check_in = filter_input(INPUT_POST, "check_in", FILTER_SANITIZE_SPECIAL_CHARS);
    $check_out = filter_input(INPUT_POST, "check_out", FILTER_SANITIZE_SPECIAL_CHARS);
    $first_name = filter_input(INPUT_POST, "first_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $last_name = filter_input(INPUT_POST, "last_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $contact_number = filter_input(INPUT_POST, "contact_number", FILTER_SANITIZE_SPECIAL_CHARS);
    $guest_adult = filter_input(INPUT_POST, "guest_adult", FILTER_SANITIZE_NUMBER_INT);
    $guest_kid = filter_input(INPUT_POST, "guest_kid", FILTER_SANITIZE_NUMBER_INT);
    $special_requests = filter_input(INPUT_POST, "special_requests", FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Generate a unique reservation ID for guests to reference
    $reservation_code = 'G-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    
    // Get current date for the reservation timestamp
    $created_at = date('Y-m-d H:i:s');
    
    // Store the guest reservation in your database
    // This assumes you have a table called 'guest_reservations' - adjust as needed
    $stmt = $mysqli->prepare("INSERT INTO guest_reservations (
        reservation_code, check_in_date, check_out_date, first_name, last_name, 
        email, contact_number, guest_adult, guest_kid, special_requests, created_at, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    
    if ($stmt === false) {
        die("Error preparing statement: " . $mysqli->error);
    }

    if ($guest_adult === null || $guest_adult === '') {
        $guest_adult = 0; // Default to 0 if empty
    }
    
    // Do the same for guest_kid
    if ($guest_kid === null || $guest_kid === '') {
        $guest_kid = 0; // Default to 0 if empty
    }
    
    $stmt->bind_param("sssssssiiss", 
        $reservation_code, 
        $check_in, 
        $check_out, 
        $first_name, 
        $last_name, 
        $email, 
        $contact_number, 
        $guest_adult, 
        $guest_kid, 
        $special_requests,
        $created_at
    );
    
    if ($stmt->execute()) {
        // Store reservation details in session for confirmation page
        $_SESSION['guest_reservation'] = [
            'reservation_code' => $reservation_code,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'name' => $first_name . ' ' . $last_name,
            'email' => $email
        ];
        
        // Send confirmation email (you would implement this)
        // sendConfirmationEmail($email, $first_name, $reservation_code, $check_in, $check_out);
        
        // Redirect to a confirmation page
        header("Location: billing.php");
        exit;
    } else {
        // If there was an error, redirect back with error message
        $_SESSION['reservation_error'] = "Failed to process your reservation. Please try again.";
        header("Location: guest_reservation.php");
        exit;
    }
    
    $stmt->close();
    $mysqli->close();
} else {
    // If someone tries to access this file directly without submitting the form
    header("Location: home_p1.php");
    exit;
}
?>