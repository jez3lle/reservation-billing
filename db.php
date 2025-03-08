<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Default for XAMPP
$dbname = 'rainbow_forest_paradise';

$conn = mysqli_connect($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>