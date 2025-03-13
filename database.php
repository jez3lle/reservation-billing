<?php

$host = "localhost";
$dbname = "resort_db";
$username = "root";
$password = "";

$mysqli = new mysqli($host, $username, $password, $dbname);

return $mysqli;
?>