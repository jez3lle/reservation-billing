<?php
session_start();

// Database connection
require_once 'database.php';

// Debugging function
function debugLog($message) {
    error_log($message);
    // Uncomment for development, comment out in production
    // echo $message . "<br>";
}

// Input Sanitization
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Validate and Sanitize Input
function validateReservationInput($input) {
    $errors = [];

    // Required Fields Validation
    $required_fields = [
        'first_name', 'last_name', 'email', 'contact_number', 
        'check_in', 'check_out', 'adult_count', 'tour_type'
    ];

    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
        }
    }

    // Email Validation
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    // Tour Type Validation
    $valid_tour_types = ['whole_day', 'day_tour', 'night_tour'];
    if (!in_array($input['tour_type'], $valid_tour_types)) {
        $errors[] = "Invalid tour type selected.";
        // Log the actual received tour type for debugging
        debugLog("Received Tour Type: " . $input['tour_type']);
    }

    // Date Validation
    $check_in = strtotime($input['check_in']);
    $check_out = strtotime($input['check_out']);
    if ($check_in === false || $check_out === false) {
        $errors[] = "Invalid date format.";
    } elseif ($check_out <= $check_in) {
        $errors[] = "Check-out date must be after check-in date.";
    }

    // Numeric Validations
    $numeric_fields = ['adult_count', 'kid_count', 'extra_mattress', 'extra_pillow', 'extra_blanket'];
    foreach ($numeric_fields as $field) {
        $value = $input[$field] ?? 0;
        if (!is_numeric($value) || $value < 0) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a non-negative number.";
        }
    }

    return $errors;
}

// Pricing Configuration
$PRICING_CONFIG = [
    'whole_day' => [
        'brackets' => [
            ['max' => 10, 'price' => 12000],
            ['max' => 15, 'price' => 13000],
            ['max' => 20, 'price' => 15000],
            ['max' => 25, 'price' => 16000],
            ['max' => 30, 'price' => 18000]
        ],
        'additional_per_person' => 600
    ],
    'day_tour' => [
        'brackets' => [
            ['max' => 10, 'price' => 7000],
            ['max' => 15, 'price' => 8000],
            ['max' => 20, 'price' => 9000],
            ['max' => 25, 'price' => 10000],
            ['max' => 30, 'price' => 11000]
        ],
        'additional_per_person' => 400
    ],
    'night_tour' => [
        'brackets' => [
            ['max' => 10, 'price' => 8000],
            ['max' => 15, 'price' => 9000],
            ['max' => 20, 'price' => 10000],
            ['max' => 25, 'price' => 11000],
            ['max' => 30, 'price' => 12000]
        ],
        'additional_per_person' => 500
    ]
];

// Generate Unique Reservation Code
function generateReservationCode($first_name, $last_name, $check_in) {
    $initials = strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));
    $date = new DateTime($check_in);
    $dateCode = $date->format('ymd');
    $randomNum = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    return $initials . $dateCode . $randomNum;
}

// Calculate Total Price
function calculateTotalPrice($tour_type, $total_guests, $extras, $pricing_config) {
    // Ensure tour type exists in configuration
    if (!isset($pricing_config[$tour_type])) {
        throw new Exception("Invalid tour type: {$tour_type}");
    }

    $pricing = $pricing_config[$tour_type];
    $base_price = 0;

    // Calculate base price
    foreach ($pricing['brackets'] as $bracket) {
        if ($total_guests <= $bracket['max']) {
            $base_price = $bracket['price'];
            break;
        }
    }

    // Handle guests beyond 30
    if ($base_price === 0) {
        $last_bracket = end($pricing['brackets']);
        $extra_guests = $total_guests - 30;
        $base_price = $last_bracket['price'] + ($extra_guests * $pricing['additional_per_person']);
    }

    // Calculate extras
    $extras_prices = [
        'extra_mattress' => 150,
        'extra_pillow' => 50,
        'extra_blanket' => 50
    ];

    $extras_price = 0;
    foreach ($extras_prices as $extra => $price) {
        $extras_price += ($extras[$extra] ?? 0) * $price;
    }

    return [
        'base_price' => $base_price,
        'extras_price' => $extras_price,
        'total_price' => $base_price + $extras_price
    ];
}

