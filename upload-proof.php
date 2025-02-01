<?php
include('db.php'); // Ensure database connection is included

$reservationId = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch reservation details and bill
$query = "SELECT guest_name, arrival_date, departure_date, DATEDIFF(departure_date, arrival_date) AS nights, 
          1000 * DATEDIFF(departure_date, arrival_date) AS total_bill, payment_expiry 
          FROM reservations 
          WHERE id = '$reservationId'";
$result = mysqli_query($conn, $query);
$reservation = mysqli_fetch_assoc($result);

if (!$reservation) {
    die("Reservation not found!");
}

// Check if the payment has expired
$currentDate = date('Y-m-d');
if ($currentDate > $reservation['payment_expiry']) {
    $message = "<span style='color: red;'>Sorry, your payment period has expired. Please contact us for further assistance.</span>";
} else {
    $message = ''; // Message to display status of the upload

    // Handle file upload
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['proof-of-payment'])) {
        $fileName = $_FILES['proof-of-payment']['name'];
        $fileTmpName = $_FILES['proof-of-payment']['tmp_name'];
        $fileSize = $_FILES['proof-of-payment']['size'];
        $fileError = $_FILES['proof-of-payment']['error'];
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf']; // Allowed file extensions

        // Get the file extension
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate file type
        if (!in_array($fileExtension, $allowedTypes)) {
            $message = "<span style='color: red;'>Invalid file type. Please upload a JPG, PNG, or PDF.</span>";
        } elseif ($fileError === 0) {
            // Set upload directory and file path
            $uploadDir = 'uploads/';
            $newFileName = uniqid('payment_', true) . '.' . $fileExtension; // Generate unique file name
            $fileDestination = $uploadDir . $newFileName;

            // Move file to the target directory
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // Insert file path into the proof_payment table
                $query = "INSERT INTO proof_of_payment (reservation_id, file_path, upload_date) 
                          VALUES ('$reservationId', '$fileDestination', NOW())";
                if (mysqli_query($conn, $query)) {
                    $message = "<span style='color: green;'>Proof of payment uploaded successfully!</span>";
                } else {
                    $message = "<span style='color: red;'>Error saving proof of payment: " . mysqli_error($conn) . "</span>";
                }
            } else {
                $message = "<span style='color: red;'>Error uploading the file!</span>";
            }
        } else {
            $message = "<span style='color: red;'>There was an error with the file upload!</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Proof of Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgba(137, 186, 169, 0.85); /* Light Green */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        .container h1 {
            color: #89BAA9; /* Light Green */
        }
        .bill-details {
            margin: 20px 0;
            text-align: left;
            background: rgba(137, 186, 169, 0.1);
            padding: 10px;
            border-radius: 10px;
        }
        .bill-details strong {
            color: #89BAA9;
        }
        .form-group {
            margin: 20px 0;
        }
        input[type="file"] {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
            cursor: pointer;
        }
        button {
            padding: 14px 20px;
            background: #89BAA9; /* Light Green */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #00553d; /* Dark Green */
        }
        .message {
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Proof of Payment</h1>
        <div class="bill-details">
            <p><strong>Guest Name:</strong> <?php echo htmlspecialchars($reservation['guest_name']); ?></p>
            <p><strong>Arrival Date:</strong> <?php echo htmlspecialchars($reservation['arrival_date']); ?></p>
            <p><strong>Departure Date:</strong> <?php echo htmlspecialchars($reservation['departure_date']); ?></p>
            <p><strong>Number of Nights:</strong> <?php echo htmlspecialchars($reservation['nights']); ?></p>
            <p><strong>Total Bill:</strong> PHP <?php echo htmlspecialchars($reservation['total_bill']); ?></p>
            <p><strong>Payment Expiry:</strong> <?php echo htmlspecialchars($reservation['payment_expiry']); ?></p>
        </div>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($currentDate <= $reservation['payment_expiry']): ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="proof-of-payment">Select File:</label>
                    <input type="file" name="proof-of-payment" id="proof-of-payment" required>
                </div>
                <button type="submit">Upload</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
