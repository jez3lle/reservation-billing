<?php
include 'db_connect.php'; // Database connection

// Fetch all rooms
$result = $conn->query("SELECT * FROM rooms");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
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
        <h2>Room Management</h2>
        <a href="admin_add_room.php" class="btn">Add New Room</a>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Room Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td>$<?php echo number_format($row['price'], 2); ?></td>
                <td>
                    <?php if ($row['image']): ?>
                        <img src="uploads/<?php echo $row['image']; ?>" width="80">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <a href="admin_edit_room.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                    <a href="admin_delete_room.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
