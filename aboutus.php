<?php
session_start();
include 'db_connect.php';

$mysqli = require __DIR__ . "/database.php";

// Fetch all About Us content
$result = $mysqli->query("SELECT title, content FROM site_content WHERE section = 'about'");
$about_sections = $result->fetch_all(MYSQLI_ASSOC);

// Function to get logged-in user's name
function getUserStatus($mysqli) { 
    if (isset($_SESSION["user_id"])) {
        $stmt = $mysqli->prepare("SELECT first_name, last_name FROM user WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }
    return null;
}

// Get the current user if logged in
$current_user = getUserStatus($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABOUT US - Rainbow Forest Paradise Resort and Campsite</title>
    <link rel="stylesheet" href="styles/mystyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
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
                <li><a href="activities_p1.html">ACTIVITIES</a></li>
                <li><a href="#">CONTACT US</a></li>
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
                    <a href="index.php" class="user-icon">
                        <img src="images/logo.png" alt="User Icon">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="about-container">
    <div class="about-text">
        <h2>About Us</h2>
        <?php if (!empty($about_sections)): ?>
            <?php foreach ($about_sections as $section): ?>
                <h3><?= htmlspecialchars($section['title']); ?></h3>
                <p><?= nl2br(htmlspecialchars($section['content'])); ?></p>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No content available. Please check back later.</p>
        <?php endif; ?>

    </div>


        <div class="about-image">
            <img src="images/activity.png" alt="Rainbow Forest Paradise Resort">
        </div>
    </div>

    <div class="location-section">
        <h2>Location</h2>
        <p>
            Located in a lush countryside, Rainbow Forest Paradise Resort provides an exclusive and serene escape while remaining easily accessible from nearby cities. Surrounded by lush landscapes, fresh mountain air, and breathtaking panoramic views, our resort is the perfect destination for your next outdoor adventure or weekend getaway.
        </p>
    </div>
    
    <div class="map-container">
        <h2>
            <img src="images/location-icon.png" alt="Location Icon" style="width: 30px; height: 30px; vertical-align: middle; margin-right: 5px;">
            BRGY. CUYAMBAY, TANAY, RIZAL
        </h2>
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.62355114514!2d121.3419326751055!3d14.56350718591845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397952b37b5010f%3A0x52edd4eea37586b8!2sRainbow%20Forest%20Paradise%20Resort%20and%20Campsite!5e0!3m2!1sen!2sph!4v1735636685930!5m2!1sen!2sph" 
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
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
</body>
</html>
