<?php
session_start();

// Check if reservation details are in the session
if (!isset($_SESSION['reservation_details'])) {
    echo "No reservation details found in session.";
    exit;
}

// Retrieve reservation details
$reservation = $_SESSION['reservation_details'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation - Rainbow Forest Paradise Resort and Campsite</title>
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
        .reservation-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
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
        <p>Rainbow Forest Paradise Resort and Campsite</p>
    </div>
    
    <div class="success-icon">✓</div>
    
    <div class="confirmation-message">
        <h2>Thank You for your Payment!</h2>
        <p>Your payment proof has been received and is being processed.</p>
    </div>
    
    <div class="reservation-code">
        Reservation Code: <?php echo htmlspecialchars($reservation['reservation_code']); ?>
    </div>
    
    <div class="confirmation-section">
        <h3>Reservation Summary</h3>
        <div class="reservation-details">
            <div>
                <strong>Name:</strong> 
                <?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?>
            </div>
            <div>
                <strong>Email:</strong> 
                <?php echo htmlspecialchars($reservation['email']); ?>
            </div>
            <div>
                <strong>Contact Number:</strong> 
                <?php echo htmlspecialchars($reservation['contact_number']); ?>
            </div>
            <div>
                <strong>Tour Type:</strong> 
                <?php 
                $tourTypes = [
                    'whole_day' => 'Whole Day Tour',
                    'day_tour' => 'Day Tour',
                    'night_tour' => 'Night Tour'
                ];
                echo htmlspecialchars($tourTypes[$reservation['tour_type']]); 
                ?>
            </div>
            <div>
                <strong>Check-in Date:</strong> 
                <?php echo htmlspecialchars($reservation['check_in']); ?>
            </div>
            <div>
                <strong>Check-out Date:</strong> 
                <?php echo htmlspecialchars($reservation['check_out']); ?>
            </div>
            <div>
                <strong>Adults:</strong> 
                <?php echo htmlspecialchars($reservation['adult_count']); ?>
            </div>
            <div>
                <strong>Kids:</strong> 
                <?php echo htmlspecialchars($reservation['kid_count'] ?? '0'); ?>
            </div>
        </div>
        
        <div>
            <strong>Total Amount Paid:</strong> ₱<?php echo number_format($reservation['total_amount'], 2); ?>
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
        <a href="home_p1.php" class="btn">Return to Home</a>
        <button onclick="window.print()" class="btn print-button">Print Confirmation</button>
    </div>
</div>

<script>
    // Optional: Add confetti effect for a more celebratory confirmation
    function launchConfetti() {
        const confettiCount = 200;
        const confettiColors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8'];
        
        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.style.position = 'fixed';
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.top = -20 + 'px';
            confetti.style.width = Math.random() * 10 + 5 + 'px';
            confetti.style.height = Math.random() * 10 + 5 + 'px';
            confetti.style.backgroundColor = confettiColors[Math.floor(Math.random() * confettiColors.length)];
            confetti.style.borderRadius = '50%';
            confetti.style.zIndex = '1000';
            document.body.appendChild(confetti);
            
            const animation = confetti.animate(
                [
                    { transform: 'translate(0, 0)', opacity: 1 },
                    { transform: `translate(${Math.random() * 100 - 50}px, ${window.innerHeight}px)`, opacity: 0 }
                ],
                {
                    duration: Math.random() * 3000 + 2000,
                    easing: 'cubic-bezier(0.215, 0.61, 0.355, 1)'
                }
            );
            
            animation.onfinish = () => {
                confetti.remove();
            };
        }
    }
    
    // Launch confetti when the page loads
    window.addEventListener('load', launchConfetti);
</script>
</body>
</html>