<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-In / Check-Out</title>
</head>
<body>
    <h1>Check-In / Check-Out</h1>

    <form action="p2_process_checkin_checkout.php" method="POST">
        <h2>Check-In</h2>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number" required><br>

        <label for="adults">Adults:</label>
        <input type="number" id="adults" name="adults" required><br>

        <label for="children">Children:</label>
        <input type="number" id="children" name="children" required><br>

        <label for="room_id">Room ID:</label>
        <input type="number" id="room_id" name="room_id" required><br>

        <label for="check_in">Check-In Date:</label>
        <input type="date" id="check_in" name="check_in" required><br>

        <label for="check_out">Check-Out Date:</label>
        <input type="date" id="check_out" name="check_out" required><br>

        <h2>Check-Out</h2>
        <label for="reservation_id">Reservation ID:</label>
        <input type="number" id="reservation_id" name="reservation_id" required><br>

        <button type="submit" name="action" value="checkin">Check In</button>
        <button type="submit" name="action" value="checkout">Check Out</button>
    </form>
</body>
</html>