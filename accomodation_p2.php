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
    </style>
</head>
<body>
<?php include 'headers/header_p2.php'; ?>

    <main class="content-wrapper" role="main">
        <h1>Our Accommodations</h1>
        <section class="accom-container" aria-label="Accommodation Listings">
            <article class="accom-item" aria-labelledby="up-down-house">
                <div class="accom-image">
                    <img src="images/up_and_down_house_main.jpg" alt="Up and Down House" />
                </div>
                <div class="accom-details">
                    <h2 id="up-down-house" class="accom-title">Up and Down House</h2>
                    <span class="availability">1 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 10-15 PAX</h3>
                    <h4>Daytour: ₱4,000.00<br> Overnight: ₱6,500.00</h4>
                    <a href="accommodation_details.php?id=1" class="accom-button">See Details</a>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Glass Cabin">
                <div class="accom-image">
                    <img src="images/glass_cabin_main.jpg" alt="Glass Cabin" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Glass Cabin</h2>
                    <span class="availability">4 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 2-4 PAX</h3>
                    <h4>Daytour: ₱1,500.00 <br> Overnight: ₱2,000.00</h4>
                    <a href="accommodation_details.php?id=2" class="accom-button">See Details</a>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="concrete-house">
                <div class="accom-image">
                    <img src="images/concrete_house_main.jpg" alt="Concrete House" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Concrete House</h2>
                    <span class="availability">1 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 10-15 PAX</h3>
                    <h4>Daytour: ₱4,000.00 <br> Overnight: ₱6,500.00</h4>
                    <a href="accommodation_details.php?id=3" class="accom-button">See Details</a>
                </div>
            </article>
        </section>

        <section class="accom-container" aria-label="Accommodation Listings">
            <article class="accom-item" aria-labelledby="Concrete Room">
                <div class="accom-image">
                    <img src="images/concrete_room_main.jpg" alt="Concrete Room" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Concrete Room</h2>
                    <span class="availability">4 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 4-6 PAX</h3>
                    <h4>Daytour: ₱2,000.00 <br> Overnight: ₱2,500.00</h4>
                    <a href="accommodation_details.php?id=4" class="accom-button">See Details</a>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="TP Hut">
                <div class="accom-image">
                    <img src="images/tp_hut_main.jpg" alt="TP Hut" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">TP Hut</h2>
                    <span class="availability">2 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 2 PAX</h3>
                    <h4>Daytour: ₱1,500.00 <br> Overnight: ₱1,500.00</h4>
                    <a href="accommodation_details.php?id=5" class="accom-button">See Details</a>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Kubo Room">
                <div class="accom-image">
                    <img src="images/kubo_room_main.jpg" alt="Kubo Room" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Kubo Room</h2>
                    <span class="availability">2 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 2-4 PAX</h3>
                    <h4>Daytour: ₱1,500.00 <br> Overnight: ₱2,000.00</h4>
                    <a href="accommodation_details.php?id=6" class="accom-button">See Details</a>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Group Cabin">
                <div class="accom-image">
                    <img src="images/group_cabin_main.jpg" alt="Group Cabin" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Group Cabin</h2>
                    <span class="availability">1 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 6-12 PAX</h3>
                    <h4>Daytour: ₱3,000.00 <br> Overnight: ₱6,000.00</h4>
                    <a href="accommodation_details.php?id=7" class="accom-button">See Details</a>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Open Cottage">
                <div class="accom-image">
                    <img src="images/open_cottage_main.jpg" alt="Open Kubo" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Open Cottage</h2>
                    <span class="availability">6 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 10-15 PAX</h3>
                    <h4>Daytour: ₱1,500.00 <br> Overnight: ₱2,000.00</h4>
                    <a href="accommodation_details.php?id=8" class="accom-button">See Details</a>
                </div>
            </article>
            <article class="accom-item" aria-labelledby="Canopy Tent">
                <div class="accom-image">
                    <img src="images/canopy_tent_main.jpg" alt="Canopy Tent" />
                </div>
                <div class="accom-details">
                    <h2 id="glass-cabin" class="accom-title">Canopy Tent</h2>
                    <span class="availability">3 AVAILABLE</span>
                    <div class="accom-divider"></div>
                    <h3 class="accom-description">FOR 10 PAX</h3>
                    <h4>Daytour: ₱800.00 <br> Overnight: ₱1,300.00</h4>
                    <a href="accommodation_details.php?id=9" class="accom-button">See Details</a>
                </div>
            </article>
        </section>

        </div>
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