<?php
// Include the database connection
$mysqli = require "database.php"; // Assuming this file returns the $mysqli object

// Get available rooms
$sql = "SELECT id, name, description, day_tour_price, night_tour_price, quantity, image 
        FROM rooms 
        WHERE status = 'Available' AND quantity > 0";

$result = $mysqli->query($sql);

$rooms = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

// Return rooms as JSON
header('Content-Type: application/json');
echo json_encode($rooms);

// Close the database connection
$mysqli->close();
?>