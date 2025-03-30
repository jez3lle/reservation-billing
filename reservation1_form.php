<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Ensure the connection is valid
    if (!$conn) {
        die("Database connection failed.");
    }

    // Use a prepared statement
    $stmt = $conn->prepare("INSERT INTO bookings (check_in, check_out, status) VALUES (?, ?, 'pending')");

    if ($stmt) {
        $stmt->bind_param("ss", $check_in, $check_out); // "ss" means both are strings
        if ($stmt->execute()) {
            echo "Booking successful!";
        } else {
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}
?>
