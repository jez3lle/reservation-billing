

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management</title>
    <link rel="stylesheet" href="adminstyle.css">
    <style>
    h1 {
        text-align: center;
    }
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    .grid-item {
        padding: 15px;
        background: #f8f8f8;
        border-radius: 8px;
        text-align: center;
    }
    .grid-item a {
        text-decoration: none;
        font-weight: bold;
        color: #333;
    }
    form {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    input, textarea, button {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    button {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        font-weight: bold;
    }
    button:hover {
        background-color: #45a049;
    }
</style>
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
        <h1>Content Management</h1>
        <div class="grid-container">
            <div class="grid-item"><a href="edit_about.php">Edit About Us</a></div>
            <div class="grid-item"><a href="edit_home.php">Edit Home Page</a></div>
            <div class="grid-item"><a href="edit_services.php">Edit Services Page</a></div>
        </div>

    <form action="add_content.php" method="POST">
        <label for="title">Title:</label>
        <input type="text" name="title" required>

        <label for="content">Content:</label>
        <textarea name="content" required></textarea>

        <input type="hidden" name="section" value="about">

        <button type="submit">Add Content</button>
    </form>

    <!-- Delete Content Form (ensure content ID is set properly in the PHP file where this is included) -->
    <form action="delete_content.php" method="POST">
        <input type="hidden" name="content_id" id="content_id">
        <button type="submit" onclick="return confirm('Are you sure you want to delete this content?');">Delete</button>
    </form>
</div>
</body>
</html>
