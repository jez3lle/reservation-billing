<?php
include('db.php'); // Ensure database connection is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect data from the form
    $arrivalDate = $_POST['arrival-date'];
    $departureDate = $_POST['departure-date'];
    $lastName = $_POST['guest-last-name']; // Collect last name
    $firstName = $_POST['guest-first-name']; // Collect first name
    $guestEmail = $_POST['guest-email']; // Collect guest email
    $guestPhone = $_POST['guest-phone']; // Collect guest phone

    // Combine first and last name into a single full name
    $guestName = $lastName . ', ' . $firstName;

    // Set payment expiry to 48 hours from now
    $paymentExpiry = date('Y-m-d H:i:s', strtotime('+48 hours'));  // 48-hour grace period

    // Insert reservation into the database
    $query = "INSERT INTO reservations (arrival_date, departure_date, guest_name, guest_email, guest_phone, reservation_status, created_at, payment_expiry)
              VALUES ('$arrivalDate', '$departureDate', '$guestName', '$guestEmail', '$guestPhone', 'pending', NOW(), '$paymentExpiry')";
    if (mysqli_query($conn, $query)) {
        $reservationId = mysqli_insert_id($conn); // Get the inserted reservation ID
        header("Location: upload-proof.php?id=$reservationId"); // Redirect to upload proof of payment
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Pre-fill arrival and departure dates if passed via GET
$arrivalDate = isset($_GET['arrival-date']) ? $_GET['arrival-date'] : '';
$departureDate = isset($_GET['departure-date']) ? $_GET['departure-date'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        /* Base Styles */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #89BAA9; /* Dark Green */
        }
        .reservation-form {
            width: 100%;
            max-width: 1000px;
            padding: 30px;
            background: rgba(137, 186, 169, 0.85); /* Light Green */
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 2px solid #ffffff;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        h2 {
            text-align: center;
            font-size: 26px;
            color: #ffffff;
            margin-bottom: 20px;
        }
        .form-row {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .form-group {
            width: 48%; /* Two columns */
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 8px;
            color: white;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: white;
            color: #333;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #89BAA9; /* Light Green */
        }
        .form-group.full-width {
            width: 100%; /* Full width for special requests */
        }
        .reservation-button {
            display: block;
            width: 100%;
            padding: 16px;
            background: #89BAA9; /* Light Green */
            color: white;
            font-size: 18px;
            font-weight: 600;
            border: 2px solid white;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .reservation-button:hover {
            background-color: #002f25; /* Dark Green */
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            .form-group {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <form class="reservation-form" method="POST" action="reservation-form.php">
        <h2>Guest Reservation Form</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="guest-last-name">Last Name:</label>
                <input type="text" id="guest-last-name" name="guest-last-name" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <label for="guest-first-name">First Name:</label>
                <input type="text" id="guest-first-name" name="guest-first-name" placeholder="First Name" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="guest-email">Guest Email:</label>
                <input type="email" id="guest-email" name="guest-email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <label for="guest-phone">Guest Phone:</label>
                <input type="tel" id="guest-phone" name="guest-phone" placeholder="Phone Number" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="number-of-guests">Number of Adults:</label>
                <input type="number" id="number-of-adults" name="number-of-guests" placeholder="Number of Guests" required>
            </div>
            <div class="form-group">
                <label for="number-of-guests">Number of Kids:</label>
                <input type="number" id="number-of-kids" name="number-of-guests" placeholder="Number of Guests" required>
            </div>
        </div>

        <div class="form-row">
        <div class="form-group">
                <label for="arrival-date">Arrival Date:</label>
                <input type="date" id="arrival-date" name="arrival-date" value="<?php echo $arrivalDate; ?>" required>
            </div>
            <div class="form-group">
                <label for="departure-date">Departure Date:</label>
                <input type="date" id="departure-date" name="departure-date" value="<?php echo $departureDate; ?>" required>
            </div>
        </div>

        <div class="form-group full-width">
            <label for="special-requests">Special Requests:</label>
            <textarea id="special-requests" name="special-requests" rows="2" placeholder="Enter any special requests or requirements"></textarea>
        </div>

        <button type="submit" class="reservation-button">Submit Reservation</button>
    </form>
</body>
</html>
