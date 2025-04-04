<?php
session_start(); // Start the session at the beginning

// Store check-in/check-out dates if redirected from booking page
if (isset($_GET['check_in']) && isset($_GET['check_out'])) {
    $_SESSION['check_in'] = $_GET['check_in'];
    $_SESSION['check_out'] = $_GET['check_out'];
}

// Store the referring page for redirection after login
if (!isset($_SESSION['redirect_after_login'])) {
    // Get the referrer or set a default
    $referrer = $_SERVER['HTTP_REFERER'] ?? '';
    
    // Check if the referrer contains home_p1.php or home_p2.php
    if (strpos($referrer, 'home_p1.php') !== false) {
        $_SESSION['redirect_after_login'] = 'home_p1.php';
    } elseif (strpos($referrer, 'home_p2.php') !== false) {
        $_SESSION['redirect_after_login'] = 'home_p2.php';
    } else {
        // Default redirect if not coming from a specific page
        $_SESSION['redirect_after_login'] = 'home_p1.php';
    }
}

$if_invalid = false;    
$error_message = ""; // Initialize an error message variable

function getUserStatus() {
    if (isset($_SESSION["user_id"])) {
        $mysqli = require __DIR__ . "/database.php";
        $stmt = $mysqli->prepare("SELECT first_name, last_name FROM user WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}

// Get the current user if logged in
$current_user = getUserStatus();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Include the database connection
    $mysqli = require __DIR__ . "/database.php";

    // Prepare the SQL statement to prevent SQL injection
    $email = $_POST["email"] ?? ''; // Use null coalescing operator to handle undefined index
    $password = $_POST["password"] ?? '';

    // Use prepared statements for better security
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ?");
    if ($stmt === false) {
        die("Error preparing the query: " . $mysqli->error);
    }

    $stmt->bind_param("s", $email); // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists and if the account is activated
    if ($user && password_verify($password, $user["password_hash"])) {
        if ($user["account_activation_hash"] === null) {
            // Regenerate session ID and store user ID
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            
            // Determine where to redirect the user
            if (isset($_SESSION['check_in']) && isset($_SESSION['check_out'])) {
                // If there are check-in/out dates, redirect to reservation form
                header("Location: reservation_form.php");
                exit();
            } elseif (isset($_SESSION['redirect_after_login'])) {
                // Redirect to the stored referring page
                $redirect_page = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']); // Clear the stored redirect
                header("Location: " . $redirect_page);
                exit();
            } else {
                // Default redirect
                header("Location: home_p1.php");
                exit();
            }
        } else {
            $if_invalid = true;
            $error_message = "Your account is not activated. Please check your email.";
        }
    } else {
        $if_invalid = true;
        $error_message = "Incorrect email or password.";
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/just-validate@3.0.1/dist/js/just-validate.min.js"></script> <!-- Include JustValidate -->
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
                    <li><a href="aboutus.php">ABOUT</a></li>
                    <li><a href="accomodation_p1.php">ACCOMMODATIONS</a></li>
                    <li><a href="activities_p1.php">ACTIVITIES</a></li>
                    <li><a href="#">BOOK NOW</a></li>
            </ul>
            <div class="icon">
                <?php if($current_user): ?>
                    <div class="user-info">
                        <span class="user-name">Hello, <?= htmlspecialchars($current_user["first_name"]) ?></span>
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
    <!-- Sign Up form -->
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="process-signup.php" method="post" id="signup-form" novalidate>
                <h1>Create Account</h1>
                
                <input type="text" id="first_name" name="first_name" placeholder="First Name">
                <div id="first_name-error" class="error-message"></div> <!-- Error message container for Name -->

                <input type="text" id="last_name" name="last_name" placeholder="Last Name">
                <div id="last_name-error" class="error-message"></div> <!-- Error message container for Name -->
                
                <input type="email" id="email" name="email" placeholder="Email" required>
                <div id="email-error" class="error-message"></div> <!-- Error message container for Email -->
                
                <input type="text" id="contact_number" name="contact_number" placeholder="Contact Number" required>
                <div id="contact_number-error" class="error-message"></div> <!-- Error message container for Contact Number -->
                
                <input type="password" id="password" name="password" placeholder="Password" required>
                <div id="password-error" class="error-message"></div> <!-- Error message container for Password -->
                
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                <div id="password_confirmation-error" class="error-message"></div> <!-- Error message container for Password Confirmation -->
                
                <button type="submit">Sign up</button>
            </form>

        </div>

        <!-- Sign In form -->
        <div class="form-container sign-in">
            <form method="post" id="login-form" novalidate>
                <h1>Log In</h1>
                <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Log in</button>
                <?php if ($if_invalid): ?>
                    <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
                <?php endif; ?>
                <p><a href="forgot-password.php">Forgot your password?</a></p>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Log in</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign up</button>
                </div>
            </div>
        </div>
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
    // Wait for the DOM to be fully loaded
    document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("signup-form");

    form.addEventListener("submit", function(event) {
        let errors = {};

        // Validate Name
        const first_name = document.getElementById("first_name").value.trim();
        if (first_name === "") {
            errors.first_name = "First Name is required";
        }
        const last_name = document.getElementById("last_name").value.trim();
        if (last_name === ""){
            errors.last_name = "Last Name is required"
        }

        // Validate Email
        const email = document.getElementById("email").value.trim();
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (email === "" || !emailRegex.test(email)) {
            errors.email = "A valid email is required";
        }

        // Validate Contact Number
        const contactNumber = document.getElementById("contact_number").value.trim();
        const contactNumberRegex = /^\+?[0-9]{10,15}$/; // Allow optional country code and 10-15 digits
        if (contactNumber === "") {
            errors.contact_number = "Contact number is required";
        } else if (!contactNumberRegex.test(contactNumber)) {
            errors.contact_number = "Invalid contact number format";
        }

        // Validate Password
        const password = document.getElementById("password").value.trim();
        if (password === "") {
            errors.password = "Password is required";
        } else if (password.length < 8) {
            errors.password = "Password must be at least 8 characters";
        } else if (!/[a-z]/i.test(password)) {
            errors.password = "Password must contain at least one letter";
        } else if (!/[0-9]/.test(password)) {
            errors.password = "Password must contain at least one number";
        }

        // Validate Password Confirmation
        const passwordConfirmation = document.getElementById("password_confirmation").value.trim();
        if (password !== passwordConfirmation) {
            errors.password_confirmation = "Passwords don't match";
        }

        // If there are any errors, show them and prevent form submission
        if (Object.keys(errors).length > 0) {
            event.preventDefault();  // Prevent form submission

            // Clear previous error messages
            const errorFields = document.querySelectorAll('.error-message');
            errorFields.forEach(field => field.textContent = '');

            // Display new error messages
            for (const [field, message] of Object.entries(errors)) {
                const errorElement = document.getElementById(`${field}-error`);
                if (errorElement) {
                    errorElement.textContent = message;
                }
            }
        }
    });

    // Sign-In Form Validation (using JustValidate)
    const signInValidation = new JustValidate('#login-form'); // Ensure the right form is targeted
    signInValidation
        .addField('input[name="email"]', [
            { rule: 'required', errorMessage: 'Email is required' },
            { rule: 'email', errorMessage: 'Email is not valid' }
        ])
        .addField('input[name="password"]', [
            { rule: 'required', errorMessage: 'Password is required' }
        ])
        .onSuccess((event) => {
            // Prevent form submission and allow JustValidate to handle it
            event.target.submit();  // This ensures the form is submitted if validation passes
        });
});

    </script>
     <!-- Include the main script after the DOM -->
     <script src="js/script.js"></script>
</body>
</html>
