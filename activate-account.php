<?php

$token = $_GET["token"] ?? null; // Use null coalescing operator to avoid undefined index notice

$token_hash = hash("sha256", $token);
$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM user WHERE account_activation_hash = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found.");
}

$sql = "UPDATE user SET account_activation_hash = NULL WHERE id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user["id"]); // Use "i" for integer type
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Account Activated</title>
</head>
<body>
    <h1>Account Activated</h1>
    <p>Account activated successfully!. You can now 
    <a href="index.php">Login</a></p>
</body>
</html>