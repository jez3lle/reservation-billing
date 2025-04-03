<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit;
}

// Database connection
$mysqli = require __DIR__ . "/database.php";

// Define $success and $error variables
$success = $error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $contact_number = $_POST["contact_number"];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Valid email is required";
    } else {
        // Check if email already exists but belongs to someone else
        $stmt = $mysqli->prepare("SELECT id FROM user WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Email already taken";
        } else {
            // Update user information
            $stmt = $mysqli->prepare("UPDATE user SET first_name = ?, last_name = ?, email = ?, contact_number = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $first_name, $last_name, $email, $contact_number, $_SESSION["user_id"]);
            
            if ($stmt->execute()) {
                $success = "Profile updated successfully!";
            } else {
                $error = "Error updating profile: " . $mysqli->error;
            }
        }
    }
}

// Get current user info
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
    <link rel="stylesheet" href="style.css">
    <title>Edit Profile</title>
    <style>
        .edit-profile-form {
            max-width: 600px;
            margin: 25px;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .form-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .submit-btn {
            background-color: #4CAF50;
            color: white;
        }
        
        .cancel-btn {
            background-color: #f44336;
            color: white;
        }
        
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        h1{
            margin-top: 20px;
        }
        .user-info{
            display: flex;
            flex-direction: column;
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
            <div class="phase-card phase-private">
                <h2>PHASE 1</h2>
                <h3>PRIVATE</h3>
                <p>
                    Enjoy exclusive access to the entire resort! This includes two pools, two houses, a pavilion, and a cozy kubo, ensuring privacy and relaxation.
                    Perfect for families, reunions, and private gatherings. Guests can also partake in exciting activities available in the public area.
                </p>
                <button onclick="bookNow('Phase 1')">BOOK NOW</button>
            </div>
            <div class="phase-card phase-public">
                <h2>PHASE 2</h2>
                <h3>PUBLIC</h3>

                <p>
                    Stay in our welcoming accommodations, including rooms, cabins, and houses, ideal for individuals or small groups. 
                    Enjoy thrilling activities such as ziplining, bonfires, and swimming, making your stay an unforgettable adventure!
                </p>
                <button onclick="bookNow('Phase 2')">BOOK NOW</button>
            </div>            
        </div>
    </div>

    <header class="page-header">
        <div class="navbar">
            <!-- Logo -->
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
                <li><a href="accommodation_p1.php">ACCOMMODATIONS</a></li>
                <li><a href="activties_p1.php">ACTIVITIES</a></li>
                <li><a href="#">BOOK NOW</a></li>
            </ul>
            <div class="icon">
                <?php if($user): ?>
                    <div class="user-info">
                        <span class="user-name">Hello, <?= htmlspecialchars($user["first_name"]) ?></span>
                        <div class="user-actions">
                            <a href="account.php" class="profile-btn">My Profile</a>
                            <form action="logout.php" method="post">
                                <button type="submit" class="logout-btn">Logout</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Just a placeholder since we're already on the login page -->
                    <a href="login.php" class="user-icon">
                        <img src="images/logo.png" alt="User Icon">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <h1 class="account">Edit Profile</h1>
    
    <div class="edit-profile-form">
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="post" action="edit_profile.php" id="edit-form">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user["first_name"]) ?>">
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user["last_name"]) ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>">
            </div>
            
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" value="<?= htmlspecialchars($user["contact_number"] ?? "") ?>">
            </div>
            
            <div class="form-buttons">
                <a href="account.php" class="cancel-btn" style="text-decoration: none; display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; border-radius: 4px;">Cancel</a>
                <button type="submit" class="submit-btn">Save Changes</button>
            </div>
        </form>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img src="images/rainbow-logo.png" alt="Rainbow Forest Logo">
            </div>
            <div class="footer-nav">
                <h3>Explore</h3>
                <ul>
                    <li><a href="#">Accommodations</a></li>
                    <li><a href="#">Activities</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h3>Contact Us</h3>
                <p><strong>Address:</strong> Brgy. Cuyambay, Tanay, Rizal</p>
                <p><strong>Contact No.:</strong> 0960 587 7561</p>
            </div>
            <div class="footer-actions">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Follow Us</a></li>
                    <li><a href="#">Book Now</a></li>
                    <li><a href="#">Cancel Reservation</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            const hamburger = document.querySelector('.hamburger');
            const hamburgerVertical = document.querySelector('.hamburger-vertical');
            const header = document.querySelector('.page-header');
            menu.classList.toggle('active');
            header.classList.toggle('hidden');
            if (menu.classList.contains('active')) {
                hamburger.style.display = 'none';
                hamburgerVertical.style.display = 'block';
            } else {
                hamburger.style.display = 'block';
                hamburgerVertical.style.display = 'none';
            }
        }

        function bookNow(phase) {
            alert(`You clicked Book Now for ${phase}!`);
        }
    </script>
    <script>
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            const hamburger = document.querySelector('.hamburger');
            const hamburgerVertical = document.querySelector('.hamburger-vertical');
            const header = document.querySelector('.page-header');
            menu.classList.toggle('active');
            header.classList.toggle('hidden');
            if (menu.classList.contains('active')) {
                hamburger.style.display = 'none';
                hamburgerVertical.style.display = 'block';
            } else {
                hamburger.style.display = 'block';
                hamburgerVertical.style.display = 'none';
            }
        }

        function bookNow(phase) {
            alert(`You clicked Book Now for ${phase}!`);
        }
    </script>

    <script>
    // Wait for the DOM to be fully loaded
    document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("edit-form");

    form.addEventListener("submit", function(event) {
        let errors = {};
        let hasErrors = false;

        // Validate Name
        const first_name = document.getElementById("first_name").value.trim();
        if (first_name === "") {
            errors.first_name = "First Name is required";
            hasErrors = true;
        }
        
        const last_name = document.getElementById("last_name").value.trim();
        if (last_name === ""){
            errors.last_name = "Last Name is required";
            hasErrors = true;
        }

        // Validate Email
        const email = document.getElementById("email").value.trim();
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (email === "" || !emailRegex.test(email)) {
            errors.email = "A valid email is required";
            hasErrors = true;
        }

        // Validate Contact Number
        const contactNumber = document.getElementById("contact_number").value.trim();
        const contactNumberRegex = /^\+?[0-9]{10,15}$/; // Allow optional country code and 10-15 digits
        if (contactNumber === "") {
            errors.contact_number = "Contact number is required";
            hasErrors = true;
        } else if (!contactNumberRegex.test(contactNumber)) {
            errors.contact_number = "Invalid contact number format";
            hasErrors = true;
        }
        
        // If there are errors, prevent form submission and display errors
        if (hasErrors) {
            event.preventDefault();
            
            // Remove any existing error messages
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            
            // Display new error messages
            for (const field in errors) {
                const input = document.getElementById(field);
                const errorSpan = document.createElement('span');
                errorSpan.className = 'error-message';
                errorSpan.style.color = 'red';
                errorSpan.style.fontSize = '12px';
                errorSpan.style.display = 'block';
                errorSpan.style.marginTop = '5px';
                errorSpan.textContent = errors[field];
                input.parentNode.appendChild(errorSpan);
            }
        }
    });
});
    </script>
</body>
</html>