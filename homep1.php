<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME - Rainbow Forest Paradise Resort and Campsite</title>
    <link rel="stylesheet" href="mystyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        
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
                <button onclick="bookNow('Phase 1')">BOOK NOW</button>
            </div>
            <div class="phase-card phase-public">
                <h2>PHASE 2</h2>
                <h3>PUBLIC</h3>
                <p>
                    Stay in our welcoming accommodations, including rooms, cabins, and houses, ideal for individuals or small groups. 
                    Enjoy thrilling activities such as ziplining, bonfires, and swimming, making your stay an unforgettable adventure!
                </p>
                <button onclick="bookNow('Phase 2')">BOOK NOW</button>
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
                    <li><a href="homep1.html">HOME</a></li>
                    <li><a href="aboutus.html">ABOUT</a></li>
                    <li><a href="accom.html">ACCOMMODATIONS</a></li>
                    <li><a href="#">ACTIVITIES</a></li>
                    <li><a href="#">CONTACT US</a></li>
                    <li><a href="#">BOOK NOW</a></li>
                    <li><a href="#" class="user-icon">
                        <img src="images/logo.png" alt="User Icon">
                    </a></li>
                </ul>
            </div>
        </nav>
        <div class="hero-text">
            <h1><span>Welcome to Phase 1</span><br>Private</h1>
            <p>Book the entire property for your exclusive retreat!</p>
            <p>Experience nature, peace, and luxury.</p>
            <a href="#" class="booknow">BOOK NOW</a> <!-- Book Now Button -->
        </div>
        <!-- Vertical Menu with Images -->
        <div class="menu-img">
            <img src="images/pavilion.png" alt="Image 1" onclick="changeBackground('images/pavilion.png', this)">
            <img src="images/img1.jpg" alt="Image 2" onclick="changeBackground('images/img1.jpg', this)">
            <img src="images/kubo.jpg" alt="Image 3" onclick="changeBackground('images/kubo.jpg', this)">
        </div>
    </header>

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
            <img src="images/resort2.png" alt="" class="image1">
            <img src="images/pavilion.png" alt="" class="image2">
            </div>
        </div>
        <div class="right">
            <div class="heading">
            <h5>Take a break. Exclusive Getaway. Recharge your batteries.</h5>
            <h2>Welcome to Rainbow Forest Paradise Resort and Campsite</h2>
            <p>Lorem ipsum odor amet, consectetuer adipiscing elit. Himenaeos vehicula sem amet primis; efficitur posuere. 
                Ullamcorper faucibus ante turpis semper class quisque; potenti platea. Tristique semper facilisis tortor placerat mi libero. Nibh eleifend suscipit penatibus nulla lacus fames. 
                Sit ultricies euismod tristique habitant morbi; nisl eget luctus eleifend. 
                Rhoncus class sapien sed praesent lorem sollicitudin pharetra cubilia.
                </p>
            <a href="about us.html"><button class="btn1" style="cursor: pointer;">READ MORE</button>
            </a>
            </div>
        </div>
        </div>
    </section>

  <section class="amenities-section" id="amenities">
    <div class="container">
      <h2 class="section-title">Our Amenities</h2>
      <div class="amenities-content">
        <div class="amenities-grid">
          <div class="amenity-item" style="background-image: url('images/bg1.png');">
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
          <div class="amenity-item" style="background-image: url('images/ziplinee.jpg');">
            <div class="amenity-text">
              <h3>Zipline</h3>
            </div>
          </div>
          <div class="amenity-item" style="background-image: url('images/spiderweb.png');">
            <div class="amenity-text">
              <h3>Spider Web</h3>
            </div>
          </div>
          <div class="amenity-item" style="background-image: url('images/kubo.jpg');">
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
            <div class="gallery-item"><img src="images/resort11.png" alt="Image 1"></div>
            <div class="gallery-item"><img src="images/resort3.png" alt="Image 2"></div>
            <div class="gallery-item"><img src="images/kubo.jpg" alt="Image 3"></div>
            <div class="gallery-item"><img src="images/phase1.png" alt="Image 4"></div>
            <div class="gallery-item"><img src="images/pavilion.png" alt="Image 5"></div>
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

                if (!check_in || !check_out) {
                    $("#availabilityResult").html("<p class='error'>Please select both check-in and check-out dates.</p>");
                    return;
                }

                if (check_in >= check_out) {
                    $("#availabilityResult").html("<p class='error'>Check-out date must be after check-in date.</p>");
                    return;
                }

                $("#availabilityResult").html("<p class='loading'>Checking availability...</p>");

                $.ajax({
                    url: "check_availability.php",
                    type: "POST",
                    data: { check_in: check_in, check_out: check_out },
                    success: function(response){
                        $("#availabilityResult").html(response);

                        if(response.includes("Available")) {
                            $("#availabilityResult").append(`
                                <button id="proceedToReservation" data-checkin="${check_in}" data-checkout="${check_out}">Proceed to Reservation</button>
                            `);
                        }
                    }
                });
            });

            $(document).on("click", "#proceedToReservation", function(){
                var check_in = $(this).data("checkin");
                var check_out = $(this).data("checkout");

                $.ajax({
                    url: "check_login.php",
                    type: "GET",
                    success: function(response) {
                        if (response.trim() === "logged_in") {
                            window.location.href = "reservation_form.php";
                        } else {
                            window.location.href = "login.php?check_in=" + encodeURIComponent(check_in) + "&check_out=" + encodeURIComponent(check_out);
                        }
                    },
                    error: function() {
                        alert("Error checking login status. Please try again.");
                    }
                });
            });
        });
    </script>


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
        const images = ['images/pavilion.png', 'images/img1.jpg', 'images/resort2.png'];
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
