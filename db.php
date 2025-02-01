<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Default for XAMPP
$dbname = 'rainbow_forest_paradise';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
