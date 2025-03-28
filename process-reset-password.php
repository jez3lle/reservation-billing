<?php

$token = $_POST["token"];
$token_hash = hash("sha256", $token);
$mysqli = require __DIR__ . "/database.php";
$sql = "SELECT * FROM user
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if($user === null){
    die("token not found");
}

if(strtotime($user["reset_token_expires_at"]) <= time()){
    die("Token has expired.");
}
if (empty($_POST['password'])) {
    die("Please input password");
} elseif (strlen($_POST['password']) < 8) {
    die("Password too short");
} elseif (!preg_match('/[a-z]/i', $_POST['password'])) {
    die("Password must contain at least one letter");
} elseif (!preg_match('/[0-9]/', $_POST['password'])) {
    die("Password must contain at least one number");
} elseif ($_POST['password'] !== $_POST['password_confirmation']) {
    die("Passwords don't match");    
}

// If there are no errors, proceed with registration
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "UPDATE user SET password_hash = ?, reset_token_hash = NULL, reset_token_expires_At = NULL WHERE id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $password_hash, $user["id"]);
$stmt->execute();

echo '<p>Password Updated! You can now <a href="login.php">login</a></p>';
?>