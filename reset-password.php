<?php

$token = $_GET["token"] ?? null; 

if ($token === null) {
    die("Token is required.");
}

$token_hash = hash("sha256", $token);
$mysqli = require __DIR__ . "/database.php";

// Check if the database connection was successful
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT * FROM user WHERE reset_token_hash = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die("Failed to prepare statement: " . $mysqli->error);
}

$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found.");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired.");
}

echo "Token is valid and hasn't expired.";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <form method="post" action="process-reset-password.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirmation">Repeat Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <button type="submit">Submit</button>
    </form>
</body>
</html>