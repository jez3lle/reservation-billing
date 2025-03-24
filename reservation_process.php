<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form data
    $first_name = filter_input(INPUT_POST, "first_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $last_name = filter_input(INPUT_POST, "last_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $contact_number = filter_input(INPUT_POST, "contact_number", FILTER_SANITIZE_SPECIAL_CHARS);
    $check_in = filter_input(INPUT_POST, "check_in", FILTER_SANITIZE_SPECIAL_CHARS);
    $check_out = filter_input(INPUT_POST, "check_out", FILTER_SANITIZE_SPECIAL_CHARS);
    $adult_count = filter_input(INPUT_POST, "adult_count", FILTER_SANITIZE_NUMBER_INT);
    $kid_count = filter_input(INPUT_POST, "kid_count", FILTER_SANITIZE_NUMBER_INT);
    $special_requests = filter_input(INPUT_POST, "special_requests", FILTER_SANITIZE_SPECIAL_CHARS);
    $add_ons = filter_input(INPUT_POST, "add_ons", FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Basic validation
    $errors = [];
    
    if (empty($first_name)) {
        $errors[] = "First name is required";
    }
    
    if (empty($last_name)) {
        $errors[] = "Last name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($contact_number)) {
        $errors[] = "Contact number is required";
    }
    
    if (empty($check_in)) {
        $errors[] = "Check-in date is required";
    }
    
    if (empty($check_out)) {
        $errors[] = "Check-out date is required";
    }
    
    if ($check_in >= $check_out) {
        $errors[] = "Check-out date must be after check-in date";
    }
    
    if ($adult_count < 1) {
        $errors[] = "At least one adult guest is required";
    }
    
    if ($kid_count < 0) {
        $errors[] = "Number of kids cannot be negative";
    }
    
    // If there are validation errors, redirect back to the form
    if (!empty($errors)) {
        $_SESSION['reservation_errors'] = $errors;
        header("Location: guest_reservation.php");
        exit;
    }
    
    // Generate a temporary reservation code
    $reservation_code = 'TEMP-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
    
    // Store reservation data in session instead of database
    $_SESSION['temp_reservation'] = [
        'reservation_code' => $reservation_code,
        'user_id' => $_SESSION["user_id"],
        'first_name' => $first_name,
        'last_name' => $last_name,
        'name' => $first_name . ' ' . $last_name, // For compatibility with existing code
        'email' => $email,
        'contact_number' => $contact_number,
        'check_in_date' => $check_in,
        'check_out_date' => $check_out,
        'guest_adult' => $adult_count,
        'guest_kid' => $kid_count,
        'special_requests' => $special_requests,
        'add_ons' => $add_ons,
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // For compatibility with the billing page
    $_SESSION['guest_reservation'] = $_SESSION['temp_reservation'];
    
    // Redirect to billing page
    header("Location: guest_billing.php");
    exit;
} else {
    // If accessed directly without POST data, redirect to reservation form
    header("Location: guest_reservation.php");
    exit;
}
?>