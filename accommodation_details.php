<?php
session_start(); // Start the session at the beginning

function getUserStatus() { // Corrected function name
    // Check if user is logged in
    if (isset($_SESSION["user_id"])) {
        $mysqli = require __DIR__ . "/database.php"; // Connect to the database
        $stmt = $mysqli->prepare("SELECT first_name, last_name FROM user WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]); // Bind user ID
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc(); // Fetch user data
        $stmt->close();
        
        return $user ?: null; // Return user data or null if not found
    }
    return null; // Return null if user is not logged in
}

// Get room ID from URL
$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Connect to database
$mysqli = require __DIR__ . "/database.php";

// Fetch room details
$stmt = $mysqli->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc(); // Fetch room data
$stmt->close();

// If room doesn't exist, redirect back to accommodations page
if (!$room) {
    header("Location: accomodation_p2.php");
    exit;
}

// Get the current user if logged in
$current_user = getUserStatus(); // Corrected function call

// Set default values for image and pricing if not in database
$room_name_slug = strtolower(str_replace(" ", "_", $room['name']));
$main_image_path = "images/{$room_name_slug}_main.jpg";
$view1_image_path = "images/{$room_name_slug}_view1.jpg";
$view2_image_path = "images/{$room_name_slug}_view2.jpg";
$view3_image_path = "images/{$room_name_slug}_view3.jpg";

// Check if main image exists, otherwise use default
if (!file_exists($main_image_path)) {
    $main_image_path = "images/default_room.jpg";
}

// For thumbnail images, check if they exist, otherwise use the main image
$thumb1_image_path = file_exists($view1_image_path) ? $view1_image_path : $main_image_path;
$thumb2_image_path = file_exists($view2_image_path) ? $view2_image_path : $main_image_path;
$thumb3_image_path = file_exists($view3_image_path) ? $view3_image_path : $main_image_path;

// Define pricing based on room name
$pricing = [
    // Pricing details for each room type
    "UP AND DOWN HOUSE" => ["daytour" => 4000, "overnight" => 6500, "quantity" => "10-15"],
    "GLASS CABIN" => ["daytour" => 1500, "overnight" => 2000, "quantity" => "2-4"],
    "CONCRETE HOUSE" => ["daytour" => 4000, "overnight" => 6500, "quantity" => "10-15"],
    "CONCRETE ROOM" => ["daytour" => 2000, "overnight" => 2500, "quantity" => "4-6"],
    "TP HUT" => ["daytour" => 1500, "overnight" => 1500, "quantity" => "2"],
    "KUBO ROOM" => ["daytour" => 1500, "overnight" => 2000, "quantity" => "2-4"],
    "GROUP CABIN" => ["daytour" => 3000, "overnight" => 6000, "quantity" => "6-12"],
    "OPEN COTTAGE" => ["daytour" => 1500, "overnight" => 2000, "quantity" => "10-15"],
    "CANOPY TENT" => ["daytour" => 800, "overnight" => 1300, "quantity" => "10"]
];

// Get pricing for current room
$current_pricing = $pricing[$room['name']] ?? ["daytour" => 0, "overnight" => 0, "quantity" => "Unknown"];

// Fetch reserved dates from the database - FIXED
$reserved_dates = [];
$stmt = $mysqli->prepare("
    SELECT r.check_in, r.check_out 
    FROM p2_guest_reservation r 
    JOIN p2_guest_reservation_room rr ON r.id = rr.reservation_id 
    WHERE rr.room_id = ?
");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    // Add each reserved date range to the reserved_dates array
    $start = new DateTime($row['check_in']);
    $end = new DateTime($row['check_out']);
    $interval = new DateInterval('P1D'); // 1 day interval
    $daterange = new DatePeriod($start, $interval, $end);
    
    foreach ($daterange as $date) {
        $reserved_dates[] = $date->format('Y-m-d');
    }
    // Also add the checkout date
    $reserved_dates[] = $end->format('Y-m-d');
}
$stmt->close();

// Convert reserved dates to JSON for JavaScript
$reserved_dates_json = json_encode($reserved_dates);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($room['name']) ?> - Rainbow Forest Paradise Resort and Campsite</title>
    <link rel="stylesheet" href="mystyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
        /* CSS styles for the page layout and design */
        /* Room Details Page Styling */
        .room-details {
            display: flex;
            flex-direction: column;
            max-width: 1200px;
            margin: 120px auto 50px auto;
            padding: 0;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .back-link {
            padding: 20px;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
        }

        .back-link:hover {
            color: #228B22;
        }

        /* Gallery styling */
        .room-gallery {
            display: flex;
            flex-direction: row;
            gap: 10px;
            padding: 0 20px 20px 20px;
        }

        .main-image {
            flex: 3;
            height: 500px;
            overflow: hidden;
            border-radius: 8px;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-images {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
            height: 500px;
        }

        .thumbnail {
            flex: 1;
            cursor: pointer;
            border-radius: 8px;
            overflow: hidden;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .thumbnail:hover img {
            transform: scale(1.05);
        }

        /* Room info section */
        .room-info {
            display: flex;
            padding: 20px;
            gap: 40px;
        }

        .room-description {
            flex: 3;
            padding-right: 40px;
            color: black;
        }

        .room-description h1 {
            color: #f8d724;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-family: 'Acme', sans-serif;
            text-transform: uppercase;
        }

        .availability {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        /* Amenities section fix */
        .amenities {
            margin-top: 30px;
            margin-bottom: 40px;
            color: black;
        }

        .amenities h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.3rem;
            text-align: center;
        }

        .amenities-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 0 15px;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .amenity-icon {
            font-size: 1.8rem;
            color: #228B22;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f8f0;
            border-radius: 50%;
        }

        /* Media query for smaller screens */
        @media (max-width: 768px) {
            .amenities-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .amenities-list {
                grid-template-columns: 1fr;
            }
        }

        /* Booking section */
        .room-booking {
            flex: 2;
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .room-booking h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.5rem;
        }

        .price-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .price-option h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #333;
        }

        .price-option p {
            margin: 5px 0 0;
            color: #666;
            font-size: 0.9rem;
        }

        .price {
            font-size: 1.4rem;
            font-weight: bold;
            color: #333;
        }

        .date-info {
            margin: 20px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 4px solid #4CAF50;
            color: #333;
        }

        /* Form styling */
        .date-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .date-input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
        }

        .book-now-btn {
            width: 100%;
            padding: 15px;
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .book-now-btn:hover {
            background-color: #ff5252;
        }

        /* Responsive design */
        @media (max-width: 992px) {
            .room-gallery {
                flex-direction: column;
            }
            
            .main-image {
                height: 300px;
            }
            
            .thumbnail-images {
                flex-direction: row;
                height: 100px;
            }
            
            .room-info {
                flex-direction: column;
            }
            
            .room-description {
                padding-right: 0;
            }
        }

        @media (max-width: 768px) {
            .amenities-list {
                grid-template-columns: 1fr;
            }
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
                <!-- Logo -->
                <div class="logo">
                    <img src="images/rainbow-logo.png" alt="Resort Logo">
                    <div class="logo-text">
                        <h1>Rainbow Forest Paradise</h1>
                        <h2>Resort and Campsite</h2>
                    </div>
                </div>

                <ul class="nav-links">
                    <li><a href="home_p2.php">HOME</a></li>
                    <li><a href="aboutus_p2.php">ABOUT</a></li>
                    <li><a href="accomodation_p2.php">ACCOMMODATIONS</a></li>
                    <li><a href="activities_p2.php">ACTIVITIES</a></li>
                    <li><a href="contact_p2.php">CONTACT US</a></li>
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
                        <a href="login.php" class="user-icon">
                            <img src="images/logo.png" alt="User  Icon">
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="content-wrapper" role="main">
            <div class="room-details">
                <a href="accomodation_p2.php" class="back-link">← Back to All Accommodations</a>

                <div class="room-gallery">
                    <div class="main-image">
                        <img src="<?= htmlspecialchars($main_image_path) ?>" alt="<?= htmlspecialchars($room['name']) ?>" id="mainImage">
                    </div>
                    <div class="thumbnail-images">
                        <div class="thumbnail" onclick="changeImage('<?= htmlspecialchars($thumb1_image_path) ?>')">
                            <img src="<?= htmlspecialchars($thumb1_image_path) ?>" alt="Front view">
                        </div>
                        <div class="thumbnail" onclick="changeImage('<?= htmlspecialchars($thumb2_image_path) ?>')">
                            <img src="<?= htmlspecialchars($thumb2_image_path) ?>" alt="Inside view">
                        </div>
                        <div class="thumbnail" onclick="changeImage('<?= htmlspecialchars($thumb3_image_path) ?>')">
                            <img src="<?= htmlspecialchars($thumb3_image_path) ?>" alt="Night view">
                        </div>
                    </div>
                </div>

                <div class="room-info">
                    <div class="room-description">
                        <h1><?= htmlspecialchars($room['name']) ?></h1>
                        <span class="availability"><?= $room['quantity'] ?> AVAILABLE</span>
                        
                        <p>Experience the ultimate comfort and natural beauty in our <?= htmlspecialchars(strtolower($room['name'])) ?>. 
                        Designed to blend seamlessly with the surrounding environment, this accommodation offers a perfect balance of 
                        comfort and nature.</p>
                        
                        <p>Each <?= htmlspecialchars(strtolower($room['name'])) ?> features comfortable bedding, breathtaking views, 
                        and all the essentials you need for a memorable stay at Rainbow Forest Paradise Resort and Campsite.</p>
                        
                        <p>Ideal for <?= $current_pricing['quantity'] ?> guests, this is perfect for 
                        <?= (int)$current_pricing['quantity'] > 4 ? 'families or groups of friends' : 'couples or small families' ?> 
                        looking to escape the city life and reconnect with nature.</p>
                        
                        <div class="amenities">
                            <h3>Amenities</h3>
                            <div class="amenities-list">
                                <?php if($room_id != 8 && $room_id != 9): // Show bedding for all except Open Cottage and Canopy Tent ?>
                                <div class="amenity-item">
                                    <span class="amenity-icon">🛏️</span>
                                    <span>Comfortable bedding</span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($room_id != 2 && $room_id != 5 && $room_id != 6 && $room_id != 8 && $room_id != 9): // Show private bathroom for accommodations with it ?>
                                <div class="amenity-item">
                                    <span class="amenity-icon">🚿</span>
                                    <span>Private bathroom</span>
                                </div>
                                <?php else: ?>
                                <div class="amenity-item">
                                    <span class="amenity-icon">🚿</span>
                                    <span>Access to shared bathrooms</span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="amenity-item">
                                    <span class="amenity-icon">🌅</span>
                                    <span>Scenic views</span>
                                </div>

                                <?php if($room_id != 1 && $room_id != 3 && $room_id != 7): ?>
                                <div class="amenity-item">
                                    <span class="amenity-icon">🌲</span>
                                    <span>Natural surroundings</span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($room_id != 8 && $room_id != 9): // Show power outlets for all except Open Cottage and Canopy Tent ?>
                                <div class="amenity-item">
                                    <span class="amenity-icon">🔌</span>
                                    <span>Power outlets</span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($room_id != 1 && $room_id != 2 && $room_id != 3 && $room_id != 4 && $room_id != 5 && $room_id != 6 && $room_id != 7): ?>
                                <div class="amenity-item">
                                    <span class="amenity-icon">🧹</span>
                                    <span>Daily cleaning</span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($room_id == 1 || $room_id == 3 || $room_id == 7): // Add extra amenities for larger houses ?>
                                <div class="amenity-item">
                                    <span class="amenity-icon">🚰</span>
                                    <span>Kitchen area</span>
                                </div>
                                <div class="amenity-item">
                                    <span class="amenity-icon">🪑</span>
                                    <span>Living space</span>
                                </div>
                                <?php else:?>
                                    <div class="amenity-item">
                                    <span class="amenity-icon">🚰</span>
                                    <span>Shared Kitchen area</span>
                                </div>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="room-booking">
                        <h2>Book Your Stay</h2>
                        
                        <div class="price-option">
                            <div>
                                <h3>Daytour</h3>
                                <p>8:00 AM - 5:00 PM</p>
                            </div>
                            <div class="price">₱<?= number_format($current_pricing['daytour'], 2) ?></div>
                        </div>
                        
                        <div class="price-option">
                            <div>
                                <h3>Overnight</h3>
                                <p>2:00 PM - 12:00 PM (next day)</p>
                            </div>
                            <div class="price">₱<?= number_format($current_pricing['overnight'], 2) ?></div>
                        </div>
                        
                        <!-- Add availability message -->
                        <div class="date-info">
                            <p><strong>Availability:</strong> Please select dates to check availability</p>
                        </div>
                        
                        <form action="booking_form.html" method="post">
                            <input type="hidden" name="room_id" value="<?= $room_id ?>">
                            
                            <div class="date-selector">
                                <input type="date" name="check_in" class="date-input" required min="<?= date('Y-m-d') ?>" id="check_in">
                                <input type="date" name="check_out" class="date-input" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>" id="check_out">
                            </div>
                            
                            <div class="form-group">
                                <label for="stay_type">Tour Type:</label>
                                <select name="stay_type" id="stay_type" required>
                                    <option value="daytour">Daytour</option>
                                    <option value="overnight">Overnight</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="adults">Number of Adults</label>
                                <input type="number" name="adults" id="adults" min="1" max="<?= explode('-', $current_pricing['quantity'])[1] ?? explode('-', $current_pricing['quantity'])[0] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="kids">Number of Kids:</label>
                                <input type="number" name="children" id="children" min="0" max="<?= explode('-', $current_pricing['quantity'])[1] ?? explode('-', $current_pricing['quantity'])[0] ?>" required value="0">
                            </div>
                            
                            <button type="submit" class="book-now-btn">Book Now</button>
                        </form>
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
                        <li><a href="accomodation_p2.php">Accommodations</a></li>
                        <li><a href="activities_p2.php">Activities</a></li>
                        <li><a href="aboutus_p2.php">About Us</a></li>
                        <li><a href="contact_p2.php">Contact Us</a></li>
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

        function changeImage(src) {
            document.getElementById('mainImage').src = src; // Change the main image based on thumbnail click
        }

        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Update price based on stay type selection
            const stayTypeSelect = document.getElementById('stay_type');
            if (stayTypeSelect) {
                stayTypeSelect.addEventListener('change', function() {
                    const stayType = this.value;
                    const daytourPrice = <?= $current_pricing['daytour'] ?>;
                    const overnightPrice = <?= $current_pricing['overnight'] ?>;
                    
                    const priceDisplay = document.querySelector('.selected-price');
                    if (priceDisplay) {
                        priceDisplay.textContent = '₱' + (stayType === 'daytour' ? daytourPrice.toFixed(2) : overnightPrice.toFixed(2));
                    }
                });
            }

            // Get the list of reserved dates
            const reservedDates = <?= $reserved_dates_json ?>;
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            const dateInfo = document.querySelector('.date-info');

            // Function to check if a date is reserved
            function isDateReserved(dateStr) {
                return reservedDates.includes(dateStr);
            }

            // Function to format date for display
            function formatDateForDisplay(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
            }

            // Add event listeners to check-in input
            if (checkInInput) {
                checkInInput.addEventListener('change', function() {
                    const selectedDate = this.value;
                    if (isDateReserved(selectedDate)) {
                        alert('This date is already reserved. Please choose another date.');
                        this.value = ''; // Clear the input if the date is reserved
                        if (dateInfo) {
                            dateInfo.innerHTML = '<p><strong>Note:</strong> The selected date is not available.</p>';
                            dateInfo.style.color = '#cc0000';
                        }
                    } else {
                        if (dateInfo) {
                            dateInfo.innerHTML = '<p><strong>Selected check-in date:</strong> ' + formatDateForDisplay(selectedDate) + ' (Available)</p>';
                            dateInfo.style.color = '#4CAF50';
                        }
                        
                        // Update min date for checkout to be after check-in
                        if (checkOutInput) {
                            const nextDay = new Date(selectedDate);
                            nextDay.setDate(nextDay.getDate() + 1);
                            checkOutInput.min = nextDay.toISOString().split('T')[0];
                            
                            // If checkout date is before the new check-in date, clear it
                            if (checkOutInput.value && checkOutInput.value < checkOutInput.min) {
                                checkOutInput.value = '';
                            }
                        }
                    }
                });
            }

            // Add event listeners to check-out input
            if (checkOutInput && dateInfo) {
                checkOutInput.addEventListener('change', function() {
                    const selectedDate = this.value;
                    if (isDateReserved(selectedDate)) {
                        alert('This date is already reserved. Please choose another date.');
                        this.value = ''; // Clear the input if the date is reserved
                    } else if (checkInInput && checkInInput.value) {
                        dateInfo.innerHTML = '<p><strong>Selected dates:</strong> ' + 
                            formatDateForDisplay(checkInInput.value) + ' to ' + 
                            formatDateForDisplay(selectedDate) + ' (Available)</p>';
                        dateInfo.style.color = '#4CAF50';
                    }
                });
            }

            // Function to disable reserved dates in the date picker
            function disableReservedDates() {
                // This requires a more advanced datepicker library like flatpickr or jQuery UI
                // For HTML5 input[type=date], we can only check after user selection
                console.log("Reserved dates:", reservedDates);
            }

            // Run on page load
            disableReservedDates();

            // Get the book now button
            const bookNowBtn = document.querySelector('.book-now-btn');
            
            // Check if the button exists
            if (bookNowBtn) {
                console.log("Book now button found");
                // Get the form (either the button is inside a form or we find a related form)
                const bookingForm = bookNowBtn.closest('form') || document.getElementById('booking_form');
                
                if (bookingForm) {
                    console.log("Booking form found");
                    bookingForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        console.log('Form submission intercepted'); // Debug message
                        
                        // Get form data - add null checks
                        const checkInDate = document.getElementById('check_in')?.value || '';
                        const checkOutDate = document.getElementById('check_out')?.value || '';
                        const stayType = document.getElementById('stay_type')?.value || 'overnight';
                        const adults = document.getElementById('adults')?.value || '1';
                        const children = document.getElementById('children')?.value || '0';
                        const roomId = this.querySelector('input[name="room_id"]')?.value || '';
                        
                        // Debug data
                        console.log('Form data:', {checkInDate, checkOutDate, stayType, adults, children, roomId});
                        
                        // Validate the form
                        if (!checkInDate || !checkOutDate || !adults) {
                            alert('Please fill in all required fields.');
                            return;
                        }
                        
                        try {
                            // Store data in sessionStorage
                            sessionStorage.setItem('bookingData', JSON.stringify({
                                checkInDate: checkInDate,
                                checkOutDate: checkOutDate,
                                stayType: stayType,
                                adults: adults,
                                children: children,
                                roomId: roomId,
                                roomName: "<?= htmlspecialchars($room['name'] ?? '') ?>"
                            }));
                            console.log('Data stored in sessionStorage');
                            
                            // Redirect to booking form
                            window.location.href = 'booking_form.html';
                        } catch (error) {
                            console.error('Error storing booking data:', error);
                            alert('There was an error processing your booking. Please try again.');
                        }
                    });
                } else {
                    console.error('Booking form not found');
                }
                
                // Add direct click handler to the button as a backup
                bookNowBtn.addEventListener('click', function(e) {
                    console.log('Book now button clicked directly');
                    
                    // Check if button is inside a form to avoid double submission
                    if (!this.closest('form')) {
                        // Get form data
                        const checkInDate = document.getElementById('check_in')?.value || '';
                        const checkOutDate = document.getElementById('check_out')?.value || '';
                        const stayType = document.getElementById('stay_type')?.value || 'overnight';
                        const adults = document.getElementById('adults')?.value || '1';
                        const children = document.getElementById('children')?.value || '0';
                        const roomId = document.querySelector('input[name="room_id"]')?.value || '';
                        
                        // Validate the form
                        if (!checkInDate || !checkOutDate || !adults) {
                            alert('Please fill in all required fields.');
                            return;
                        }
                        
                        try {
                            // Store data in sessionStorage
                            sessionStorage.setItem('bookingData', JSON.stringify({
                                checkInDate: checkInDate,
                                checkOutDate: checkOutDate,
                                stayType: stayType,
                                adults: adults,
                                children: children,
                                roomId: roomId,
                                roomName: "<?= htmlspecialchars($room['name'] ?? '') ?>"
                            }));
                            
                            // Redirect to booking form
                            window.location.href = 'booking_form.html';
                        } catch (error) {
                            console.error('Error storing booking data:', error);
                            alert('There was an error processing your booking. Please try again.');
                        }
                    }
                });
            } else {
                console.error('Book now button not found');
            }
        });
    </script>
    </body>
    </html>