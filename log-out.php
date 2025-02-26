<?php
session_start();
$_SESSION['logout_message'] = "You have been successfully logged out."; // Store message
session_destroy(); // Destroy all session data
header("Location: login.php"); // Redirect to login page
exit();
?>
