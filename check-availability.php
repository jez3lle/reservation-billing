<?php
include('db.php'); // Ensure database connection is included

if (isset($_GET['arrival-date']) && isset($_GET['departure-date'])) {
    $arrivalDate = $_GET['arrival-date'];
    $departureDate = $_GET['departure-date'];

    // Query to check overlapping reservations
    $query = "SELECT * FROM reservations 
              WHERE NOT (departure_date < '$arrivalDate' OR arrival_date > '$departureDate')";
    $result = mysqli_query($conn, $query);

    // Check availability
    $isAvailable = (mysqli_num_rows($result) == 0);

    echo json_encode(['available' => $isAvailable]);
}
?>
