<?php
session_start();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews & Feedback - Rainbow Forest Paradise Resort and Campsite</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="mystyle.css"> <!-- Ensure CSS is included -->
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .content-wrapper { max-width: 600px; margin: 40px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        .review-form form { display: flex; flex-direction: column; gap: 15px; }
        .review-form label { font-weight: 500; color: black; text-align: left; display: block; }
        .review-form input, .review-form select, .review-form textarea {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;
        }
        .review-form button { background-color: #27ae60; color: white; padding: 10px; border: none; border-radius: 5px; font-size: 18px; cursor: pointer; }
        .reviews-container .review {
            background: #f1f1f1; padding: 15px; border-radius: 5px; margin-bottom: 15px; color: black; /* Ensure text is black */
        }
        .reviews-container .rating { color: #f4b400; font-size: 20px; }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="page-header">
        <div class="navbar">
            <div class="logo">
                <img src="images/rainbow-logo.png" alt="Resort Logo">
                <div class="logo-text">
                    <h1>Rainbow Forest Paradise</h1>
                    <h2>Resort and Campsite</h2>
                </div>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="home_p2.php">Home</a></li>
                    <li><a href="aboutus_p2.php">About</a></li>
                    <li><a href="accomodation_p2.html">Accommodations</a></li>
                    <li><a href="activities_p2.php">Activities</a></li>
                    <li><a href="contact_p2.php">Contact Us</a></li>
                    <li><a href="#">Book Now</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="content-wrapper">
        <section class="reviews-section">
            <h1>Guest Reviews & Feedback</h1>

            <!-- Review Form -->
            <section class="review-form">
                <h2>Leave a Review</h2>
                <form action="submit_review.php" method="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

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
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit">Submit Review</button>
                </form>
            </section>

            <!-- Display Guest Reviews -->
            <section class="reviews-container">
                <h2>What Our Guests Say</h2>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='review'>";
                        echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
                        echo "<p class='rating'>" . str_repeat("★", $row["rating"]) . "</p>";
                        echo "<p>\"" . htmlspecialchars($row["message"]) . "\"</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No reviews yet. Be the first to leave a review!</p>";
                }
                ?>
            </section>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Rainbow Forest Paradise Resort and Campsite</p>
    </footer>

</body>
</html>

<?php $conn->close(); ?>