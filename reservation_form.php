<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['check_in']) || !isset($_SESSION['check_out'])) {
    die("Invalid access. Please select a date first.");
}

$check_in = $_SESSION['check_in'];
$check_out = $_SESSION['check_out'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form</title>
    <link rel="stylesheet" href="reservation.css">
</head>
<body>

 
    <div class="container">
        <h2>Reservation Form</h2>
        <form action="reservation_process.php" method="POST">

            <!-- Date Section -->
            <div class="form-row">
                <div class="form-group">
                    <label>Check-in Date:</label>
                    <input type="text" name="check_in" value="<?php echo $_SESSION['check_in']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Check-out Date:</label>
                    <input type="text" name="check_out" value="<?php echo $_SESSION['check_out']; ?>" readonly>
                </div>
            </div>

            <!-- Name Section -->
            <div class="form-row">
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="last_name" required>
                </div>
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="first_name" required>
                </div>
            </div>

            <!-- Contact Details -->
            <div class="form-row">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="text" name="phone" required>
                </div>
            </div>

            <!-- Guests Section -->
            <div class="form-row">
                <div class="form-group">
                    <label>Number of Adults:</label>
                    <input type="number" name="adults" min="1" required>
                </div>
                <div class="form-group">
                    <label>Number of Kids:</label>
                    <input type="number" name="kids" min="0">
                </div>
            </div>

            <!-- Special Requests -->
            <div class="form-group full-width">
                <label>Special Requests (Optional):</label>
                <textarea name="special_requests" rows="3" placeholder="Any specific needs or preferences?"></textarea>
            </div>

            <button type="submit" class="btn">Submit Reservation</button>
        </form>
    </div>
</body>
</html>
