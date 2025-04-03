<?php
session_start();

// Include database connection
require_once 'database.php';

// Validation and Sanitization Functions
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function validateDate($date) {
    return (bool)strtotime($date);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhoneNumber($phone) {
    // Basic phone number validation (adjust regex as needed)
    return preg_match('/^[0-9\-\(\)\/\+\s]{10,15}$/', $phone);
}

// Pricing Configuration
$PRICING_DATA = [
    'whole_day' => [
        'brackets' => [
            ['max' => 10, 'price' => 12000],
            ['max' => 15, 'price' => 13000],
            ['max' => 20, 'price' => 15000],
            ['max' => 25, 'price' => 16000],
            ['max' => 30, 'price' => 18000]
        ],
        'additional_per_person' => 600,
        'displayName' => "Whole Day Tour"
    ],
    'day_tour' => [
        'brackets' => [
            ['max' => 10, 'price' => 7000],
            ['max' => 15, 'price' => 8000],
            ['max' => 20, 'price' => 9000],
            ['max' => 25, 'price' => 10000],
            ['max' => 30, 'price' => 11000]
        ],
        'additional_per_person' => 400,
        'displayName' => "Day Tour"
    ],
    'night_tour' => [
        'brackets' => [
            ['max' => 10, 'price' => 8000],
            ['max' => 15, 'price' => 9000],
            ['max' => 20, 'price' => 10000],
            ['max' => 25, 'price' => 11000],
            ['max' => 30, 'price' => 12000]
        ],
        'additional_per_person' => 500,
        'displayName' => "Night Tour"
    ]
];

// Reservation Code Generation
function generateReservationCode($first_name, $last_name, $check_in) {
    $initials = strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));
    $date = new DateTime($check_in);
    $dateCode = $date->format('ymd');
    $randomNum = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    return $initials . $dateCode . $randomNum;
}

// Pricing Calculation
function calculateTotalPrice($tour_type, $total_guests, $extras, $pricing_data) {
    $pricing = $pricing_data[$tour_type];
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
    
    // Extras pricing
    $extras_prices = [
        'extra_mattress' => 150,
        'extra_pillow' => 50,
        'extra_blanket' => 50
    ];
    
    // Calculate extras price
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

// Validate Input
function validateInput($input, $check_in, $check_out, $email, $contact_number) {
    $errors = [];

    // Validate required fields
    $required_fields = ['first_name', 'last_name', 'email', 'contact_number', 'check_in', 'check_out', 'adult_count', 'tour_type'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
        }
    }

    // Validate dates
    if (!validateDate($check_in)) {
        $errors[] = "Invalid check-in date.";
    }
    if (!validateDate($check_out)) {
        $errors[] = "Invalid check-out date.";
    }
    if (strtotime($check_out) <= strtotime($check_in)) {
        $errors[] = "Check-out date must be after check-in date.";
    }

    // Validate email
    if (!validateEmail($email)) {
        $errors[] = "Invalid email address.";
    }

    // Validate phone number
    if (!validatePhoneNumber($contact_number)) {
        $errors[] = "Invalid contact number.";
    }

    // Validate numeric inputs
    $numeric_fields = ['adult_count', 'kid_count', 'extra_mattress', 'extra_pillow', 'extra_blanket'];
    foreach ($numeric_fields as $field) {
        $value = $input[$field] ?? 0;
        if (!is_numeric($value) || $value < 0) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a non-negative number.";
        }
    }

    // Validate tour type
    $valid_tour_types = ['whole_day', 'day_tour', 'night_tour'];
    if (!in_array($input['tour_type'], $valid_tour_types)) {
        $errors[] = "Invalid tour type.";
    }

    return $errors;
}

// Main Processing Logic
try {
    // Sanitize and validate input
    $sanitized_input = array_map('sanitizeInput', $_POST);
    
    // Validate input
    $validation_errors = validateInput(
        $sanitized_input, 
        $sanitized_input['check_in'], 
        $sanitized_input['check_out'], 
        $sanitized_input['email'], 
        $sanitized_input['contact_number']
    );
    
    // If there are validation errors, throw an exception
    if (!empty($validation_errors)) {
        throw new Exception(implode("\n", $validation_errors));
    }

    // Prepare input data
    $check_in = $sanitized_input['check_in'];
    $check_out = $sanitized_input['check_out'];
    $first_name = $sanitized_input['first_name'];
    $last_name = $sanitized_input['last_name'];
    $email = $sanitized_input['email'];
    $contact_number = $sanitized_input['contact_number'];
    $adult_count = intval($sanitized_input['adult_count']);
    $kid_count = intval($sanitized_input['kid_count'] ?? 0);
    $tour_type = $sanitized_input['tour_type'];
    $special_requests = $sanitized_input['special_requests'] ?? '';

    // Prepare extras
    $extras = [
        'extra_mattress' => max(0, intval($sanitized_input['extra_mattress'] ?? 0)),
        'extra_pillow' => max(0, intval($sanitized_input['extra_pillow'] ?? 0)),
        'extra_blanket' => max(0, intval($sanitized_input['extra_blanket'] ?? 0))
    ];

    // Calculate total guests and pricing
    $total_guests = $adult_count + $kid_count;
    $pricing_calculation = calculateTotalPrice($tour_type, $total_guests, $extras, $PRICING_DATA);

    // Generate reservation code
    $reservation_code = generateReservationCode($first_name, $last_name, $check_in);

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

    // Check if prepare statement failed
    if ($stmt === false) {
        throw new Exception("Prepare statement failed: " . $mysqli->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "sssssssisisiiiidd", 
        $reservation_code,
        $check_in,
        $check_out,
        $first_name,
        $last_name,
        $email,
        $contact_number,
        $adult_count,
        $kid_count,
        $tour_type,
        $special_requests,
        $extras['extra_mattress'],
        $extras['extra_pillow'],
        $extras['extra_blanket'],
        $pricing_calculation['base_price'],
        $pricing_calculation['extras_price'],
        $pricing_calculation['total_price']
    );

    // Execute the statement
    if (!$stmt->execute()) {
        throw new Exception("Execution failed: " . $stmt->error);
    }

    // Prepare session data
    $_SESSION['reservation_details'] = [
        'reservation_code' => $reservation_code,
        'check_in' => $check_in,
        'check_out' => $check_out,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'contact_number' => $contact_number,
        'adult_count' => $adult_count,
        'kid_count' => $kid_count,
        'tour_type' => $tour_type,
        'special_requests' => $special_requests,
        'extras' => $extras,
        'total_price' => $pricing_calculation['base_price'],
        'extras_total' => $pricing_calculation['extras_price'],
        'total_amount' => $pricing_calculation['total_price']
    ];

    // Close statement
    $stmt->close();

    // Redirect to billing page
    header("Location: billing.php");
    exit;

} catch (Exception $e) {
    // Log the error
    error_log("Reservation Error: " . $e->getMessage());

    // Store error in session
    $_SESSION['error_message'] = $e->getMessage();

    // Redirect to error page or back to form
    header("Location: error.php");
    exit;
} finally {
    // Ensure database connection is closed
    if (isset($mysqli)) {
        $mysqli->close();
    }
}
?>