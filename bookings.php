<?php
session_start(); // Start the session at the beginning
require 'database.php';  // Database connection

// Function to get the logged-in user's status (fetching user details)
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

// Check if the user is logged in
if ($current_user === null) {
    echo "<p>You must be logged in to view your booking history.</p>";
    exit;
}

$user_id = $_SESSION['user_id']; // Use user_id for querying the database

// Query to fetch all bookings for the logged-in user (from user_reservation)
$stmt = $mysqli->prepare("
    SELECT 
        r.reservation_code, 
        r.first_name, 
        r.last_name, 
        r.check_in, 
        r.check_out, 
        r.total_amount, 
        p.status AS payment_status,
        r.expires_at
    FROM user_reservation r
    LEFT JOIN payments p ON r.reservation_code = p.user_reservation_code
    WHERE r.user_id = ?
    ORDER BY r.created_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Booking History</title>
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
            margin: 20px auto;
            margin-top: 60px;
            margin-bottom: 50px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: black;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table td {
            background-color: #f9f9f9;
        }
        table td a {
            color: #007bff;
            text-decoration: none;
        }
        table td a:hover {
            text-decoration: underline;
        }
        .no-bookings {
            text-align: center;
            font-size: 18px;
            color: #777;
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
    <h2>Your Booking History</h2>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr><th>Reservation Code</th><th>Name</th><th>Check-In</th><th>Check-Out</th><th>Total Amount</th><th>Payment Status</th><th>Actions</th></tr></thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
            $reservation_code = $row['reservation_code'];
            $name = $row['first_name'] . " " . $row['last_name'];
            $check_in = date("Y-m-d", strtotime($row['check_in']));
            $check_out = date("Y-m-d", strtotime($row['check_out']));
            $total_amount = number_format($row['total_amount'], 2);
            $payment_status = ucfirst($row['payment_status']); // Capitalize the first letter
            $expires_at = $row['expires_at'];
            $current_time = time();

            // Check if reservation is expired
            $is_expired = ($current_time > $expires_at);

            // Check if payment has already been approved
            $is_payment_approved = ($payment_status == 'Approved');

            echo "<tr>";
            echo "<td>" . $reservation_code . "</td>";
            echo "<td>" . $name . "</td>";
            echo "<td>" . $check_in . "</td>";
            echo "<td>" . $check_out . "</td>";
            echo "<td>₱" . $total_amount . "</td>";
            echo "<td>" . $payment_status . "</td>";

            // Check if the reservation is expired or the payment is already approved
            if ($is_expired) {
                echo "<td><span style='color: red;'>Reservation Expired</span> | <a href='reservation_form.php'>Create New Reservation</a></td>";
            } elseif ($is_payment_approved) {
                echo "<td><span style='color: green;'>Payment Already Approved</span></td>";
            } else {
                echo "<td><a href='view_booking.php?code=$reservation_code'>View</a> | <a href='saved_billing.php?code=$reservation_code'>Pay</a></td>";
            }
            
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p class='no-bookings'>You have no bookings.</p>";
    }
    ?>
</div>
<?php include "headers/footer.php"?>
</body>
</html>