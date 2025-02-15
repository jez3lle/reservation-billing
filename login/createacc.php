<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    
    <title>Create</title>
</head>
<body>
    
    <?php
    
     include("config.php");
     if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $contactnum = $_POST['contactnum'];
        $password = $_POST['password'];


     // verify the email

     $verify_query = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");

     if(mysqli_num_rows($verify_query) !=0 ){
        echo "<div class='message'>
                    <p>This email is already USED.</p>
              </div> <br>";
        echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
     }    
     else{

        mysqli_query($conn, "INSERT INTO users (Email,Firstname,Lastname,Contactnum,Password) VALUES('$email', '$firstname', '$lastname', '$contactnum', '$password')") or die ("Error Occured");
        
        echo "<div class='message'>
                    <p>Account Created.</p>
              </div> <br>";
        echo "<a href='login.php'><button class='btn'>Login Now</button>";

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
        <h2>Create Account</h2>
        <div class="btn-group">
            <a href= "login.php" class="btn">LogIn</a>
            <a  href= "createacc.php"class="btn-active"><u>Create New Account</u></a>
            <a href= "Resetpass.php" class="btn">Reset your password</a>
        </div>

        <form action="" method="post">
            <div class="form-group">
                <br><label for="email">Email Address*</label>
                <input type="text" id="email" name="email" placeholder="Enter your Email Address" autocomplete="off" required>
            </div>

            <div class="form-group">
                <label for="firstname">First Name*</label>
                <input type="text" id="firstname" name="firstname" placeholder="Enter your firstname" autocomplete="off" required>
            </div>

            <div class="form-group">
                <label for="lastname">Surname*</label>
                <input type="text" id="lastname" name="lastname" placeholder="Enter your lastname" autocomplete="off" required>
            </div>

            <div class="form-group">
                <label for="contactnum">Contact Number*</label>
                <input type="text" id="contactnum" name="contactnum" placeholder="Enter contact number" autocomplete="off" required>
            </div>

            <div class="form-group">
                <label for="password">Password*</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="off" required>
            </div>

            <div>
                <input type="submit" class="lbtn" name="submit" value="Sign Up" autocomplete="off" required>
            </div>

            <div class="links">
                <br>
                Have an Account already? <a href="login.php"> Log In</a>
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