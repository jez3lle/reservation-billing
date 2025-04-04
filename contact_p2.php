<?php
session_start(); // Start the session at the beginning
function getUserStatus() {
    if (isset($_SESSION["user_id"])) {
        $mysqli = require __DIR__ . "/database.php";
        $stmt = $mysqli->prepare("SELECT first_name, last_name FROM user WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        // Return null if user doesn't exist in database (account might have been deleted)
        return $user ?: null;
    }
    return null;
}

// Get the current user if logged in
$current_user = getUserStatus();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTACT US - Rainbow Forest Paradise Resort</title>
    <link rel="stylesheet" href="styles/mystyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        main.content-wrapper {
            width: 90%;
            max-width: 1100px;
            margin: 50px auto;
            padding: 40px; /* Increased padding */
            background: rgba(255, 255, 255, 0.05); /* Slightly darker background */
            border-radius: 15px; /* More rounded corners */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Subtle shadow */
        }

        /* Heading */
        .content-wrapper h1 {
            font-size: 2.8em; /* Larger heading */
            margin-bottom: 30px; /* Increased margin */
            text-align: center;
            color: #ffd700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Text shadow for emphasis */
        }

        /* Contact Items */
        .contact-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Grid layout */
            gap: 30px; /* Increased gap */
            margin-bottom: 30px; /* Added margin */
        }

        .contact-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 30px; /* Increased padding */
            text-align: center;
            transition: transform 0.2s ease-in-out; /* Smooth hover effect */
        }

        .contact-item:hover {
            transform: translateY(-5px); /* Slight lift on hover */
        }

        .contact-item h2 {
            font-size: 1.5em; /* Adjusted heading size */
            margin-bottom: 20px; /* Increased margin */
            color: #ffd700;
        }

        .contact-item p {
            font-size: 1em;
            margin-bottom: 15px;
        }

    .contact-item a {
        color: #ffffff;
        font-weight: bold;
        text-decoration: none;
        position: relative; /* Required for pseudo-element positioning */
    }

    .contact-item a::after {
        content: '';
        position: absolute;
        width: 0; /* Start with zero width */
        height: 2px; /* Underline thickness */
        background: #ffd700;
        bottom: -2px; /* Adjust as needed for underline position */
        left: 0;
        transition: width 0.3s ease-in-out; /* Animate width property */
    }

    .contact-item a:hover::after {
        width: 100%; /* Grow to full width on hover */
    }


        /* Contact Form */
        .contact-form {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 30px; /* Increased padding */
            margin-top: 30px;
        }

        .contact-form h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #ffd700;
            text-align: center;
        }

        .contact-form label {
            display: block;
            text-align: left;
            margin: 15px 0 5px; /* Increased margin */
            font-weight: bold;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 12px; /* Increased padding */
            margin-bottom: 20px; /* Increased margin */
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-family: inherit; /* Inherit font */
        }
        .contact-form input::placeholder,
        .contact-form textarea::placeholder {
            color: #ffffff;
            opacity: 0.7; /* Optional: Slightly reduce opacity for better readability */
        }

        .contact-form textarea {
            resize: vertical;
            min-height: 120px; /* Minimum height for textarea */
        }

        .contact-form button {
            background: #ffd700;
            color: #2d6a4f;
            padding: 12px 25px; /* Adjusted button padding */
            font-size: 1em;
            font-weight: bold;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        .contact-form button:hover {
            background: #ffffff;
            color: #2d6a4f;
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
                <a href="home_p1.php" class="phasebutton">Proceed to Phase 1</a>
            </div>
            <div class="phase-card phase-public">
                <h2>PHASE 2</h2>
                <h3>PUBLIC</h3>
                <p>
                    Stay in our welcoming accommodations, including rooms, cabins, and houses, ideal for individuals or small groups. 
                    Enjoy thrilling activities such as ziplining, bonfires, and swimming, making your stay an unforgettable adventure!
                </p>
                <a href="home_p2.php" class="phasebutton">Proceed to Phase 2</a>
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
                <li><a href="home_p2.php">HOME</a></li>
                <li><a href="aboutus_p2.php">ABOUT</a></li>
                <li><a href="accomodation_p2.php">ACCOMMODATIONS</a></li>
                <li><a href="activities_p2.php">ACTIVITIES</a></li>
                <li><a href="contact_p2.php">CONTACT US</a></li>
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

<?php include 'headers/header_p2.php'; ?>
    <main class="content-wrapper">
        <h1>Contact Us</h1>
        <section class="contact-container">
            <div class="contact-item">
                <h2>Get in Touch</h2>
                <p>Have questions? We're here to help.</p>
                <p><strong>Phone:</strong> <a href="tel:09605877561">0960 587 7561</a></p>
                <p><strong>Email:</strong> <a href="mailto:rainbowforestparadise2020@gmail.com">rainbowforestparadise2020@gmail.com</a></p>
            </div>

            <div class="contact-item">
                <h2>Follow Us</h2>
                <p>Stay updated with our latest news and promotions.</p>
                <p><a href="https://www.facebook.com/profile.php?id=100050508021940" target="_blank">Facebook Page</a></p>
            </div>
        </section>
        <section class="contact-container">
            <div class="contact-item">
                <h2>Visit Us</h2>
                <p>Relax in nature at Rainbow Forest Paradise Resort.</p>
                <p>Brgy. Cuyambay, Tanay, Rizal, Philippines</p>
            </div>
        </section>

        <section class="contact-form">
            <h2>Send Us a Message</h2>
            <form action="#" method="post">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </section>
    </main>

    <?php include 'headers/footer_p2.php'; ?>
    
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

</body>
</html>
