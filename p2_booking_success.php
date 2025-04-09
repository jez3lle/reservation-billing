<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Include the database connection
$conn = require "database.php"; // Assuming this file returns the $conn object

// Check if the booking reference is provided in the URL
if (!isset($_GET['ref'])) {
    error_log("No booking reference provided in the URL.");
    header("Location: index.php");
    exit;
}

// Sanitize the booking reference
$booking_reference = htmlspecialchars($_GET['ref']);

// Prepare a statement to retrieve booking details
$stmt = $conn->prepare("SELECT * FROM p2_guest_reservation WHERE booking_reference = ?");
$stmt->bind_param("s", $booking_reference);
$stmt->execute();
$result = $stmt->get_result();

// Check if the booking exists
if ($result->num_rows === 0) {
    error_log("No booking found for reference: " . $booking_reference);
    echo "<h1>Booking Not Found</h1>";
    echo "<p>We could not find a booking with the reference: <strong>" . $booking_reference . "</strong>.</p>";
    echo "<p><a href='index.php'>Return to Home</a></p>";
    exit;
}

// Fetch the booking details
$booking = $result->fetch_assoc();

// Fetch room details based on the booking reference
$room_stmt = $conn->prepare("SELECT r.name FROM p2_guest_reservation_room AS rr JOIN rooms AS r ON rr.room_id = r.id WHERE rr.reservation_id = ?");
$room_stmt->bind_param("i", $booking['id']); // Assuming 'id' is the primary key of the reservation
$room_stmt->execute();
$room_result = $room_stmt->get_result();
$rooms = [];
while ($room = $room_result->fetch_assoc()) {
    $rooms[] = $room['name']; // Collect room names
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Rainbow Forest Paradise</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .confirmation-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .confirmation-header {
            text-align: center;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .success-icon {
            color: #28a745;
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
        .confirmation-message {
            text-align: center;
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .reservation-code {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .confirmation-section {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .reservation-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .instructions {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
        }
        .actions {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .print-button {
            background-color: #6c757d;
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="confirmation-container">
    <div class="confirmation-header">
        <h1>Reservation Confirmed</h1>
        <p>Rainbow Forest Paradise</p>
    </div>
    
    <div class="success-icon">✓</div>
    
    <div class="confirmation-message">
        <h2>Thank You for Your Reservation!</h2>
        <p>Your payment proof has been received and is being processed.</p>
    </div>
    
    <div class="reservation-code">
        Reservation Code: <?php echo htmlspecialchars($booking['booking_reference']); ?>
    </div>
    
    <div class="confirmation-section">
        <h3>Reservation Summary</h3>
        <div class="reservation-details">
            <div>
                <strong>Name:</strong> 
                <?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?>
            </div>
            <div>
                <strong>Email:</strong> 
                <?php echo htmlspecialchars($booking['email']); ?>
            </div>
            <div>
                <strong>Contact Number:</strong> 
                <?php echo htmlspecialchars($booking['contact_number']); ?>
            </div>
            <div>
                <strong>Check-in Date:</strong> 
                <?php echo htmlspecialchars($booking['check_in']); ?>
            </div>
            <div>
                <strong>Check-out Date:</strong> 
                <?php echo htmlspecialchars($booking['check_out']); ?>
            </div>
            <div>
                <strong>Adults:</strong> 
                <?php echo htmlspecialchars($booking['adults']); ?>
            </div>
            <div>
                <strong>Children:</strong> 
                <?php echo htmlspecialchars($booking['children']); ?>
            </div>
            <div>
                <strong>Room Type:</strong> 
                <?php echo htmlspecialchars(implode(', ', $rooms)); ?>
            </div>
        </div>
        
        <div>
            <strong>Total Amount Paid:</strong> ₱<?php echo number_format($booking['total_amount'], 2); ?>
        </div>
    </div>
    
    <div class="instructions">
        <h3>What's Next?</h3>
        <p>Your reservation is now in our system. Here's what you can expect:</p>
        <ol>
            <li>Within 24 hours, you will receive an email confirming your payment has been verified.</li>
            <li>Please save or print this confirmation for your records.</li>
            <li>You will need to present your reservation code upon arrival.</li>
            <li>If you have any questions, please contact us at support@rainbowforestparadise.com or call (123) 456-7890.</li>
        </ol>
    </div>
    
    <div class="actions">
        <a href="home_p2.php" class="btn">Return to Home</a>
        <button onclick="window.print()" class="btn print-button">Print Confirmation</button>
    </div>
</div>
</body>
</html>