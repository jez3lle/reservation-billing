<?php
// Include the database connection
$mysqli = require "database.php"; // Assuming this file returns the $mysqli object

// Get arrival and departure dates from the request
$arrival_date = $_GET['arrival_date'];
$departure_date = $_GET['departure_date'];

// Log the received dates for debugging
error_log("Arrival Date: $arrival_date, Departure Date: $departure_date");

// Prepare the SQL query to find available rooms
$sql = "
    SELECT r.id, r.name, r.description, r.day_tour_price, r.night_tour_price, r.quantity, r.image 
    FROM rooms r
    WHERE r.status = 'Available' 
    AND r.quantity > 0 
    AND r.id NOT IN (
        SELECT room_id 
        FROM p2_guest_reservation_room g
        JOIN p2_guest_reservation r ON g.reservation_id = r.id
        WHERE (r.check_in < ? AND r.check_out > ?)
    )
";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    error_log("SQL Prepare Error: " . $mysqli->error);
    die("SQL Prepare Error: " . $mysqli->error);
}

$stmt->bind_param("ss", $departure_date, $arrival_date); // Bind parameters to prevent SQL injection

if (!$stmt->execute()) {
    error_log("SQL Execute Error: " . $stmt->error);
    die("SQL Execute Error: " . $stmt->error);
}

$result = $stmt->get_result();

$rooms = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
} else {
    error_log("No rooms available for the selected dates.");
}

// Log the rooms array for debugging
error_log("Available Rooms: " . json_encode($rooms));

// Return rooms as JSON
header('Content-Type: application/json');
echo json_encode($rooms);

// Close the database connection
$stmt->close();
$mysqli->close();
?>