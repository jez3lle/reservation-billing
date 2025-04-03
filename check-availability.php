<?php
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    $sql = "SELECT * FROM bookings WHERE (check_in <= '$check_out' AND check_out >= '$check_in')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<p class='error'>Selected dates are already booked.</p>";
    } else {
        echo "<p class='success'>Available! You can proceed to reservation.</p>";
    }
}
?>
