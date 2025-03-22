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
    <title>ACTIVITIES - Rainbow Forest Paradise Resort and Campsite</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        .account-info {
            margin-top: 150px;
            margin-bottom: 150px;
            background-color: #f0f0f0;
        }

        .account-info p{
            line-height: 3;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        
        h1{
            alignment: center;
        }

        .user-info{
            display: flex;
            flex-direction: column;
        }
        .profile-btn{
            color:white;
        }
        .activities-container {
            max-width: 1200px;
            margin: 30px auto;
        }

        .activities-container h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #ffd700;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .activity-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .activity-item:hover {
            transform: translateY(-5px);
        }

        .activity-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .activity-item h2 {
            font-size: 1.8em;
            margin-bottom: 15px;
            color: #ffd700;
        }

        .activity-item p {
            font-size: 1em;
        }

        .activities-note {
            text-align: center;
            margin: 40px 0;
            font-style: italic;
            color: #ccc;
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
                <li><a href="accomodation_p2.html">ACCOMMODATIONS</a></li>
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
                    <a href="index.php" class="user-icon">
                        <img src="images/logo.png" alt="User Icon">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="activities-container">
        <h2>Activities</h2>
        <section class="activity-grid">
            <div class="activity-item">
                <img src="images/zipline2.jpg" alt="Zipline">
                <h2>Zipline Adventure</h2>
                <p>Soar through the forest canopy and experience breathtaking views.</p>
            </div>

            <div class="activity-item">
                <img src="images/IMG_4849.jpg" alt="Spider Web">
                <h2>Spider Web</h2>
                <p>Test your balance and agility on our exciting spider web course.</p>
            </div>

            <div class="activity-item">
                <img src="images/bonfire2.jpg" alt="Bonfire">
                <h2>Bonfire Nights</h2>
                <p>Enjoy warm evenings with a crackling bonfire under the stars.</p>
            </div>
        </section>

        <p class="activities-note">
            Please note: These activities are displayed for informational purposes only and do not require prior reservations. Availability may vary.
        </p>

        <section class="activity-grid">
            
            <div class="activity-item">
                    <img src="images/atv.jpg" alt="ATV">
                    <h2>ATV Trails</h2>
                    <p>Explore the rugged terrain with an adventurous ATV ride.</p>
            </div>

            <div class="activity-item">
                <img src="images/resort2.png" alt="Swimming">
                <h2>Swimming Pools</h2>
                <p>Relax and cool off in our refreshing swimming pools. </p>
            </div>

            <div class="activity-item">
                <img src="images/camping.jpg" alt="Camping">
                <h2>Camping</h2>
                <p>Discover the natural beauty of the area with scenic hiking trails.</p>
            </div>
        </section>
    </main>
    
   
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
               
