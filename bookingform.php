<?php
// Start session if needed
session_start();

// Get check-in and check-out dates from URL parameters
$check_in = isset($_GET['check_in']) ? htmlspecialchars($_GET['check_in']) : '';
$check_out = isset($_GET['check_out']) ? htmlspecialchars($_GET['check_out']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Resort Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            background: green;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }
        button:hover {
            background: darkgreen;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 style="text-align: center;">Resort Booking</h2>
    <form action="process_booking.php" method="POST">
        <label for="guest_name">Full Name:</label>
        <input type="text" id="guest_name" name="guest_name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required pattern="[0-9]{10,15}" placeholder="Enter a valid phone number">

        <label for="check_in">Check-in Date:</label>
        <input type="date" id="check_in" name="check_in" required value="<?= $check_in ?>" readonly>

        <label for="check_out">Check-out Date:</label>
        <input type="date" id="check_out" name="check_out" required value="<?= $check_out ?>" readonly>

        <label for="room_type">Select Room:</label>
        <select id="room_type" name="room_type" required>
            <option value="Glass Cabin">Glass Cabin</option>
            <option value="Concrete Room">Concrete Room</option>
            <option value="Up and Down House">Up and Down House</option>
            <option value="Kubo Room">Kubo Room</option>
            <option value="Concrete House">Concrete House</option>
            <option value="Group Cabin">Group Cabin</option>
            <option value="Teepe Hut">Teepe Hut</option>
            <option value="Open Cottage">Open Cottage</option>
            <option value="Dome Tent">Dome Tent</option>
            <option value="Canopy Tent">Canopy Tent</option>
        </select>

        <label for="guests">Number of Guests:</label>
        <input type="number" id="guests" name="guests" min="1" required>

        <label for="requests">Special Requests:</label>
        <textarea id="requests" name="requests" rows="3" placeholder="Any special requests (optional)"></textarea>

        <button type="submit">Submit Booking</button>
    </form>
</div>

</body>
</html>
