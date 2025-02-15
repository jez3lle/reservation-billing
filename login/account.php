<?php
    session_start();

    include("config.php");
    if(!isset($_SESSION['valid'])){
        header("Location: login.php");
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="accdesign.css">
    
    <title>USER</title>
</head>
<body>
    <header>
        <div class="nav-links">
            <a href="landing.html" aria-label="User"  class="active"><img src="login-active.png" alt="User/Guest"></a>
            <a href="home-phase1.html">Home</a>
            <a href="accommodation-p1.html">Accommodations</a>
            <a href="#">Activities</a>
        </div>
        <div class="logo-container">
            <a href="#"><img src="rainbow-logo.png" alt="Rainbow Forest Logo"></a>
        </div>
        <div class="nav-links">
            <a href="aboutus.html">About Us</a>
            <a href="#">Contact Us</a>
            <a href="#" class="book-now">Book Now</a>
        </div>
    </header>
            <?php
            
            $id = $_SESSION['id'];
            $query = mysqli_query($conn, "SELECT*FROM users WHERE id = $id");

            while($result = mysqli_fetch_assoc($query)){
                $res_Email = $result['Email'];
                $res_Lname = $result['Lastname'];
                $res_Fname = $result['Firstname'];
                $res_Contactnum = $result['Contactnum'];
                $res_id = $result['id'];
            }

            echo "<a href='edit.php?id=$res_id'>Change Profile</a>";
            ?> 
    <div class="profile-card">
        <h2>Profile</h2>
        <div class="Name">Ike Diezel Samosa</div>
        <br>
        <div class="Cumber">09451234123</div>
        <br>
        <a href="logout.php"><button class="button">Log Out</button></a>
    </div>
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img src="rainbow-logo.png" alt="Rainbow Forest Logo">
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
</body>
</html>