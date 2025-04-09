<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit;
}

// Database connection
$mysqli = require __DIR__ . "/database.php";

// Get full user info from database
$stmt = $mysqli->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If user doesn't exist (was deleted or session is invalid)
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <title>Account</title>
    <style>
        /* Style for the account-info card */
        .account-info {
            margin-top: 100px;
            margin-bottom: 100px;
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .account-info h1 {
            margin-bottom: 20px;
            font-size: 32px;
            color: #333;
        }

        .account-info p {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
            margin: 10px 0;
        }

        /* Profile picture style */
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 20px;
            object-fit: cover;
        }

        /* Buttons and User Actions */
        .user-actions {
            margin-top: 30px;
        }

        .profile-btn, .bookings-btn {
            padding: 12px 25px;
            margin: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .profile-btn:hover, .bookings-btn:hover {
            background-color: #0056b3;
        }

        .bookings-btn {
            background-color: #28a745;
        }

        .bookings-btn:hover {
            background-color: #218838;
        }

        /* Logout button positioned at the header */
        .logout-btn {
            padding: 12px 25px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        /* Header user info */
        .header-user-info {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            color: white;
        }

        .header-user-info span {
            margin-right: 15px;
        }
        .account-info {
            margin-top: 140px;
            margin-bottom: 140px;
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<div class="top-space">
    <div class="hamburger" onclick="toggleMenu()">☰</div>
</div>

<div class="menu">
    <div class="close-icon" onclick="toggleMenu()">X</div>
    <div class="menucontainer">
        <!-- Menu content here -->
    </div>
</div>

<header class="page-header">
    <div class="navbar">
        <div class="logo">
            <img src="images/rainbow-logo.png" alt="Resort Logo">
            <div class="logo-text">
                <h1>Rainbow Forest Paradise</h1>
                <h2>Resort and Campsite</h2>
            </div>
        </div>
        <ul class="nav-links">
            <li><a href="home_p1.php">HOME</a></li>
            <li><a href="aboutus_p1.php">ABOUT</a></li>
            <li><a href="accomodation_p1.php">ACCOMMODATIONS</a></li>
            <li><a href="activties_p1.php">ACTIVITIES</a></li>
            <li><a href="#">BOOK NOW</a></li>
        </ul>
        <!-- Logout button in the header -->
        <div class="header-user-info">
            <span>Hello, <?= htmlspecialchars($user["first_name"] . " " . $user["last_name"]) ?></span>
            <form action="logout.php" method="post">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
</header>

<div class="account-info">

    <h1>Account Information</h1>
    <p><strong>Name:</strong> <?= htmlspecialchars($user["first_name"] . " " . $user["last_name"]) ?></p>
    <p><strong>Email Address:</strong> <?= htmlspecialchars($user["email"]) ?></p>
    <p><strong>Contact Number:</strong> <?= htmlspecialchars($user["contact_number"] ?? "N/A") ?></p>
    
    <div class="user-actions">
        <a href="edit_profile.php" class="profile-btn">Edit Profile</a>
        <a href="bookings.php" class="bookings-btn">View Bookings</a>
    </div>
</div>

<?php include "headers/footer.php"  ?>

<script>
    function toggleMenu() {
        const menu = document.querySelector('.menu');
        const hamburger = document.querySelector('.hamburger');
        const header = document.querySelector('.page-header');
        menu.classList.toggle('active');
        header.classList.toggle('hidden');
        if (menu.classList.contains('active')) {
            hamburger.style.display = 'none';
        } else {
            hamburger.style.display = 'block';
        }
    }
</script>

</body>
</html>
