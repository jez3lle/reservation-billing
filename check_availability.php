<?php
session_start();
$conn = new mysqli("localhost", "root", "", "resort_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];

$check_in_date = DateTime::createFromFormat('Y-m-d', $check_in);
$check_out_date = DateTime::createFromFormat('Y-m-d', $check_out);

if (!$check_in_date || !$check_out_date) {
    echo "<p style='color: red;'>Invalid date format.</p>";
    exit;
}

if ($check_in_date >= $check_out_date) {
    echo "<p style='color: red;'>Check-out date must be after check-in date.</p>";
    exit;
}

// Store in session so it persists across pages
$_SESSION['check_in'] = $check_in;
$_SESSION['check_out'] = $check_out;

$sql = "SELECT * FROM reservations 
        WHERE (check_in < ? AND check_out > ?) 
        OR (check_in >= ? AND check_in < ?) 
        OR (check_out > ? AND check_out <= ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $check_out, $check_in, $check_in, $check_out, $check_in, $check_out);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<p style='color: red;'>Not Available. Please select different dates.</p>";
} else {
    echo "<p style='color: green;'>Available! You can proceed with booking.</p>";
}

$stmt->close();
$conn->close();
?>
