<?php
require 'db.php'; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Use prepared statements to prevent SQL injection
    $query = "SELECT check_in, check_out FROM bookings WHERE check_in <= ? AND check_out >= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $check_out, $check_in);
    $stmt->execute();
    $result = $stmt->get_result();

    $booked_dates = [];

    while ($row = $result->fetch_assoc()) {
        $start = new DateTime($row['check_in']);
        $end = new DateTime($row['check_out']);
        
        while ($start <= $end) {
            $booked_dates[] = $start->format('Y-m-d');
            $start->modify('+1 day');
        }
    }

    if (!empty($booked_dates)) {
        echo "<p class='error'>❌ These dates are already booked.</p>";
    } else {
        echo "<p class='success'>✅ Available! Proceed with reservation.</p>";
        echo "<button id='proceedToReservation' data-checkin='$check_in' data-checkout='$check_out'>Proceed to Reservation</button>";
    }

    $stmt->close();
}
?>
