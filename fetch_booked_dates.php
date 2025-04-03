<?php
include "db_connect.php";

$booked_dates = [];
$sql = "SELECT check_in, check_out FROM bookings WHERE status = 'confirmed'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $start = $row['check_in'];
    $end = $row['check_out'];

    $period = new DatePeriod(
        new DateTime($start),
        new DateInterval('P1D'),
        (new DateTime($end))->modify('+1 day')
    );

    foreach ($period as $date) {
        $booked_dates[] = $date->format("Y-m-d");
    }
}

echo json_encode($booked_dates);
$conn->close();
?>
