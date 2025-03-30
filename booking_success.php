<?php
session_start();

// Get session data
$guest_name = $_SESSION['guest_name'] ?? 'N/A';
$email = $_SESSION['email'] ?? 'N/A';
$phone = $_SESSION['phone'] ?? 'N/A';
$check_in = $_SESSION['check_in'] ?? 'N/A';
$check_out = $_SESSION['check_out'] ?? 'N/A';
$room_type = $_SESSION['room_type'] ?? 'N/A';
$guests = $_SESSION['guests'] ?? 'N/A';
$special_requests = $_SESSION['special_requests'] ?? 'None';

// Calculate Length of Stay
$length_of_stay = ($check_in !== 'N/A' && $check_out !== 'N/A') ? (strtotime($check_out) - strtotime($check_in)) / 86400 : 'N/A';

// Time of Arrival (Assume 2:00 PM)
$arrival_time = "2:00 PM";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .summary-box {
            position: absolute;
            top: 20px;
            left: 1500px;
            width: 250px;
            padding: 15px;
            border-radius: 10px;
            background-color:rgb(0, 0, 0);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }
        .summary-box h3 {
            margin-top: 0;
            color: #03624c;
            text-align: center;
        }
        .summary-box p {
            margin: 5px 0;
        }
        .success-box {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #e6ffe6;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-box h2 {
            color: #03624c;
        }
        .success-box p {
            color: #333;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #03624c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #024d3a;
        }
    </style>
</head>
<body>

    <!-- 🔹 Reservation Summary (Top Left) -->
    <div class="summary-box">
        <h3>Reservation Summary</h3>
        <p><strong>Length of Stay:</strong> <?php echo $length_of_stay; ?> night(s)</p>
        <p><strong>Time of Arrival:</strong> <?php echo $arrival_time; ?></p>
        <p><strong>Room Type:</strong> <?php echo $room_type; ?></p>
    </div>

    <!-- ✅ Booking Success Message (Centered) -->
    <div class="success-box">
        <h2>Booking Successful! ✅</h2>
        <p>Thank you for booking with us. Your reservation details are as follows:</p>
        <p><strong>Guest Name:</strong> <?php echo $guest_name; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Phone:</strong> <?php echo $phone; ?></p>
        <p><strong>Check-in Date:</strong> <?php echo $check_in; ?></p>
        <p><strong>Check-out Date:</strong> <?php echo $check_out; ?></p>
        <p><strong>Room Type:</strong> <?php echo $room_type; ?></p>
        <p><strong>Number of Guests:</strong> <?php echo $guests; ?></p>
        <p><strong>Special Requests:</strong> <?php echo $special_requests; ?></p>
        <a href="homep1.html" class="btn">Return to Home</a>
    </div>

</body>
</html>
