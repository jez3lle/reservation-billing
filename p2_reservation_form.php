<?php
session_start();

function getUserStatus() {
    if (isset($_SESSION["user_id"])) {
        $mysqli = require __DIR__ . "/database.php";
        $stmt = $mysqli->prepare("SELECT id, first_name, last_name, email, phone FROM user WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        return $user ?: null;
    }
    return null;
}

// Get current user
$current_user = getUserStatus();

// Connect to database
$mysqli = require __DIR__ . "/database.php";

// Process form data from previous page
$room_id = isset($_POST['room_id']) ? (int)$_POST['room_id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
$stay_type = isset($_POST['stay_type']) ? $_POST['stay_type'] : 'daytour';
$check_in = isset($_POST['check_in']) ? $_POST['check_in'] : '';
$check_out = isset($_POST['check_out']) ? $_POST['check_out'] : '';
$adults = isset($_POST['adults']) ? (int)$_POST['adults'] : 1;
$kids = isset($_POST['kids']) ? (int)$_POST['kids'] : 0;
$total_guests = $adults + $kids;

// If no room_id, redirect to accommodations
if (!$room_id) {
    header("Location: accomodation_p2.php");
    exit;
}

// Fetch room details
$stmt = $mysqli->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
$stmt->close();

// If room doesn't exist, redirect back to accommodations page
if (!$room) {
    header("Location: accomodation_p2.php");
    exit;
}

// Define pricing based on room name
$pricing = [
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

// Calculate price
$base_price = $stay_type === 'daytour' ? $current_pricing['daytour'] : $current_pricing['overnight'];
$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$duration = $check_in_date->diff($check_out_date)->days;
if ($duration < 1) $duration = 1;
$total_price = $base_price * $duration;

// Process form submission
$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complete_reservation'])) {
    // Validate primary guest info
    $primary_name = filter_input(INPUT_POST, 'primary_name', FILTER_SANITIZE_STRING);
    $primary_email = filter_input(INPUT_POST, 'primary_email', FILTER_SANITIZE_EMAIL);
    $primary_phone = filter_input(INPUT_POST, 'primary_phone', FILTER_SANITIZE_STRING);
    
    if (empty($primary_name)) {
        $errors[] = "Please enter your name.";
    }
    
    if (!filter_var($primary_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    if (empty($primary_phone) || !preg_match("/^[0-9+\s()-]{10,15}$/", $primary_phone)) {
        $errors[] = "Please enter a valid phone number.";
    }
    
    // Validate additional guests if any
    $guest_names = $_POST['guest_name'] ?? [];
    $guest_ages = $_POST['guest_age'] ?? [];
    
    // Check if we have the correct number of additional guests
    if (count($guest_names) != $total_guests - 1) {
        $errors[] = "Please provide information for all additional guests.";
    }
    
    for ($i = 0; $i < count($guest_names); $i++) {
        if (empty($guest_names[$i])) {
            $errors[] = "Please enter a name for guest " . ($i + 1) . ".";
        }
        
        if (!isset($guest_ages[$i]) || !is_numeric($guest_ages[$i]) || $guest_ages[$i] < 1) {
            $errors[] = "Please enter a valid age for guest " . ($i + 1) . ".";
        }
    }
    
    // Validate payment method
    $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
    if (empty($payment_method)) {
        $errors[] = "Please select a payment method.";
    }
    
    // Process if no errors
    if (empty($errors)) {
        // Here you would normally:
        // 1. Save reservation to database
        // 2. Process payment
        // 3. Send confirmation email
        
        // For demo purposes, just set success
        $success = true;
        
        // In a real application, redirect to confirmation page
        // header("Location: reservation_confirmation.php?id=123");
        // exit;
    }
}

// Maximum guests allowed
$max_guests_parts = explode('-', $current_pricing['quantity']);
$max_guests = isset($max_guests_parts[1]) ? $max_guests_parts[1] : $max_guests_parts[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Reservation - Rainbow Forest Paradise Resort</title>
    <link rel="stylesheet" href="mystyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        .reservation-container {
            max-width: 1000px;
            margin: 120px auto 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .booking-summary {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .room-image {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            margin-right: 20px;
        }
        
        .room-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .booking-details h2 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .booking-info {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 5px;
            color: #666;
        }
        
        .booking-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section h3 {
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 8px;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
            min-width: 250px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
        
        .guest-container {
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        
        .price-summary {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .price-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 18px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .submit-btn {
            background-color: #FF6B6B;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        
        .submit-btn:hover {
            background-color: #FF5252;
        }
        
        .error-message {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            padding: 10px 15px;
            margin-bottom: 20px;
            color: #d32f2f;
        }
        
        .success-message {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 10px 15px;
            margin-bottom: 20px;
            color: #2e7d32;
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-group {
                min-width: 100%;
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
                        <img src="images/logo.png" alt="User Icon">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="content-wrapper" role="main">
        <div class="reservation-container">
            <h1>Complete Your Reservation</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <strong>Please correct the following errors:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <strong>Reservation Successful!</strong>
                    <p>Your booking has been confirmed. A confirmation email has been sent to your email address.</p>
                </div>
            <?php endif; ?>
            
            <div class="booking-summary">
                <div class="room-image">
                    <?php
                    $room_name_slug = strtolower(str_replace(" ", "_", $room['name']));
                    $main_image_path = "images/{$room_name_slug}_main.jpg";
                    if (!file_exists($main_image_path)) {
                        $main_image_path = "images/default_room.jpg";
                    }
                    ?>
                    <img src="<?= htmlspecialchars($main_image_path) ?>" alt="<?= htmlspecialchars($room['name']) ?>">
                </div>
                <div class="booking-details">
                    <h2><?= htmlspecialchars($room['name']) ?></h2>
                    <div class="booking-info">
                        <span>📅 <?= htmlspecialchars(date('F j, Y', strtotime($check_in))) ?> to <?= htmlspecialchars(date('F j, Y', strtotime($check_out))) ?></span>
                        <span>👥 <?= $total_guests ?> guests (<?= $adults ?> adults, <?= $kids ?> children)</span>
                        <span>🕒 <?= $stay_type === 'daytour' ? 'Day Tour' : 'Overnight Stay' ?></span>
                    </div>
                </div>
            </div>
            
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?id=<?= $room_id ?>" method="post" id="reservationForm">
                <input type="hidden" name="room_id" value="<?= $room_id ?>">
                <input type="hidden" name="stay_type" value="<?= $stay_type ?>">
                <input type="hidden" name="check_in" value="<?= $check_in ?>">
                <input type="hidden" name="check_out" value="<?= $check_out ?>">
                <input type="hidden" name="adults" value="<?= $adults ?>">
                <input type="hidden" name="kids" value="<?= $kids ?>">
                <input type="hidden" name="total_guests" value="<?= $total_guests ?>">
                
                <div class="form-section">
                    <h3>Primary Guest Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="primary_name">Full Name:</label>
                            <input type="text" name="primary_name" id="primary_name" class="form-control" required
                                   value="<?= $current_user ? htmlspecialchars($current_user['first_name'] . ' ' . $current_user['last_name']) : '' ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="primary_email">Email Address:</label>
                            <input type="email" name="primary_email" id="primary_email" class="form-control" required
                                   value="<?= $current_user ? htmlspecialchars($current_user['email']) : '' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="primary_phone">Phone Number:</label>
                            <input type="tel" name="primary_phone" id="primary_phone" class="form-control" required
                                   value="<?= $current_user && isset($current_user['phone']) ? htmlspecialchars($current_user['phone']) : '' ?>">
                        </div>
                    </div>
                </div>
                
                <?php if ($total_guests > 1): ?>
                <div class="form-section">
                    <h3>Additional Guests</h3>
                    <p>Please provide details for all additional guests who will be staying.</p>
                    
                    <div id="guestsContainer">
                        <?php for ($i = 0; $i < $total_guests - 1; $i++): ?>
                        <div class="guest-container">
                            <h4>Guest <?= $i + 2 ?></h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="guest_name_<?= $i ?>">Full Name:</label>
                                    <input type="text" name="guest_name[]" id="guest_name_<?= $i ?>" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="guest_age_<?= $i ?>">Age:</label>
                                    <input type="number" name="guest_age[]" id="guest_age_<?= $i ?>" class="form-control" min="1" max="120" required>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="form-section">
                    <h3>Special Requests</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="special_requests">Any special requests or requirements?</label>
                            <textarea name="special_requests" id="special_requests" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Price Summary</h3>
                    <div class="price-summary">
                        <div class="price-row">
                            <span><?= $stay_type === 'daytour' ? 'Day Tour' : 'Overnight' ?> Rate:</span>
                            <span>₱<?= number_format($base_price, 2) ?></span>
                        </div>
                        <div class="price-row">
                            <span>Duration:</span>
                            <span><?= $duration ?> <?= $stay_type === 'daytour' ? 'day(s)' : 'night(s)' ?></span>
                        </div>
                        <div class="price-total">
                            <span>Total:</span>
                            <span>₱<?= number_format($total_price, 2) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Payment Information</h3>
                    <p>To confirm your reservation, a 50% deposit is required. The remaining balance can be paid upon arrival.</p>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="payment_method">Payment Method:</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">Select payment method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="gcash">GCash</option>
                                <option value="paymaya">PayMaya</option>
                                <option value="credit_card">Credit Card</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the Terms and Conditions, including the cancellation policy.</label>
                    </div>
                </div>
                
                <button type="submit" name="complete_reservation" class="submit-btn">Complete Reservation</button>
            </form>
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
        // Toggle menu function
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            const hamburger = document.querySelector('.hamburger');
            const header = document.querySelector('.page-header');
            menu.classList.toggle('active');
            header.classList.toggle('hidden');
        }
    </script>
</body>
</html>