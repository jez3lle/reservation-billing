<?php
session_start();
require 'database.php';  // Database connection

// Function to get the current user's status (name)
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

// Check if reservation code is passed
if (!isset($_GET['code'])) {
    echo "No reservation code provided.";
    exit;
}

$reservation_code = $_GET['code'];

// Query for reservation details
$stmt = $mysqli->prepare("
    SELECT * 
    FROM user_reservation 
    WHERE reservation_code = ?
");

$stmt->bind_param("s", $reservation_code);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="styles/mystyle.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 2rem;
        }

        .details {
            margin-top: 30px;
            font-size: 1.1rem;
        }

        .details p {
            margin: 10px 0;
            line-height: 1.8;
        }

        .details strong {
            color: #007bff;
        }

        .no-reservation {
            text-align: center;
            color: #f44336;
            font-size: 1.2rem;
        }

        .button {
            display: block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
        }

        .button:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
<?php include "headers/header.php"?>
<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <?php $reservation = $result->fetch_assoc(); ?>
        <h2>Reservation Details</h2>
        <div class="details">
            <p><strong>Reservation Code:</strong> <?= htmlspecialchars($reservation['reservation_code']); ?></p>
            <p><strong>Guest Name:</strong> <?= htmlspecialchars($reservation['first_name']) . " " . htmlspecialchars($reservation['last_name']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($reservation['email']); ?></p>
            <p><strong>Contact Number:</strong> <?= htmlspecialchars($reservation['contact_number']); ?></p>
            <p><strong>Check-In Date:</strong> <?= date("Y-m-d", strtotime($reservation['check_in'])); ?></p>
            <p><strong>Check-Out Date:</strong> <?= date("Y-m-d", strtotime($reservation['check_out'])); ?></p>
            <p><strong>Total Amount:</strong> ₱<?= number_format($reservation['total_amount'], 2); ?></p>
        </div>
        <a href="bookings.php" class="button">Back to Booking History</a>
    <?php else: ?>
        <p class="no-reservation">No reservation found for the provided code.</p>
    <?php endif; ?>
</div>
<?php include "headers/footer.php"?>
</body>
</html>
