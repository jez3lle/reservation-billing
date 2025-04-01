<?php
// process_payment.php with enhanced error handling
// Add error reporting at the top
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to browser, but log them
ini_set('log_errors', 1);
ini_set('error_log', 'php_error.log'); // Log errors to a file

// Log the start of the script (for debugging)
error_log("Processing payment submission");

session_start();

// Make sure there's no output before intended JSON response
// by buffering everything
ob_start();

// Response array
$response = array('success' => false, 'message' => '');

// Check if reservation details are in the session
if (!isset($_SESSION['reservation_details'])) {
    $response['message'] = 'No reservation details found in session.';
    sendJsonResponse($response);
    exit;
}

// Log received data (without sensitive info)
error_log("Payment submission for reservation code: " . $_SESSION['reservation_details']['reservation_code']);

// Check if required data is present
if (!isset($_FILES['paymentProof'])) {
    error_log("Missing payment proof file");
    $response['message'] = 'Missing payment proof.';
    sendJsonResponse($response);
    exit;
}

if (!isset($_POST['referenceNumber'])) {
    error_log("Missing reference number");
    $response['message'] = 'Missing reference number.';
    sendJsonResponse($response);
    exit;
}

$reservation = $_SESSION['reservation_details'];
$referenceNumber = $_POST['referenceNumber'];
$file = $_FILES['paymentProof'];

// Log file details
error_log("File details - Name: {$file['name']}, Type: {$file['type']}, Size: {$file['size']}, Error: {$file['error']}");

// Validate file
if ($file['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload'
    ];
    
    $errorMessage = isset($errorMessages[$file['error']]) 
        ? $errorMessages[$file['error']] 
        : 'Unknown upload error';
    
    error_log("File upload error: " . $errorMessage);
    $response['message'] = 'File upload error: ' . $errorMessage;
    sendJsonResponse($response);
    exit;
}

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($file['type'], $allowedTypes)) {
    error_log("Invalid file type: {$file['type']}");
    $response['message'] = 'Invalid file type. Only JPG, JPEG, and PNG files are allowed.';
    sendJsonResponse($response);
    exit;
}

// Create upload directory if it doesn't exist
$uploadDir = 'uploads/payment_proofs/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        error_log("Failed to create directory: $uploadDir");
        $response['message'] = 'Failed to create upload directory.';
        sendJsonResponse($response);
        exit;
    }
}

// Generate unique filename
$fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileName = $reservation['reservation_code'] . '_' . time() . '.' . $fileExt;
$filePath = $uploadDir . $fileName;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    error_log("Failed to move uploaded file to $filePath");
    $response['message'] = 'Failed to upload file.';
    sendJsonResponse($response);
    exit;
}

error_log("File uploaded successfully to $filePath");

