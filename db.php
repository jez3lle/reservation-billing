<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Change if needed
$password = ""; // Default XAMPP password is empty
$database = "resort_db"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
