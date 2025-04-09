<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$mysqli = require 'database.php';

// Check if the necessary request parameters are set
if (!isset($_SESSION['reservation_details']) || !isset($_POST['referenceNumber']) || !isset($_FILES['paymentProof'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required information'
    ]);
    exit;
}

$reservation = $_SESSION['reservation_details'];
$reference_number = $_POST['referenceNumber'];
$reservation_code = $reservation['reservation_code'];
$reservation_code_column = $_POST['reservation_code_column'] ?? 'guest_reservation_code';

// Check if the session contains valid reservation details
if (empty($reservation_code)) {
    echo json_encode([
        'success' => false,
        'message' => 'Reservation details are invalid or missing.'
    ]);
    exit;
}

// Determine which table to use based on user login status
$is_user_logged_in = isset($_SESSION['user_id']);
$reservation_table = $is_user_logged_in ? 'user_reservation' : 'guest_reservation';

// Check if reservation is expired
$current_time = time();
if (isset($reservation['expires_at']) && $current_time > $reservation['expires_at']) {
    echo json_encode([
        'success' => false,
        'message' => 'Your reservation has expired. Please create a new reservation.'
    ]);
    exit;
}

// Recalculate and update expiration time - set to 48 hours from now
$new_expires_at = time() + (48 * 60 * 60); // 48 hours in seconds

// Update the expiration timestamp in the database
$stmt = $mysqli->prepare("
    UPDATE $reservation_table 
    SET expires_at = ? 
    WHERE reservation_code = ?
");
if ($stmt) {
    $stmt->bind_param("is", $new_expires_at, $reservation_code);
    $stmt->execute();
    $stmt->close();
    
    // Also update the session value
    $_SESSION['reservation_details']['expires_at'] = $new_expires_at;
} else {
    echo json_encode([
        'success' => false,
        'message' => "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error
    ]);
    exit;
}

// Handle file upload
$upload_dir = 'uploads/payment_proofs/';

// Create the upload directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Validate the uploaded file
if ($_FILES['paymentProof']['error'] != UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'message' => 'Error uploading file. Please try again.'
    ]);
    exit;
}

$file_name = $reservation_code . '_' . time() . '_' . basename($_FILES['paymentProof']['name']);
$target_file = $upload_dir . $file_name;

// Check allowed file types
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
if (!in_array($_FILES['paymentProof']['type'], $allowed_types)) {
    echo json_encode([
        'success' => false,
        'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.'
    ]);
    exit;
}

// Check the file size (max 5MB)
if ($_FILES['paymentProof']['size'] > 5000000) {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, your file is too large. Maximum size is 5MB.'
    ]);
    exit;
}

// Check if the upload directory is writable
if (!is_writable($upload_dir)) {
    echo json_encode([
        'success' => false,
        'message' => 'Upload directory is not writable. Please contact support.'
    ]);
    exit;
}

// Move the uploaded file to the target directory
if (move_uploaded_file($_FILES['paymentProof']['tmp_name'], $target_file)) {
    // Prepare and execute the database query to update the payment record
    if ($is_user_logged_in) {
        $stmt = $mysqli->prepare("
            UPDATE payments 
            SET status = 'Approved',
                payment_receipt = ?,
                file_path = ?,
                uploaded_at = CURRENT_TIMESTAMP
            WHERE user_reservation_code = ?
        ");
    } else {
        $stmt = $mysqli->prepare("
            UPDATE payments 
            SET status = 'Approved',
                payment_receipt = ?,
                file_path = ?,
                uploaded_at = CURRENT_TIMESTAMP
            WHERE guest_reservation_code = ?
        ");
    }

    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error
        ]);
        exit;
    }

    // Bind parameters based on user login status
    if ($is_user_logged_in) {
        $stmt->bind_param("sss", $reference_number, $target_file, $reservation_code);
    } else {
        $stmt->bind_param("sss", $reference_number, $target_file, $reservation_code);
    }

    // Execute the query and handle success or failure
    if ($stmt->execute()) {
        // Successful database update
        $_SESSION['reservation_details']['payment_status'] = 'Approved';
        $_SESSION['reservation_details']['reference_number'] = $reference_number;

        echo json_encode([
            'success' => true,
            'message' => 'Payment proof uploaded successfully. Redirecting to confirmation page...'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $stmt->error . '. Please try again or contact support.'
        ]);
    }
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to upload file. Please try again.'
    ]);
}
?>