try {
    // Get database connection using your connection file
    $mysqli = require 'database.php';
    
    // Check connection
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }
    
    error_log("Database connection successful");
    
    // Begin transaction
    $mysqli->begin_transaction();
    
    // Determine which table to use based on reservation data
    // If there's a user_id, use user_reservation, otherwise use guest_reservation
    $reservationTable = isset($reservation['user_id']) ? 'user_reservation' : 'guest_reservation';
    error_log("Using reservation table: $reservationTable");
    
    // Insert into the correct table
    if ($reservationTable === 'user_reservation') {
        $sql = "INSERT INTO user_reservation (
            user_id, reservation_code, check_in, check_out, first_name, last_name, 
            email, contact_number, adult_count, kid_count, tour_type, special_requests,
            extra_mattress, extra_pillow, extra_blanket, total_price, extras_total, total_amount
        ) VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?
        )";
        
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $mysqli->error);
        }
        
        // Default values for extras if not set
        $extra_mattress = $reservation['extras']['extra_mattress'] ?? 0;
        $extra_pillow = $reservation['extras']['extra_pillow'] ?? 0;
        $extra_blanket = $reservation['extras']['extra_blanket'] ?? 0;
        $special_requests = $reservation['special_requests'] ?? '';
        $extras_total = $reservation['extras_total'] ?? 0;
        
        $stmt->bind_param(
            "isssssssiisissiddd",
            $reservation['user_id'],
            $reservation['reservation_code'],
            $reservation['check_in'],
            $reservation['check_out'],
            $reservation['first_name'],
            $reservation['last_name'],
            $reservation['email'],
            $reservation['contact_number'],
            $reservation['adult_count'],
            $reservation['kid_count'],
            $reservation['tour_type'],
            $special_requests,
            $extra_mattress,
            $extra_pillow,
            $extra_blanket,
            $reservation['total_price'],
            $extras_total,
            $reservation['total_amount']
        );
    } else {
        $sql = "INSERT INTO guest_reservation (
            reservation_code, check_in, check_out, first_name, last_name, 
            email, contact_number, adult_count, kid_count, tour_type, special_requests,
            extra_mattress, extra_pillow, extra_blanket, base_price, extras_price, total_price
        ) VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?
        )";
        
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $mysqli->error);
        }
        
        // Default values for extras if not set
        $extra_mattress = $reservation['extras']['extra_mattress'] ?? 0;
        $extra_pillow = $reservation['extras']['extra_pillow'] ?? 0;
        $extra_blanket = $reservation['extras']['extra_blanket'] ?? 0;
        $special_requests = $reservation['special_requests'] ?? '';
        $base_price = $reservation['base_price'] ?? 0;
        $extras_price = $reservation['extras_price'] ?? 0;
        
        $stmt->bind_param(
            "sssssssiisissiddd",
            $reservation['reservation_code'],
            $reservation['check_in'],
            $reservation['check_out'],
            $reservation['first_name'],
            $reservation['last_name'],
            $reservation['email'],
            $reservation['contact_number'],
            $reservation['adult_count'],
            $reservation['kid_count'],
            $reservation['tour_type'],
            $special_requests,
            $extra_mattress,
            $extra_pillow,
            $extra_blanket,
            $base_price,
            $extras_price,
            $reservation['total_price']
        );
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $reservationId = $mysqli->insert_id;
    error_log("Reservation inserted with ID: $reservationId");
    $stmt->close();
    
    // Insert payment details
    $reservationType = $reservationTable === 'user_reservation' ? 'user' : 'guest';
    
    $paymentStmt = $mysqli->prepare("
        INSERT INTO payments (
            user_id, reservation_code, reservation_type, payment_receipt, file_path
        ) VALUES (
            ?, ?, ?, ?, ?
        )
    ");
    
    if (!$paymentStmt) {
        throw new Exception("Prepare payment failed: " . $mysqli->error);
    }
    
    $userId = $reservation['user_id'] ?? null;
    
    $paymentStmt->bind_param("issss", 
        $userId, 
        $reservation['reservation_code'], 
        $reservationType, 
        $referenceNumber, 
        $filePath
    );
    
    if (!$paymentStmt->execute()) {
        throw new Exception("Execute payment failed: " . $paymentStmt->error);
    }
    
    error_log("Payment record inserted");
    $paymentStmt->close();
    
    // Commit transaction
    $mysqli->commit();
    error_log("Transaction committed successfully");
    
    // Successfully stored data
    $response['success'] = true;
    $response['message'] = 'Payment proof uploaded successfully!';
    
    // Add reservation details to session
    $_SESSION['reservation_id'] = $reservationId;
    $_SESSION['reservation_type'] = $reservationType;
    
} catch (Exception $e) {
    // Roll back transaction if something went wrong
    if (isset($mysqli)) {
        $mysqli->rollback();
    }
    
    error_log("Error in process_payment.php: " . $e->getMessage());
    $response['message'] = 'Database error: ' . $e->getMessage();
    
    // Delete uploaded file if database operation failed
    if (file_exists($filePath)) {
        unlink($filePath);
        error_log("Deleted uploaded file due to error");
    }
}

// Close the database connection
if (isset($mysqli)) {
    $mysqli->close();
}

// Return response as JSON
sendJsonResponse($response);

// Function to properly send JSON response
function sendJsonResponse($data) {
    // Clear any previous output
    if (ob_get_length()) ob_clean();
    
    // Set proper JSON headers
    header('Content-Type: application/json');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    
    // Encode and output
    $json = json_encode($data);
    
    // Check for JSON encoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON encode error: " . json_last_error_msg());
        // Send a simple error message as plain text
        header('Content-Type: text/plain');
        echo "Error encoding response: " . json_last_error_msg();
    } else {
        echo $json;
    }
    
    // End script execution
    exit;
}