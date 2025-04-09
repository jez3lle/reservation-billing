<?php
session_start();

// Include database connection
$mysqli = require 'database.php';

// Check if we're accessing a specific reservation by URL parameters
if (isset($_GET['code']) && isset($_GET['type'])) {
    $code = mysqli_real_escape_string($mysqli, $_GET['code']);
    $type = $_GET['type'];
    
    // Determine which table to use
    $table = ($type === 'user') ? 'user_reservation' : 'guest_reservation';
    $reservation_code_column = ($type === 'user') ? 'user_reservation_code' : 'guest_reservation_code';
    
    // Query the database
    $stmt = $mysqli->prepare("SELECT * FROM $table WHERE reservation_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $row = $result->fetch_assoc()) {
        // Properly format the reservation for session (same as in saved_billing.php)
        $_SESSION['reservation_details'] = [
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
        $reservation = $_SESSION['reservation_details']; // Update local copy
    }
    $stmt->close();
}

// Check if the reservation exists in session
if (!isset($_SESSION['reservation_details'])) {
    echo json_encode(['error' => 'No reservation details found']);
    exit;
}

$reservation = $_SESSION['reservation_details'];
$current_time = time();
$expiration_message = '';

// Determine which table to use based on reservation type
$is_user_logged_in = isset($_SESSION['user_id']); 
$reservation_table = $is_user_logged_in ? 'user_reservation' : 'guest_reservation';
$reservation_code_column = $is_user_logged_in ? 'user_reservation_code' : 'guest_reservation_code';

// If reservation exists but no reservation code, create one and save to database
if (!isset($reservation['reservation_code'])) {
    // Create a unique reservation code - Format: YYYYMMDD-RANDOMSTRING
    $date = date('Ymd');
    $random_string = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
    $reservation_code = $date . '-' . $random_string;
    
    // Add reservation code, created_at and expires_at to session
    $_SESSION['reservation_details']['reservation_code'] = $reservation_code;
    $_SESSION['reservation_details']['created_at'] = $current_time;
    $_SESSION['reservation_details']['expires_at'] = $current_time + (3 * 60 * 60); // 3 hours from now
    
    $reservation = $_SESSION['reservation_details']; // Update local copy
    
    // Based on your database schema, adjust the field names and values
    if ($is_user_logged_in) {
        $stmt = $mysqli->prepare("
            INSERT INTO user_reservation (
                user_id,
                reservation_code, 
                first_name, 
                last_name, 
                email, 
                contact_number, 
                tour_type, 
                check_in, 
                check_out, 
                adult_count, 
                kid_count, 
                total_price, 
                extras_total,
                total_amount, 
                created_at, 
                expires_at,
                extra_mattress,
                extra_pillow,
                extra_blanket
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $extras_total = 0;
        $extra_mattress = $reservation['extras']['extra_mattress'] ?? 0;
        $extra_pillow = $reservation['extras']['extra_pillow'] ?? 0;
        $extra_blanket = $reservation['extras']['extra_blanket'] ?? 0;
        
        $stmt->bind_param(
            "isssssssiidddiiiiii", 
            $_SESSION['user_id'],
            $reservation_code,
            $reservation['first_name'],
            $reservation['last_name'],
            $reservation['email'],
            $reservation['contact_number'],
            $reservation['tour_type'],
            $reservation['check_in'],
            $reservation['check_out'],
            $reservation['adult_count'],
            $reservation['kid_count'] ?? 0,
            $reservation['total_price'],
            $extras_total,
            $reservation['total_amount'],
            $current_time,
            $_SESSION['reservation_details']['expires_at'],
            $extra_mattress,
            $extra_pillow,
            $extra_blanket
        );
    } else {
        $stmt = $mysqli->prepare("
            INSERT INTO guest_reservation (
                reservation_code, 
                first_name, 
                last_name, 
                email, 
                contact_number, 
                tour_type, 
                check_in, 
                check_out, 
                adult_count, 
                kid_count, 
                base_price,
                extras_price,
                total_price, 
                created_at, 
                expires_at,
                extra_mattress,
                extra_pillow,
                extra_blanket
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $base_price = $reservation['total_price'] ?? 0;
        $extras_price = 0;
        $extra_mattress = $reservation['extras']['extra_mattress'] ?? 0;
        $extra_pillow = $reservation['extras']['extra_pillow'] ?? 0;
        $extra_blanket = $reservation['extras']['extra_blanket'] ?? 0;
        
        $stmt->bind_param(
            "sssssssiidddiiiii", 
            $reservation_code,
            $reservation['first_name'],
            $reservation['last_name'],
            $reservation['email'],
            $reservation['contact_number'],
            $reservation['tour_type'],
            $reservation['check_in'],
            $reservation['check_out'],
            $reservation['adult_count'],
            $reservation['kid_count'] ?? 0,
            $base_price,
            $extras_price,
            $reservation['total_amount'],
            $current_time,
            $_SESSION['reservation_details']['expires_at'],
            $extra_mattress,
            $extra_pillow,
            $extra_blanket
        );
    }
    
    $stmt->execute();
    $stmt->close();
    
    // Insert initial payment record
    if ($is_user_logged_in) {
        $stmt = $mysqli->prepare("
            INSERT INTO payments (
                user_id,
                user_reservation_code,
                status,
                file_path
            ) VALUES (?, ?, 'Pending', '')
        ");
        $stmt->bind_param("is", $_SESSION['user_id'], $reservation_code);
    } else {
        $stmt = $mysqli->prepare("
            INSERT INTO payments (
                guest_reservation_code,
                status,
                file_path
            ) VALUES (?, 'Pending', '')
        ");
        $stmt->bind_param("s", $reservation_code);
    }
    
    $stmt->execute();
    $stmt->close();
} else {
    // Reservation already exists, check if it's expired
    if (isset($reservation['expires_at']) && $current_time > $reservation['expires_at']) {
        $expiration_message = "Your reservation has expired. Please create a new reservation.";
        
        // Update payments table to mark as expired
        if ($is_user_logged_in) {
            $stmt = $mysqli->prepare("
                UPDATE payments 
                SET status = 'Rejected'
                WHERE user_reservation_code = ?
            ");
        } else {
            $stmt = $mysqli->prepare("
                UPDATE payments 
                SET status = 'Rejected'
                WHERE guest_reservation_code = ?
            ");
        }
        
        $stmt->bind_param("s", $reservation['reservation_code']);
        $stmt->execute();
        $stmt->close();
    } else {
        // If not expired, refresh the expiration time
        $_SESSION['reservation_details']['expires_at'] = $current_time + (3 * 60 * 60);
        
        // Update expiration time in reservation table
        $stmt = $mysqli->prepare("
            UPDATE $reservation_table 
            SET expires_at = ? 
            WHERE reservation_code = ?
        ");
        $new_expires_at = $current_time + (3 * 60 * 60);
        $stmt->bind_param("is", $new_expires_at, $reservation['reservation_code']);
        $stmt->execute();
        $stmt->close();
    }
}

// Function to handle saving the payment progress
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_billing'])) {
    $reference = $_POST['reference_number'] ?? '';
    
    // First check if a payment record exists
    if ($is_user_logged_in) {
        $check_stmt = $mysqli->prepare("
            SELECT id FROM payments 
            WHERE user_reservation_code = ?
        ");
    } else {
        $check_stmt = $mysqli->prepare("
            SELECT id FROM payments 
            WHERE guest_reservation_code = ?
        ");
    }
    
    $check_stmt->bind_param("s", $reservation['reservation_code']);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $check_stmt->close();
    
    if ($result->num_rows > 0) {
        // Update existing payment record
        if ($is_user_logged_in) {
            $stmt = $mysqli->prepare("
                UPDATE payments 
                SET status = 'Pending',
                    payment_receipt = ?
                WHERE user_reservation_code = ?
            ");
        } else {
            $stmt = $mysqli->prepare("
                UPDATE payments 
                SET status = 'Pending',
                    payment_receipt = ?
                WHERE guest_reservation_code = ?
            ");
        }
        $stmt->bind_param("ss", $reference, $reservation['reservation_code']);
    } else {
        // Insert initial payment record
        if ($is_user_logged_in) {
            // User is logged in, use user_reservation_code
            $stmt = $mysqli->prepare("
                INSERT INTO payments (
                    user_id,
                    user_reservation_code,
                    status,
                    file_path
                ) VALUES (?, ?, 'Pending', '')
            ");
            $stmt->bind_param("is", $_SESSION['user_id'], $reservation['reservation_code']); // Use user_reservation_code
        } else {
            // User is not logged in, use guest_reservation_code
            $stmt = $mysqli->prepare("
                INSERT INTO payments (
                    guest_reservation_code,
                    status,
                    file_path
                ) VALUES (?, 'Pending', '')
            ");
            $stmt->bind_param("s", $reservation['reservation_code']); // Use guest_reservation_code
        }
    }

    // Execute the statement
    $stmt->execute();
    $stmt->close();
    
    // Redirect to a confirmation page or display a message
    $_SESSION['message'] = "Your billing information has been saved. You can complete your payment later.";
    header("Location: saved_billing.php");
    exit;
}

// Get payment status from payments table
$payment_status = 'Pending'; // Default value
$payment_reference = '';

if ($is_user_logged_in) {
    $stmt = $mysqli->prepare("
        SELECT status, payment_receipt 
        FROM payments 
        WHERE user_reservation_code = ?
        ORDER BY uploaded_at DESC
        LIMIT 1
    ");
} else {
    $stmt = $mysqli->prepare("
        SELECT status, payment_receipt 
        FROM payments 
        WHERE guest_reservation_code = ?
        ORDER BY uploaded_at DESC
        LIMIT 1
    ");
}

$stmt->bind_param("s", $reservation['reservation_code']);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $payment_status = $row['status'];
    $payment_reference = $row['payment_receipt'];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Billing - Rainbow Forest Paradise</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .billing-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .billing-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
        .reservation-code {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
            border-radius: 5px;
        }
        .payment-status {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .reservation-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .billing-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
        }
        .payment-instructions {
            margin: 15px 0;
        }
        .bank-details {
            margin-top: 25px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        .expiration-notice {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .timer {
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .actions {
            margin: 15px 0;
            text-align: center;
        }
        .upload-container {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .custom-file-upload {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
        }
        #fileInput {
            display: none;
        }
        #imagePreview {
            max-width: 100%;
            max-height: 300px;
            margin: 15px 0;
            display: none;
        }
        #uploadButton {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }
        #uploadButton:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        #statusMessage {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<div class="billing-container">
    <div class="billing-header">
        <h1>Reservation Billing</h1>
        <p>Rainbow Forest Paradise Resort and Campsite</p>
    </div>
    
    <?php if ($expiration_message): ?>
        <div class="expiration-notice">
            <?php echo htmlspecialchars($expiration_message); ?>
            <p><a href="guest_reservation.php" class="btn">Create New Reservation</a></p>
        </div>
    <?php else: ?>
        <!-- Show expiration timer -->
        <div class="expiration-notice">
            <p>This reservation will expire in <span id="timer" class="timer"></span> if payment is not completed.</p>
        </div>
        
        <div class="reservation-code">
            Reservation Code: <?php echo htmlspecialchars($reservation['reservation_code']); ?>
        </div>
        
        <div class="payment-status status-<?php echo htmlspecialchars(strtolower($payment_status)); ?>">
            Payment Status: <?php echo htmlspecialchars(ucfirst($payment_status)); ?>
            <?php if (!empty($payment_reference)): ?>
                <br>Reference Number: <?php echo htmlspecialchars($payment_reference); ?>
            <?php endif; ?>
        </div>
        
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
        </div>
        <div class="billing-section">
            <h3>Reservation Breakdown</h3>
            <div class="reservation-details">
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

            <div class="payment-instructions">
                <h4>Total Reservation Cost</h4>
                <p>
                    <strong>Base Tour Price:</strong> ₱<?php echo number_format($reservation['total_price'], 2); ?><br>
                    
                    <?php 
                    // Explicitly check for extras, even if the value is zero or empty
                    $extras_prices = [
                        'extra_mattress' => 150,
                        'extra_pillow' => 50,
                        'extra_blanket' => 50
                    ];
        <div class="upload-container">
        <h2>Upload Payment Proof</h2>
        <p>Please upload your bank transfer receipt and enter the reference number.</p>

        <input type="text" id="referenceNumberInput" placeholder="Enter Reference Number">

                    // Make sure extras is properly defined as an array
                    if (!isset($reservation['extras']) || !is_array($reservation['extras'])) {
                        $reservation['extras'] = [
                            'extra_mattress' => 0, 
                            'extra_pillow' => 0, 
                            'extra_blanket' => 0
                        ];
                    }

                    $total_extras = 0;
                    foreach (['extra_mattress', 'extra_pillow', 'extra_blanket'] as $extra) {
                        $extra_count = $reservation['extras'][$extra] ?? 0;
                        if ($extra_count > 0) {
                            $extra_total = $extra_count * $extras_prices[$extra];
                            $total_extras += $extra_total;
                            echo htmlspecialchars(ucfirst(str_replace('_', ' ', $extra)) . ": " . $extra_count . " x ₱" . number_format($extras_prices[$extra], 2) . " = ₱" . number_format($extra_total, 2)) . "<br>";
                        }
                    }
                    ?>
                    
                    <?php if ($total_extras > 0): ?>
                        <strong>Total Extras:</strong> ₱<?php echo number_format($total_extras, 2); ?><br>
                    <?php endif; ?>
                    
                    <strong>Total Amount Due:</strong> ₱<?php echo number_format($reservation['total_amount'], 2); ?>
                </p>
            </div>
        </div>

        <div class="bank-details">
            <h3>Bank Transfer Payment Instructions</h3>
            <p>Please complete your payment using the following bank details:</p>
            
            <div>
                <strong>Bank: RCBC</strong><br>
                Account Name: Rainbow Forest Paradise<br>
                Account Number: 123-456-7890<br>
            </div>

            <form method="post" action="">
                <input type="text" id="referenceNumberInput" name="reference_number" placeholder="Enter Transaction Reference Number" value="<?php echo htmlspecialchars($payment_reference); ?>">
                
                <div class="actions">
                    <button type="submit" name="save_billing" class="btn btn-secondary">Save For Later</button>
                </div>
            </form>

            <div class="upload-container">
                <h2>Upload Payment Proof</h2>
                <p>Please upload your bank transfer receipt or screenshot</p>

                <input type="file" id="fileInput" accept="image/*" capture="environment">
                <label for="fileInput" class="custom-file-upload">
                    Select Payment Proof Image
                </label>

                <img id="imagePreview" src="#" alt="Image Preview">

                <button id="uploadButton" disabled>Complete Payment</button>

                <div id="statusMessage"></div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Calculate and display the expiration countdown timer
    function updateTimer() {
        const expirationTime = <?php echo $reservation['expires_at'] ?? 0; ?>;
        const currentTime = Math.floor(Date.now() / 1000);
        const timeLeft = expirationTime - currentTime;
        
        if (timeLeft <= 0) {
            document.getElementById('timer').textContent = "EXPIRED";
            // Reload the page to show expiration message
            location.reload();
            return;
        }
        
        const hours = Math.floor(timeLeft / 3600);
        const minutes = Math.floor((timeLeft % 3600) / 60);
        const seconds = timeLeft % 60;
        
        document.getElementById('timer').textContent = 
            `${hours}h ${minutes}m ${seconds}s`;
    }
    
    // Update timer every second
    setInterval(updateTimer, 1000);
    updateTimer(); // Initial call
    
    const fileInput = document.getElementById('fileInput');
    const imagePreview = document.getElementById('imagePreview');
    const referenceNumberInput = document.getElementById('referenceNumberInput');
    const uploadButton = document.getElementById('uploadButton');
    const statusMessage = document.getElementById('statusMessage');

    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                checkUploadValidity();
            }
            reader.readAsDataURL(file);
        }
    });

    referenceNumberInput.addEventListener('input', checkUploadValidity);
    
    function checkUploadValidity() {
        const hasImage = fileInput.files.length > 0;
        const hasReferenceNumber = referenceNumberInput.value.trim() !== '';
        
        uploadButton.disabled = !(hasImage && hasReferenceNumber);
    }

    uploadButton.addEventListener('click', async function() {
        const file = fileInput.files[0];
        const referenceNumber = referenceNumberInput.value.trim();

        if (!file || !referenceNumber) {
            showStatus('Please provide both image and reference number', false);
            return;
        }

        showStatus('Uploading payment proof...', true);
        const formData = new FormData();
        formData.append('paymentProof', file);
        formData.append('referenceNumber', referenceNumber);
        formData.append('reservation_code', '<?php echo $reservation['reservation_code']; ?>');
        // Add the column name for the reservation code
        formData.append('reservation_code_column', '<?php echo $reservation_code_column; ?>');

        try {
            console.log('Sending file:', file.name, 'Size:', file.size, 'Type:', file.type);
            console.log('Reference number:', referenceNumber);
            
            const response = await fetch('process_billing.php', {
                method: 'POST',
                body: formData
            });
            
            console.log('Response status:', response.status);
            
            const responseText = await response.text();
            console.log('Raw response:', responseText);

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                showStatus('Server returned invalid data. Please contact support.', false);
                return;
            }

            if (data.success) {
                showStatus(data.message, true);
                setTimeout(() => {
                    window.location.href = 'confirmation.php';
                }, 2000);
            } else {
                showStatus(data.message || 'Unknown error occurred', false);
            }
        } catch (error) {
            console.error('Fetch error:', error);
            showStatus('Network error: ' + error.message + '. Please check your connection.', false);
        }
    });

    function showStatus(message, isSuccess) {
        statusMessage.textContent = message;
        statusMessage.className = isSuccess ? 'success' : 'error';
    }
</script>
</body>
</html>