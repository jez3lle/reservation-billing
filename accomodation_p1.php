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
<?php include 'header.php'; ?>
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
                    <li><a href="accomodation_p1.php">Accommodations</a></li>
                    <li><a href="activities_p1.php">Activities</a></li>
                    <li><a href="aboutus_p1.php">About Us</a></li>
                    <li><a href="contact_p1.php">Contact Us</a></li>
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
                    <li><a href="contact_p1.php">Follow Us</a></li>
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