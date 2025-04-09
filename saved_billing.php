<?php
session_start();
require_once 'db_connect.php';

// Check for session message
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

// IMPORTANT: Always prioritize getting data from the URL parameters over session data
$reservation = null;
$code = null;
$type = null;

// Get reservation code and type from URL parameters if available
if (isset($_GET['code']) && isset($_GET['type'])) {
    $code = mysqli_real_escape_string($conn, $_GET['code']);
    $type = mysqli_real_escape_string($conn, $_GET['type']);
    
    // Clear any existing reservation in session to avoid confusion
    unset($_SESSION['reservation_details']);
    
    // Get reservation data from database based on URL parameters
    if ($type === 'guest') {
        $query = "SELECT * FROM guest_reservation WHERE reservation_code = '$code'";
    } elseif ($type === 'user') {
        $query = "SELECT * FROM user_reservation WHERE reservation_code = '$code'";
    } else {
        header("Location: book.php");
        exit;
    }

    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Format the reservation data
        $reservation = [
            'reservation_code' => $row['reservation_code'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'contact_number' => $row['contact_number'],
            'tour_type' => $row['tour_type'],
            'check_in' => $row['check_indate'] ?? $row['check_in'],
            'check_out' => $row['check_outdate'] ?? $row['check_out'],
            'adult_count' => $row['adult_count'],
            'kid_count' => $row['kid_count'] ?? 0,
            'total_price' => $row['base_price'] ?? $row['total_price'] ?? 0,
            'total_amount' => $row['total_price'] ?? $row['total_amount'] ?? 0,
            'created_at' => $row['created_at'],
            'expires_at' => $row['expires_at'],
            'extras' => [
                'extra_mattress' => $row['extra_mattress'] ?? 0,
                'extra_pillow' => $row['extra_pillow'] ?? 0,
                'extra_blanket' => $row['extra_blanket'] ?? 0,
            ]
        ];
        
        // Store this specific reservation in session for future reference
        $_SESSION['reservation_details'] = $reservation;
    } else {
        header("Location: book.php");
        exit;
    }
} 
// Only use session data if URL parameters aren't available
else if (isset($_SESSION['reservation_details'])) {
    $reservation = $_SESSION['reservation_details'];
} else {
    // No reservation data available, redirect to booking page
    header("Location: book.php");
    exit;
}

// If we somehow still don't have reservation data, redirect
if (!$reservation) {
    header("Location: book.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Saved - Rainbow Forest Paradise</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .saved-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .reservation-details {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: left;
        }
        .expiration-info {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
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
        .reservation-code {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="saved-container">
        <h1>Billing Information Saved</h1>
        
        <?php if ($message): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="reservation-code">
            Reservation Code: <?php echo htmlspecialchars($reservation['reservation_code']); ?>
        </div>
        
        <div class="reservation-details">
            <h3>Reservation Summary</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($reservation['email']); ?></p>
            <p><strong>Tour Type:</strong> 
                <?php 
                $tourTypes = [
                    'whole_day' => 'Whole Day Tour',
                    'day_tour' => 'Day Tour',
                    'night_tour' => 'Night Tour'
                ];
                echo htmlspecialchars($tourTypes[$reservation['tour_type']] ?? $reservation['tour_type']); 
                ?>
            </p>
            <p><strong>Total Amount:</strong> ₱<?php 
                // Check which field to use based on reservation type
                $amount = isset($type) && $type === 'guest' 
                    ? $reservation['total_price'] 
                    : ($reservation['total_amount'] ?? $reservation['total_price']);
                echo number_format(floatval($amount), 2); 
            ?></p>
        </div>
        
        <div class="expiration-info">
            <p>Your reservation will expire in <strong>3 hours</strong> if payment is not completed.</p>
            <p>Please use your reservation code when completing your payment later.</p>
        </div>
        
        <a href="billing.php?code=<?php echo urlencode($reservation['reservation_code']); ?>&type=<?php echo isset($type) ? urlencode($type) : 'guest'; ?>" class="btn">Complete Payment Now</a>
        
        <p>You can also search for your reservation using your reservation code anytime.</p>
        
        <a href="home_p1.php" class="btn">Go to home</a>
    </div>
</body>
</html>