// Main Reservation Processing
try {
    // Sanitize all input
    $sanitized_input = sanitizeInput($_POST);

    // Validate input
    $validation_errors = validateReservationInput($sanitized_input);
    
    // If there are validation errors, throw an exception
    if (!empty($validation_errors)) {
        throw new Exception(implode("\n", $validation_errors));
    }

    // Extract and prepare input data
    $tour_type = $sanitized_input['tour_type'];
    debugLog("Processed Tour Type: " . $tour_type);

    $reservation_details = [
        'check_in' => $sanitized_input['check_in'],
        'check_out' => $sanitized_input['check_out'],
        'first_name' => $sanitized_input['first_name'],
        'last_name' => $sanitized_input['last_name'],
        'email' => $sanitized_input['email'],
        'contact_number' => $sanitized_input['contact_number'],
        'adult_count' => intval($sanitized_input['adult_count']),
        'kid_count' => intval($sanitized_input['kid_count'] ?? 0),
        'tour_type' => $tour_type,
        'special_requests' => $sanitized_input['special_requests'] ?? '',
    ];

    // Prepare extras
    $extras = [
        'extra_mattress' => max(0, intval($sanitized_input['extra_mattress'] ?? 0)),
        'extra_pillow' => max(0, intval($sanitized_input['extra_pillow'] ?? 0)),
        'extra_blanket' => max(0, intval($sanitized_input['extra_blanket'] ?? 0))
    ];

    // Calculate total guests and pricing
    $total_guests = $reservation_details['adult_count'] + $reservation_details['kid_count'];
    $pricing_calculation = calculateTotalPrice($tour_type, $total_guests, $extras, $PRICING_CONFIG);

    // Generate reservation code
    $reservation_code = generateReservationCode(
        $reservation_details['first_name'], 
        $reservation_details['last_name'], 
        $reservation_details['check_in']
    );

    // Prepare SQL insertion
    $stmt = $mysqli->prepare("INSERT INTO guest_reservation (
        reservation_code, 
        check_in, 
        check_out, 
        first_name, 
        last_name, 
        email, 
        contact_number, 
        adult_count, 
        kid_count, 
        tour_type, 
        special_requests, 
        extra_mattress, 
        extra_pillow, 
        extra_blanket, 
        base_price, 
        extras_price, 
        total_price
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param(
        "sssssssisisiiiidd", 
        $reservation_code,
        $reservation_details['check_in'],
        $reservation_details['check_out'],
        $reservation_details['first_name'],
        $reservation_details['last_name'],
        $reservation_details['email'],
        $reservation_details['contact_number'],
        $reservation_details['adult_count'],
        $reservation_details['kid_count'],
        $reservation_details['tour_type'],
        $reservation_details['special_requests'],
        $extras['extra_mattress'],
        $extras['extra_pillow'],
        $extras['extra_blanket'],
        $pricing_calculation['base_price'],
        $pricing_calculation['extras_price'],
        $pricing_calculation['total_price']
    );

    // Execute the statement
    if (!$stmt->execute()) {
        throw new Exception("Database insertion failed: " . $stmt->error);
    }

    // Prepare session data
    $_SESSION['reservation_details'] = array_merge($reservation_details, [
        'reservation_code' => $reservation_code,
        'extras' => $extras,
        'total_price' => $pricing_calculation['base_price'],
        'extras_total' => $pricing_calculation['extras_price'],
        'total_amount' => $pricing_calculation['total_price']
    ]);

    // Close statement
    $stmt->close();

    // Redirect to billing page
    header("Location: billing.php");
    exit;

} catch (Exception $e) {
    // Log the error
    debugLog("Reservation Error: " . $e->getMessage());

    // Store error in session
    $_SESSION['error_message'] = $e->getMessage();

    // Redirect to error page
    header("Location: error.php");
    exit;
} finally {
    // Ensure database connection is closed
    if (isset($mysqli)) {
        $mysqli->close();
    }
}
?>