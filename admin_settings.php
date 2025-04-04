<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="resort-name">Rainbow Forest Paradise Resort and Campsite</div>
            <a href="admin.php" class="nav-item">
                <img src="icons/home.png" alt="Dashboard Icon" class="nav-icon">
                <span>Reservations</span>
            </a>

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
        <h1>Admin Settings</h1>
        <div class="settings-container">
            <div class="setting-card">
                <img src="icons/users.png" alt="Users Icon">
                <h3>User Management</h3>
                <p>Manage user accounts, roles, and permissions.</p>
                <a href="admin_user.php" class="btn">Manage Users</a>
            </div>
            <div class="setting-card">
                <img src="icons/backup.png" alt="Backup Icon">
                <h3>Data Backup</h3>
                <p>Backup and restore system data.</p>
                <a href="admin_backup.php" class="btn">Manage Backups</a>
            </div>
            <div class="setting-card">
                <img src="icons/rooms.png" alt="Rooms Icon">
                <h3>Room Management</h3>
                <p>Update room descriptions and prices.</p>
                <a href="admin_rooms.php" class="btn">Manage Rooms</a>
            </div>
            <div class="setting-card">
                <img src="icons/notification.png" alt="Notifications Icon">
                <h3>Notifications</h3>
                <p>View and manage admin notifications.</p>
                <a href="admin_dashboard.php#notifications" class="btn">View Notifications</a>
            </div>
        </div>
    </div>
</body>
</html>
