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

    // Extend check-out date for FullCalendar
    $end_date = date('Y-m-d', strtotime($check_out . ' +1 day'));

    $booked_dates[] = [
        'title' => 'Booked',
        'start' => $check_in,
        'end' => $end_date,
        'display' => 'background',
        'color' => 'red' // Red background color
    ];
}

echo json_encode($booked_dates);
?>
