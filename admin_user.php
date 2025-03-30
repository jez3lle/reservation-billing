<?php
include 'db_connect.php'; // Database connection
session_start();

// Fetch all users from the database
$query = "SELECT * FROM admin_users";
$result = $conn->query($query); // Execute the query

if (!$result) {
    die("Database query failed: " . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Backend Password Validation (PHP)
    if (strlen($password) < 8 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[\W]/', $password)) {
        $_SESSION['error_message'] = "Password must be at least 8 characters long and include an uppercase letter, a number, and a special character.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO admin_users (name, email, role, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $role, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "User added successfully!";
        } else {
            $_SESSION['error_message'] = "Error adding user!";
        }
        $stmt->close();
    }

    header("Location: admin_user.php"); // Reload the same page to show messages
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <link rel="stylesheet" href="adminstyle.css">
    <style>
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #007bff;
            color: white;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
    </style>
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
    <h1>Admin Management</h1>
    <a href="admin_add_user.php" class="add-btn">+ Add Admin</a>

    <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
            unset($_SESSION['success_message']); // Clear message after showing
        }

        if (isset($_SESSION['error_message'])) {
            echo "<div class='error-message'>" . $_SESSION['error_message'] . "</div>";
            unset($_SESSION['error_message']); // Clear message after showing
        }
    ?>

    <div class="back-container">
        <a href="admin_settings.php" class="back-btn">← Back to Admin Settings</a>
    </div>
    
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td>
                <a href="admin_edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                <button class="btn btn-delete" onclick="openDeleteModal(<?php echo $row['id']; ?>)">Delete</button>
            </td>

        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this user?</p>
            <div class="modal-buttons">
                <button class="modal-confirm" onclick="confirmDelete()">Yes, Delete</button>
                <button class="modal-cancel" onclick="closeDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
        let deleteUserId = null; // Store user ID to delete

        function openDeleteModal(userId) {
            deleteUserId = userId;
            document.getElementById("deleteModal").style.display = "block";
        }

        function closeDeleteModal() {
            document.getElementById("deleteModal").style.display = "none";
        }

        function confirmDelete() {
            if (deleteUserId) {
                window.location.href = "admin_delete_user.php?id=" + deleteUserId;
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            let messageBox = document.querySelector(".message-box");
            if (messageBox) {
                messageBox.style.display = "block";
                setTimeout(() => {
                    messageBox.style.display = "none";
                }, 3000); // Hide after 3 seconds
            }
        });
    </script>


</body>
</html>
