<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION["user_id"]);
}

// Function to get logged in user info
function getLoggedInUser() {
    if (isUserLoggedIn()) {
        $mysqli = require __DIR__ . "/database.php";
        $stmt = $mysqli->prepare("SELECT first_name, last_name FROM user WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}

// Get relative path function
function getRelativePath() {
    // Same implementation as in your header.php
    $current_path = dirname($_SERVER['PHP_SELF']);
    $root_path = '';
    if ($current_path != '/') {
        $parts = explode('/', $current_path);
        $level = count($parts) - 1;
        if ($level > 0) {
            $root_path = str_repeat('../', $level);
        }
    }
    return $root_path;
}
?>