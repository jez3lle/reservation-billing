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
    <link rel="stylesheet" href="mystyle.css">
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
                <li><a href="activities_p1.php">ACTIVITIES</a></li>
                <li><a href="contact_p1.php">CONTACT US</a></li>
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
    
    <main class="content-wrapper">
    <h1>Accommodations in Private</h1>
        <div class="accom-container">
            <div class="accom-item">
                <div class="accom-image">
                    <img src="images/house1.png" alt="House">
                </div>
                <div class="accom-details">
                    <h2 class="accom-title">House 1</h2>
                    <div class="accom-divider"></div>
                    <button class="accom-button">See Details</button>
                </div>
            </div>

            <div class="accom-item">
                <div class="accom-image">
                    <img src="images/IMG_4964.jpg" alt="House 2">
                </div>
                <div class="accom-details">
                    <h2 class="accom-title">House 2</h2>
                    <div class="accom-divider"></div>
             
                    <button class="accom-button">See Details</button>
                </div>
            </div>

            <div class="accom-item">
                <div class="accom-image">
                    <img src="images/bg1.png" alt="Swimming Pools">
                </div>
                <div class="accom-details">
                    <h2 class="accom-title">2 Swimming Pools</h2>
                    <div class="accom-divider"></div>
                    <button class="accom-button">See Details</button>
                </div>
            </div>


            <div class="accom-item">
                <div class="accom-image">
                    <img src="images/IMG_4974.jpg" alt="Kubo">
                </div>
                <div class="accom-details">
                    <h2 class="accom-title">Kubo with 2 Room</h2>
                    <div class="accom-divider"></div>
                    <button class="accom-button">See Details</button>
                </div>
            </div>

            <div class="accom-item">
                <div class="accom-image">
                    <img src="images/pavilion.png" alt="Pavilion">
                </div>
                <div class="accom-details">
                    <h2 class="accom-title">Pavilion</h2>
                    <div class="accom-divider"></div>
                    <button class="accom-button">See Details</button>
                </div>
            </div>

            <div class="accom-item">
                <div class="accom-image">
                    <img src="images/IMG_4954.jpg" alt="Roofdeck">
                </div>
                <div class="accom-details">
                    <h2 class="accom-title">Roofdeck</h2>
                    <div class="accom-divider"></div>
                    <button class="accom-button">See Details</button>
                </div>
            </div>

            <div class="accom-item">
                <div class="accom-image">
                    <img src="images/IMG_4961.jpg" alt="Kitchen">
                </div>
                <div class="accom-details">
                    <h2 class="accom-title">Alfresco Kitchen</h2>
                    <div class="accom-divider"></div>
                    <button class="accom-button">See Details</button>
                </div>
            </div>
                        
            <div class="accom-item">
                <div class="accom-image">
                    <img src="images/IMG_4982.jpg" alt="Pavilion">
                </div>
                <div class="accom-details">
                    <h2 class="accom-title">Karaoke</h2>
                    <div class="accom-divider"></div>
                    <button class="accom-button">See Details</button>
                </div>
            </div>

            <div class="accom-item">
                <div class="accom-image">
                    <img src="images/IMG_4967.jpg" alt="Exclusive">
                </div>
                <div class="accom-details">
                    <h2 class="accom-title">Exclusive</h2>
                    <div class="accom-divider"></div>
                    <button class="accom-button">See Details</button>
                </div>
            </div>


        </div>
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