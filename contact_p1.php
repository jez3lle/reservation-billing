<?php
session_start(); // Start the session at the beginning

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "resort_db";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch reviews from the database
$sql = "SELECT * FROM reviews ORDER BY created_at DESC";
$result = $conn->query($sql);
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
    <title>CONTACT US - Rainbow Forest Paradise Resort</title>
    <link rel="stylesheet" href="styles/mystyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
    .rfp-content-wrapper {
        width: 90%;
        margin: auto;
        padding: 40px 0;
    }

    h1, h2 {
        font-weight: 600;
    }

    .rfp-contact-section {
        text-align: center;
        padding: 40px 0;
        background: #213D30; 
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .rfp-contact-container {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .rfp-contact-item {
        max-width: 400px;
        text-align: left;
    }

    .rfp-contact-item a {
        color: #ffcc00;
        text-decoration: none;
        font-weight: bold;
    }

    .rfp-contact-item a:hover {
        text-decoration: underline;
    }

    /* Reviews Section */
    .rfp-reviews-section {
        margin-top: 50px;
        padding: 40px;
        background: #213D30; /* Slightly lighter blue */
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .rfp-review-wrapper {
        display: flex;
        gap: 40px;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    /* Review Form */
    .rfp-review-form {
        flex: 1;
        padding: 20px;
        background: white;
        border-radius: 8px;
        color: #333;
    }

    .rfp-review-form input, 
    .rfp-review-form textarea, 
    .rfp-review-form select {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .rfp-review-form button {
        width: 100%;
        padding: 10px;
        background-color: #ffcc00; /* Gold */
        color: #333;
        border: none;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
        border-radius: 5px;
    }

    .rfp-review-form button:hover {
        background-color: #e6b800;
    }

    /* Reviews Display */
    .rfp-reviews-container {
        flex: 1;
        padding: 20px;
        background: #213D30;
        border-radius: 8px;
        color: white;
    }

    .rfp-review {
        padding: 15px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .rfp-rating {
        font-size: 20px;
        color: #ffcc00;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .rfp-contact-container, .rfp-review-wrapper {
            flex-direction: column;
            align-items: center;
        }

        .rfp-contact-item, .rfp-review-form, .rfp-reviews-container {
            max-width: 100%;
        }
    }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>
    
    <main class="rfp-content-wrapper">
    <section class="rfp-contact-section">
        <h1>Contact Us</h1>
        <div class="rfp-contact-container">
            <div class="rfp-contact-item">
                <h2>Get in Touch</h2>
                <p>Have questions? We're here to help.</p>
                <p><strong>Phone:</strong> <a href="tel:09605877561">0960 587 7561</a></p>
                <p><strong>Email:</strong> <a href="mailto:rainbowforestparadise2020@gmail.com">rainbowforestparadise2020@gmail.com</a></p>
            </div>
            
            <div class="rfp-contact-item">
                <h2>Follow Us</h2>
                <p>Stay updated with our latest news and promotions.</p>
                <p><a href="https://www.facebook.com/profile.php?id=100050508021940" target="_blank">Facebook Page</a></p>
            </div>
        </div>
    </section>

    <section class="rfp-reviews-section">
        <h1>Guest Reviews & Feedback</h1>
        <div class="rfp-review-wrapper">
            <div class="rfp-review-form">
                <h2>Leave a Review</h2>
                <form action="submit_review.php" method="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required placeholder="Your Name">
                    
                    <label for="visit-date">When did you go?</label>
                    <input type="date" id="visit-date" name="visit_date" required>
                    
                    <label for="rating">Rating:</label>
                    <select id="rating" name="rating" required>
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                    
                    <label for="message">Your Feedback:</label>
                    <textarea id="message" name="message" rows="5" required placeholder="Your feedback..."></textarea>
                    
                    <button type="submit">Submit Review</button>
                </form>
            </div>

            <div class="rfp-reviews-container">
                <h2>What Our Guests Say</h2>
                <div class="rfp-review-list">
                    <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='rfp-review'>";
                                echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
                                echo "<p class='rfp-rating'>" . str_repeat("★", $row["rating"]) . "</p>";
                                echo "<p>\"" . htmlspecialchars($row["message"]) . "\"</p>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No reviews yet. Be the first to leave a review!</p>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>
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

<?php $conn->close(); ?>
