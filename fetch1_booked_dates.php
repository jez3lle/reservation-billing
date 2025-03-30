<?php
require 'db.php'; // Ensure database connection

header('Content-Type: application/json');

// Fetch booked dates from the database
$sql = "SELECT check_in, check_out FROM bookings";
$result = $conn->query($sql);

$booked_dates = [];

while ($row = $result->fetch_assoc()) {
    $check_in = $row['check_in'];
    $check_out = $row['check_out'];

    // Debugging
    error_log("🔍 Fetched: Check-in = $check_in, Check-out = $check_out");

    // Extend booking to include last day
    $end_date = date('Y-m-d', strtotime($check_out . ' +1 day'));

    $booked_dates[] = [
        'title' => 'Booked',
        'start' => $check_in,
        'end' => $end_date, // Extends the last day
        'display' => 'background', // Makes the whole cell red
        'color' => 'red' // Background color red
    ];
}

// Debugging
error_log("📢 JSON Output: " . json_encode($booked_dates));

echo json_encode($booked_dates);
?>
