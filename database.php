<?php

$host = "localhost";
$dbname = "resort_db";
$username = "root";
$password = "";

// Create connection
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset to UTF-8 for better character support
$mysqli->set_charset("utf8mb4");

return $mysqli;
?>
