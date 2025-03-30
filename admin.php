<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php"); // Redirect to login if not logged in
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="resort-name">Rainbow Forest Paradise Resort and Campsite</div>
            <div class="nav-item">
                <img src="icons/home.png" alt="Dashboard Icon" class="nav-icon">
                <span>Dashboard</span>
            </div>

            <a href="reservations.php" class="nav-item">
                <img src="icons/reservations.png" alt="Settings Icon" class="nav-icon">
                <span>Reservations</span>
            </a>
            <a href="private_reservations.php" class="nav-item sub-nav-item">
                <span>Private</span>
            </a>
            <a href="public_reservations.php" class="nav-item sub-nav-item">
                <span>Public</span>
            </a>
 
            <div class="nav-item">
                <img src="icons/payments.png" alt="Payments Icon" class="nav-icon">
                <span>Payments</span>
            </div>
            <div class="nav-item">
                <img src="icons/calendar.png" alt="Calendar Icon" class="nav-icon">
                <span>Calendar</span>
            </div>
            <div class="nav-item">
                <img src="icons/reports.png" alt="Reports Icon" class="nav-icon">
                <span>Reports</span>
            </div>
            <div class="nav-item">
                <img src="icons/rooms.png" alt="Rooms Icon" class="nav-icon">
                <span>Rooms</span>
            </div>
    
            <a href="content_management.php" class="nav-item">
                <img src="icons/edit.png" alt="Content Management Icon" class="nav-icon">
                <span>Content Management</span>
            </a>
            
        </div>
        <div>
            <a href="admin_settings.php" class="nav-item">
                <img src="icons/settings.png" alt="Settings Icon" class="nav-icon">
                <span>Settings</span>
            </a>  

            <a href="admin_logout.php" class="nav-item">
                <img src="icons/logout.png" alt="Logout Icon" class="nav-icon">
                <span>Logout</span>
            </a>  
        </div>
    </div>
    
    <div class="main-content">
        <h1>Dashboard</h1>
    </div>
</body>
</html>
