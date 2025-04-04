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
    <title>ACCOMMODATIONS - Rainbow Forest Paradise Resort and Campsite</title>
    <link rel="stylesheet" href="styles/mystyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
    <main class="content-wrapper" role="main">
        <h1>Our Accommodations</h1>
        <section class="accom-container" aria-label="Accommodation Listings">
            <article class="accom-item" aria-labelledby="up-down-house">
                <div class="accom-image">
                    <img src="images/IMG_4861.jpg" alt="Up and Down House" />
                </div>
                <div class="accom-details">
                    <h2 id="up-down-house" class="accom-title">Up and Down House</h2>
                    <span class="availability">1 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 10-15 PAX</h3>
                    <h4>Daytour: ₱4,000.00<br> Overnight: ₱6,500.00</h4>
                    <button class="accom-button" aria-label="See details for Up and Down House">See Details</button>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Glass Cabin">
                <div class="accom-image">
                    <img src="images/IMG_4857.jpg" alt="Glass Cabin" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Glass Cabin</h2>
                    <span class="availability">4 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 2-4 PAX</h3>
                    <h4>Daytour: ₱1,500.00 <br> Overnight: ₱2,000.00</h4>
                    <button class="accom-button" aria-label="See details for Glass Cabin">See Details</button>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="concrete-house">
                <div class="accom-image">
                    <img src="images/IMG_4918.jpg" alt="Concrete House" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Concrete House</h2>
                    <span class="availability">1 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 10-15 PAX</h3>
                    <h4>Daytour: ₱4,000.00 <br> Overnight: ₱6,500.00</h4>
                    <button class="accom-button" aria-label="See details for Glass Cabin">See Details</button>
                </div>
            </article>
        </section>

        <section class="accom-container" aria-label="Accommodation Listings">
            <article class="accom-item" aria-labelledby="Concrete Room">
                <div class="accom-image">
                    <img src="images/IMG_4821.jpg" alt="Concrete Room" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Concrete Room</h2>
                    <span class="availability">4 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 4-6 PAX</h3>
                    <h4>Daytour: ₱2,000.00 <br> Overnight: ₱2,500.00</h4>
                    <button class="accom-button" aria-label="See details for Glass Cabin">See Details</button>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="TP Hut">
                <div class="accom-image">
                    <img src="images/IMG_4895.jpg" alt="TP Hut" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">TP Hut</h2>
                    <span class="availability">2 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 2 PAX</h3>
                    <h4>Daytour: ₱1,500.00 <br> Overnight: ₱1,500.00</h4>
                    <button class="accom-button" aria-label="See details for Glass Cabin">See Details</button>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Kubo Room">
                <div class="accom-image">
                    <img src="images/IMG_4859.jpg" alt="Kubo Room" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Kubo Room</h2>
                    <span class="availability">2 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 2-4 PAX</h3>
                    <h4>Daytour: ₱1,500.00 <br> Overnight: ₱2,000.00</h4>
                    <button class="accom-button" aria-label="See details for Glass Cabin">See Details</button>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Group Cabin">
                <div class="accom-image">
                    <img src="images/IMG_4840.jpg" alt="Group Cabin" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Group Cabin</h2>
                    <span class="availability">1 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 6-12 PAX</h3>
                    <h4>Daytour: ₱3,000.00 <br> Overnight: ₱6,000.00</h4>
                    <button class="accom-button" aria-label="See details for Glass Cabin">See Details</button>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Open Cottage">
                <div class="accom-image">
                    <img src="images/img26.jpg" alt="Open Kubo" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Open Cottage</h2>
                    <span class="availability">6 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 10-15 PAX</h3>
                    <h4>Daytour: ₱1,500.00 <br> Overnight: ₱2,000.00</h4>
                    <button class="accom-button" aria-label="See details for Glass Cabin">See Details</button>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Canopy Tent">
                <div class="accom-image">
                    <img src="images/IMG_4919.jpg" alt="Canopy Tent" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Canopy Tent</h2>
                    <span class="availability">3 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 10 PAX</h3>
                    <h4>Daytour: ₱800.00 <br> Overnight: ₱1,300.00</h4>
                    <button class="accom-button" aria-label="See details for Glass Cabin">See Details</button>
                </div>
            </article>
        </section>

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