<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    
    <title>login</title>
</head>
<body>

    <?php
    
      include("config.php");
      if(isset($_POST['submit'])){
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $password = mysqli_real_escape_string($conn,$_POST['password']);

        $result = mysqli_query($conn,"SELECT * FROM users WHERE Email='$email' AND Password='$password'") or die("Error");
        $row = mysqli_fetch_assoc($result);

        if(is_array($row) && !empty($row)){
            $_SESSION['valid'] = $row['Email'];
            $_SESSION['firstname'] = $row['Firstname'];
            $_SESSION['lastname'] = $row['Lastname'];
            $_SESSION['contactnum'] = $row['Contactnum'];
            $_SESSION['id'] = $row['id'];
        }else{
            echo "<div class='message'>
                <p>Wrong Email or Password</p>
                </div> <br>";
            echo "<a href='login.php'><button class='btn'>Go Back</button>"; 
        }
        if(isset($_SESSION['valid'])){
            header("Location: account.php");
            exit;
        }
      }else{

    ?>
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
    <div class="login-container">
        <h2>Log-In</h2>
        <div class="btn-group">
            <a href= "login.php" class="btn-active"><u>LogIn</u></a>
            <a  href= "createacc.php"class="btn">Create New Account</a>
            <a href= "Resetpass.php" class="btn">Reset your password</a>
        </div>

        <form action="" method="post">
            <div class="form-group">
                <br><label for="email">Email Address*</label>
                <input type="text" name="email" id="email" autocomplete="off" required>
            </div>

            <div class="form-group">
                <label for="password">Password*</label>
                <input type="password" name="password" id="password" autocomplete="off" required>
            </div>

            <div>
                <input type="submit" class="lbtn" name="submit" value="Login" required>
            </div>
            
            <div class="links">
                <br>
                Don't have account? <a href="createacc.php"> Sign Up now</a>
            </div>
        </form>
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
            <?php } ?>
        </div>
</body>
</html>