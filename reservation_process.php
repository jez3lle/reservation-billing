<?php
session_start();

// Database connection
$mysqli = require 'database.php';

// Assuming user is logged in and user_id is in the session
// If not, you'll need to adjust this logic
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    // Handle case where user is not logged in
    $_SESSION['error_message'] = "Please log in to make a reservation.";
    header("Location: login.php");
    exit;
}

// Validate and process form data
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$contact_number = $_POST['contact_number'];
$adult_count = $_POST['adult_count'];
$kid_count = $_POST['kid_count'] ?? 0;
$tour_type = $_POST['tour_type'];
$special_requests = $_POST['special_requests'] ?? '';

// Generate unique reservation code
$date = date('Ymd');
$random_string = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
$reservation_code = $date . '-' . $random_string;

// Pricing logic remains the same as in the previous script
$pricing_data = [
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

// [Previous calculateTotalPrice function remains the same]
function calculateTotalPrice($tour_type, $total_guests, $extras) {
    global $pricing_data;
    
    $pricing = $pricing_data[$tour_type];
    $base_price = 0;
    
    // Calculate base price based on guest count
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
    
    // Extras prices
    $extras_prices = [
        'extra_mattress' => 150,
        'extra_pillow' => 50,
        'extra_blanket' => 50
    ];
    
    // Calculate extras price
    $extras_price = 0;
    foreach ($extras as $extra => $quantity) {
        $extras_price += $quantity * $extras_prices[$extra];
    }
    
    return [
        'base_price' => $base_price,
        'extras_price' => $extras_price,
        'total_price' => $base_price + $extras_price
    ];
}

// Prepare extras with default to 0 and filter out zeros
$extras = [
    'extra_mattress' => max(0, intval($_POST['extra_mattress'] ?? 0)),
    'extra_pillow' => max(0, intval($_POST['extra_pillow'] ?? 0)),
    'extra_blanket' => max(0, intval($_POST['extra_blanket'] ?? 0))
];

$total_guests = $adult_count + $kid_count;
$pricing_calculation = calculateTotalPrice($tour_type, $total_guests, $extras);

// Prepare extras prices for display
$extras_prices = [
    'extra_mattress' => 150,
    'extra_pillow' => 50,
    'extra_blanket' => 50
];

// Prepare reservation details for session
$_SESSION['reservation_details'] = [
    'user_id' => $user_id, // Added user_id to session
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
    'extras_prices' => $extras_prices,
    'total_price' => $pricing_calculation['base_price'],
    'extras_total' => $pricing_calculation['extras_price'],
    'total_amount' => $pricing_calculation['total_price']
];

// Store reservation in database
$stmt = $mysqli->prepare("INSERT INTO user_reservation (
    user_id,
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
    total_price, 
    extras_total, 
    total_amount
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "isssssssiiisssiiii", 
    $user_id,
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
if ($stmt->execute()) {
    // Successful database insertion
    $_SESSION['reservation_success'] = true;
} else {
    // Database insertion failed
    $_SESSION['reservation_success'] = false;
    $_SESSION['error_message'] = $stmt->error;
}

// Close statement
$stmt->close();

// Redirect to billing page
header("Location: billing.php");
exit;
?>