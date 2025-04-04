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
    <title>ABOUT US - Rainbow Forest Paradise Resort and Campsite</title>
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
<?php include 'headers/header.php'; ?>
    <div class="about-container">
        <div class="about-text">
            <h2>About Us</h2>
            <p>
                Rainbow Forest Paradise Resort and Campsite is a sanctuary for nature lovers, adventure seekers, and those craving a peaceful retreat.
                Experience the warmth of Filipino hospitality as you unwind in a place where tranquility meets excitement.
                Since 2019, we have been a haven for travelers looking to escape the noise of city life and reconnect with nature.
            </p>
        </div>
        <div class="about-image">
            <img src="images/activity.png" alt="Rainbow Forest Paradise Resort">
        </div>
    </div>

    <div class="location-section">
        <h2>Location</h2>
        <p>
        Rainbow Forest Paradise Resort is located just a few hours away from Manila, in the lush countryside, 
        nestled within a tropical forest. <br> It offers a serene retreat where you can unwind and reconnect with nature. 
        Its secluded spot provides a peaceful ambiance, yet it remains easily accessible from nearby cities,
         making it the perfect destination for both relaxation and adventure.
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

    <?php include 'headers/footer.php'; ?>
    
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
