<?php
session_start(); // Start the session at the beginning

if (isset($_GET['check_in']) && isset($_GET['check_out'])) {
    // Validate dates before storing in session
    $check_in = date('Y-m-d', strtotime($_GET['check_in']));
    $check_out = date('Y-m-d', strtotime($_GET['check_out']));
    
    if ($check_in && $check_out && $check_in < $check_out) {
        $_SESSION['check_in'] = $check_in;
        $_SESSION['check_out'] = $check_out;
    }
}

function getUserStatus() {
    if (isset($_SESSION["user_id"])) {
        $mysqli = require __DIR__ . "/database.php";
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

$current_user = getUserStatus();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME - Rainbow Forest Paradise Resort and Campsite</title>
    <link rel="stylesheet" href="styles/mystyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        .hero {
            position: relative;
            width: 100%;
            height: 100vh;
            background-image: url('images/img27.jpg');
            background-size: cover;
            background-position: center;
            transition: background-image 1s ease-in-out; 
        }
        #bookingForm {
            margin: 20px auto 20px auto; /* Top margin, auto center, bottom margin */
            padding:25px 40px;
            border-radius: 10px;
            background-color: white;
            width: 100%;
            max-width: 800px;
            border-left: 10px solid #afd757;
            color: #000000;
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            color: #000000;
            display: block;
        }
    
        .form-row {
            display: flex;
            margin: 0;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-left: -10px;
        }
    
        input[type="date"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #03624c;
            border-radius: 5px;
            outline: none;
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
            color: #03624c;
            font-weight: bold;
        }
    
        input[type="date"]::placeholder {
            color: #03624c;
        }
    
        #checkAvailability {
            background-color: #4caf50;
            color: #FFFAEC;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }
    
        #checkAvailability:hover {
            background-color: #FBFFE4;
            color: #1B4D3E;
        }
    
    
        .message-box {
            color: #03624c;
            border-radius: 5px;
            margin-top: 20px;
        }
        .loading {
            color: blue;
        }
        .error {
            color: #03624c;
        }
        #proceedToReservation {
            padding: 10px 18px;
            background: green;
            margin-top: 15px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
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

        .accom-divider {
            width: 120px;
            height: 4px;
            background-color:#508E87;
            margin: 10px 0;
            border-radius: 10px;
        }
        .indent {
        margin-left: 2em;
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
    <header class="hero">
        <div class="overlay"></div>
        <!-- Navbar -->
        <nav class="home-navbar">
            <div class="logo">
                <img src="images/rainbow-logo.png" alt="Logo">
                <div>
                    <h1>Rainbow Forest Paradise</h1>
                    <h2>Resort and Campsite</h2>
                </div>
            </div>
            <div class="nav-right">
                <ul id="menu-img" class="home-nav-links">
                    <li><a href="home_p2.php">HOME</a></li>
                    <li><a href="aboutus_p2.php">ABOUT</a></li>
                    <li><a href="accomodation_p2.php">ACCOMMODATIONS</a></li>
                    <li><a href="activities_p2.php">ACTIVITIES</a></li>
                    <li><a href="contact_p2.php">CONTACT US</a></li>
                    <li><a href="#">BOOK NOW</a></li>
                </ul>
            </div>
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
                    <a href="login.php" class="user-icon">
                        <img src="images/logo.png" alt="User Icon">
                    </a>
                <?php endif; ?>
            </div>
        </nav>
        <div class="hero-text">
            <h1><span>Welcome to Phase 2</span><br>Public</h1>
            <p>Stay Your Way – Choose a Room, Enjoy the Adventure!</p>
            <p>Nature, Comfort, and Activities – Your Ideal Escape!</p>
            <a href="#" class="booknow">BOOK NOW</a> 
        </div>
        <div class="menu-img">
            <img src="images/img27.jpg" alt="Image 1" onclick="changeBackground('images/img27.jpg', this)">
            <img src="images/img28.jpg" alt="Image 2" onclick="changeBackground('images/img28.jpg', this)">
            <img src="images/img25.jpg" alt="Image 3" onclick="changeBackground('images/img25.jpg', this)">
        </div>
    </header>

<?php include 'headers/homeheader.php'; ?>
    <form id="bookingForm">
        <div class="form-row">
            <label for="check_in">Check-in:</label>
            <input type="date" id="check_in" name="check_in" required>

            <label for="check_out">Check-out:</label>
            <input type="date" id="check_out" name="check_out" required>

            <button type="button" id="checkAvailability">Check Availability</button>
        </div>
        <div id="availabilityResult" class="message-box"></div>
    </form>

    <section class="abouthome" id="abouthome">
        <div class="containerflex">
        <div class="left">
            <div class="img">
            <img src="images/IMG_4859.jpg" alt="" class="image1">
            <img src="images/img23.jpg" alt="" class="image2">
            </div>
        </div>
        <div class="right">
            <div class="heading">
                <h5>Take a break. Exclusive Getaway. Reconnect with nature.</h5>
                <div class="accom-divider"></div>
                <h2>Welcome to Rainbow Forest Paradise Resort and Campsite</h2>
                    <p class="indent">Looking for an affordable yet clean and relaxing resort or campsite just a few hours away from Manila?
                    Rainbow Forest Paradise is the perfect destination for those seeking a peaceful retreat surrounded by nature. Whether you're planning a private getaway with family and friends or a fun-filled adventure in the great outdoors, our resort offers the ideal balance of comfort, relaxation, and excitement.              
                    </p>
                    <p class="indent">At Rainbow Forest Paradise Resort and Campsite, we take pride in providing a well-maintained and tranquil environment, 
                    ensuring a stress-free stay for all our guests. Our friendly and accommodating staff are always ready to assist, making sure your experience is nothing short of exceptional. From cozy accommodations to refreshing pools and open-air pavilions, every corner of our resort is designed to offer relaxation and enjoyment.
                    </p>
                <a href="aboutus_p1.php"><button class="btn1" style="cursor: pointer;">READ MORE</button></a>
            </div>
        </div>
        </div>
    </section>

  <section class="amenities-section" id="amenities">
    <div class="container">
      <h2 class="section-title">Our Activities</h2>
      <div class="amenities-content">
        <div class="amenities-grid">
          <div class="amenity-item" style="background-image: url('images/img2.jpg');">
            <div class="amenity-text">
              <h3>Swimming Pool</h3>
            </div>
          </div>
          <div class="amenity-item" style="background-image: url('images/camping.jpg');">
            <div class="amenity-text">
              <h3>Camping</h3>
            </div>
          </div>
          <div class="amenity-item" style="background-image: url('images/bonfire.jpg');">
            <div class="amenity-text">
              <h3>Bonfire</h3>
            </div>
          </div>
          <div class="amenity-item" style="background-image: url('images/zipline.jpg');">
            <div class="amenity-text">
              <h3>Zipline</h3>
            </div>
          </div>
          <div class="amenity-item" style="background-image: url('images/IMG_4850.jpg');">
            <div class="amenity-text">
              <h3>Spider Web</h3>
            </div>
          </div>
          <div class="amenity-item" style="background-image: url('images/IMG_4890.jpg');">
            <div class="amenity-text">
              <h3>Kubo/Cottages</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="empty-space"></div>
    </div>
  </section>
  
    <section class="photo-gallery">
        <h2>Welcome to Our Photo Gallery</h2>
        <div class="gallery-container" id="gallery">
            <div class="gallery-item"><img src="images/img28.jpg" alt="Image 1"></div>
            <div class="gallery-item"><img src="images/img27.jpg" alt="Image 2"></div>
            <div class="gallery-item"><img src="images/IMG_4828.jpg" alt="Image 3"></div>
            <div class="gallery-item"><img src="images/IMG_4922.jpg" alt="Image 4"></div>
            <div class="gallery-item"><img src="images/IMG_4896.jpg" alt="Image 5"></div>
        </div>
        <div class="gallery-navigation">
            <button onclick="nextImages()">See More</button>
        </div>
    </section>
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
        $(document).ready(function(){
            var today = new Date().toISOString().split('T')[0];
            $("#check_in, #check_out").attr("min", today);
            $("#checkAvailability").click(function(){
                var check_in = $("#check_in").val();
                var check_out = $("#check_out").val();
                var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format

                if (!check_in || !check_out) {
                    $("#availabilityResult").html("<p style='color: red;'>Please select both check-in and check-out dates.</p>");
                    return;
                }
                
                // Check if check-in date is today
                if (check_in === today) {
                    $("#availabilityResult").html("<p style='color: red;'>Same-day check-in is not allowed. Please select a future date.</p>");
                    return;
                }

                if (check_in >= check_out) {
                    $("#availabilityResult").html("<p style='color: red;'>Check-out date must be after check-in date.</p>");
                    return;
                }

                $("#availabilityResult").html("<p class='loading'>Checking availability...</p>");

                $.ajax({
                    url: "check_availability.php",
                    type: "POST",
                    data: { check_in: check_in, check_out: check_out },
                    success: function(response){
                        $("#availabilityResult").html(response);

                        // Remove any existing buttons first
                        $(".reservation-buttons, #proceedToReservation").parent("div").remove();
                        
                        // Only show reservation buttons if the property is available
                        // Look for "Available!" which only appears in the success message
                        if(response.includes("Available!")) {
                            // Store dates in sessionStorage
                            sessionStorage.setItem('check_in', check_in);
                            sessionStorage.setItem('check_out', check_out);
                            
                            // Check if user is logged in
                            $.ajax({
                                url: "check_login.php",
                                type: "GET",
                                success: function(loginResponse) {
                                    if (loginResponse.trim() === "logged_in") {
                                        // User is logged in, show normal button as direct link
                                        $("#availabilityResult").append(`
                                            <div style="display: flex; justify-content: center; margin-top: 15px;">
                                                <a href="reservation_form.php?check_in=${encodeURIComponent(check_in)}&check_out=${encodeURIComponent(check_out)}" class="btn" id="proceedToReservation" style="padding: 10px 18px; background: green; color: white; text-decoration: none; border-radius: 5px; cursor: pointer;">Proceed to Reservation</a>
                                            </div>
                                        `);
                                    } else {
                                        // User is not logged in, show both options as direct links
                                        $("#availabilityResult").append(`
                                            <div class="reservation-buttons" style="display: flex; justify-content: center; gap: 10px; margin-top: 15px;">
                                                <a href="guest_reservation.php?check_in=${encodeURIComponent(check_in)}&check_out=${encodeURIComponent(check_out)}" class="btn" id="guestReservation" style="padding: 10px 18px; background: #4caf50; color: white; text-decoration: none; border-radius: 5px; cursor: pointer;">Continue as Guest</a>
                                                <a href="login.php?check_in=${encodeURIComponent(check_in)}&check_out=${encodeURIComponent(check_out)}" class="btn" id="loginToReserve" style="padding: 10px 18px; background: #03624c; color: white; text-decoration: none; border-radius: 5px; cursor: pointer;">Login to Reserve</a>
                                            </div>
                                        `);
                                    }
                                }
                            });
                        }
                    }
                });
            });
            // Handle click for logged-in users
            $(document).on("click", "#proceedToReservation", function(){
                var check_in = $(this).data("checkin");
                var check_out = $(this).data("checkout");
                window.location.href = "reservation_form.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
            });

            // Handle click for guest users
            $(document).on("click", "#guestReservation", function(){
                var check_in = $(this).data("checkin");
                var check_out = $(this).data("checkout");
                window.location.href = "guest_reservation.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
            });

            // Handle click for users who want to login first
            $(document).on("click", "#loginToReserve", function(){
                var check_in = $(this).data("checkin");
                var check_out = $(this).data("checkout");
                window.location.href = "login.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
            });
        });
        function proceedToReservationFunc(check_in, check_out) {
        window.location.href = "reservation_form.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
        }
    </script> <!-- Add this closing tag -->

    <script>
        function img(anything) {
        document.querySelector('.slide').src = anything;
        }

        function change(change) {
        const line = document.querySelector('.image');
        line.style.background = change;
        }
    </script>

    <script>
    function toggleMenu() {
        const menu = document.querySelector('.menu');
        const hamburger = document.querySelector('.hamburger');
        const hamburgerVertical = document.querySelector('.hamburger-vertical');
        menu.classList.toggle('active');
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

    <script>
    // Function to change the background image of the hero section
        function changeBackground(image, imgElement) {
            document.querySelector('.hero').style.backgroundImage = `url('${image}')`;
            // Add border to clicked image
            const images = document.querySelectorAll('.menu img');
            images.forEach(img => img.classList.remove('clicked'));
            imgElement.classList.add('clicked');
        }

        // Automatic hero image transition every 3 seconds
        const images = ['images/img27.jpg', 'images/img25.jpg', 'images/img28.jpg'];
        let currentImage = 0;
        setInterval(() => {
            currentImage = (currentImage + 1) % images.length;
            changeBackground(images[currentImage], document.querySelectorAll('.menu img')[currentImage]);
        }, 3000);
    </script>

    <script>
        const image = [
            "resort11.png", "resort3.png", "kubo.jpg", "phase1.png", "phase2.png",
            "resort6.png", "resort7.png", "resort8.png", "resort9.png", "resort10.png"
        ];
        let index = 0;
        const gallery = document.getElementById("gallery");

        function nextImages() {
            index = (index + 5) % images.length;
            gallery.innerHTML = "";
            for (let i = 0; i < 5; i++) {
                let img = document.createElement("img");
                img.src = images[(index + i) % images.length];
                img.alt = `Image ${index + i + 1}`;
                let div = document.createElement("div");
                div.classList.add("gallery-item");
                div.appendChild(img);
                gallery.appendChild(div);
            }
        }
    </script>

</body>
</html>
