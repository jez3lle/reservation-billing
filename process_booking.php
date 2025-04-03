<?php
session_start();
include 'db.php'; // Ensure database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $_SESSION['guest_name'] = $_POST['guest_name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['check_in'] = $_POST['check_in'];
    $_SESSION['check_out'] = $_POST['check_out'];
    $_SESSION['room_type'] = $_POST['room_type'];
    $_SESSION['guests'] = $_POST['guests'];
    $_SESSION['special_requests'] = $_POST['requests'];

    // Insert data into database
    $conn = new mysqli("localhost", "root", "", "resort_db"); // Update with your actual database details
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO bookings (guest_name, email, phone, check_in, check_out, room_type, guests, special_requests) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssis", $_SESSION['guest_name'], $_SESSION['email'], $_SESSION['phone'], $_SESSION['check_in'], $_SESSION['check_out'], $_SESSION['room_type'], $_SESSION['guests'], $_SESSION['special_requests']);

    if ($stmt->execute()) {
        header("Location: booking_success.php"); // Redirect to success page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
