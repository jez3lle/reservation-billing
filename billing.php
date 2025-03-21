<?php
session_start();

// Check if reservation details exist in session
if (!isset($_SESSION['guest_reservation'])) {
    // Redirect to reservation page if no reservation data is found
    header("Location: guest_reservation.php");
    exit;
}

// Get reservation details from session
$reservation = $_SESSION['guest_reservation'];

// Get the database connection
$mysqli = require 'database.php';

// Fetch reservation details from database to get complete information
$stmt = $mysqli->prepare("SELECT * FROM guest_reservations WHERE reservation_code = ?");
$stmt->bind_param("s", $reservation['reservation_code']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Reservation not found in database
    $_SESSION['reservation_error'] = "Reservation details not found. Please try again.";
    header("Location: guest_reservation.php");
    exit;
}

$reservationData = $result->fetch_assoc();

// Calculate number of nights
$check_in = new DateTime($reservationData['check_in_date']);
$check_out = new DateTime($reservationData['check_out_date']);
$nights = $check_in->diff($check_out)->days;

// Calculate total number of guests
$total_guests = $reservationData['guest_adult'] + $reservationData['guest_kid'];

// Set base rate and additional person rate
$base_rate = 20000; // Base rate for up to 20 people per night
$base_capacity = 20; // Maximum number of people included in base rate
$additional_person_rate = 1000; // Rate per additional person over base capacity

// Calculate additional guests cost (if any)
$additional_guests = max(0, $total_guests - $base_capacity);
$additional_guests_cost = $additional_guests * $additional_person_rate * $nights;

// Calculate base cost
$base_cost = $base_rate * $nights;

// Calculate subtotal
$subtotal = $base_cost + $additional_guests_cost;

// Calculate tax (e.g., 12%)
$tax_rate = 0.12;
$tax_amount = $subtotal * $tax_rate;

// Calculate total
$total = $subtotal + $tax_amount;

// Process payment form if submitted
$payment_success = false;
$payment_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get payment method
    $payment_method = filter_input(INPUT_POST, "payment_method", FILTER_SANITIZE_SPECIAL_CHARS);
    
    if (empty($payment_method)) {
        $payment_error = "Please select a payment method";
    } else {
        // Additional form validation based on payment method
        $valid = true;
        
        switch ($payment_method) {
            case 'credit_card':
                $card_number = filter_input(INPUT_POST, "card_number", FILTER_SANITIZE_NUMBER_INT);
                $card_expiry = filter_input(INPUT_POST, "card_expiry", FILTER_SANITIZE_SPECIAL_CHARS);
                $card_cvv = filter_input(INPUT_POST, "card_cvv", FILTER_SANITIZE_NUMBER_INT);
                $card_name = filter_input(INPUT_POST, "card_name", FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (empty($card_number) || empty($card_expiry) || empty($card_cvv) || empty($card_name)) {
                    $payment_error = "All credit card fields are required";
                    $valid = false;
                }
                break;
                
            case 'gcash':
                $gcash_number = filter_input(INPUT_POST, "gcash_number", FILTER_SANITIZE_NUMBER_INT);
                
                if (empty($gcash_number)) {
                    $payment_error = "GCash number is required";
                    $valid = false;
                }
                break;
                
            case 'bank_transfer':
                $reference_number = filter_input(INPUT_POST, "reference_number", FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (empty($reference_number)) {
                    $payment_error = "Reference number is required";
                    $valid = false;
                }
                break;
                
            case 'cash':
                // No additional validation needed for cash
                break;
                
            default:
                $payment_error = "Invalid payment method";
                $valid = false;
                break;
        }
        
        if ($valid) {
            // In a real application, you would process the payment here
            // For this example, we'll just simulate success
            
            // Update reservation status to confirmed
            $update_stmt = $mysqli->prepare("UPDATE guest_reservations SET status = 'confirmed', payment_amount = ?, payment_method = ? WHERE reservation_code = ?");
            $update_stmt->bind_param("dss", $total, $payment_method, $reservation['reservation_code']);
            
            if ($update_stmt->execute()) {
                $payment_success = true;
                
                // Store payment confirmation in session
                $_SESSION['payment_confirmation'] = [
                    'reservation_code' => $reservation['reservation_code'],
                    'amount' => $total,
                    'payment_method' => $payment_method,
                    'transaction_id' => 'TXN-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10)),
                    'date' => date('Y-m-d H:i:s')
                ];
                
                // Redirect to confirmation page
                header("Location: guest_confirmation.php");
                exit;
            } else {
                $payment_error = "Failed to update reservation status. Please try again.";
            }
            
            $update_stmt->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Information</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .payment-option {
            display: none;
        }
        .payment-method-selector {
            cursor: pointer;
            border: 2px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }
        .payment-method-selector:hover {
            border-color: #6c757d;
        }
        .payment-method-selector.selected {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
        .payment-icon {
            font-size: 24px;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Reservation Billing</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($payment_error) && !empty($payment_error)): ?>
                            <div class="alert alert-danger"><?php echo $payment_error; ?></div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <h4>Reservation Details</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Reservation Code:</th>
                                    <td><?php echo htmlspecialchars($reservation['reservation_code']); ?></td>
                                </tr>
                                <tr>
                                    <th>Guest Name:</th>
                                    <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Check-in Date:</th>
                                    <td><?php echo htmlspecialchars($check_in->format('F d, Y')); ?></td>
                                </tr>
                                <tr>
                                    <th>Check-out Date:</th>
                                    <td><?php echo htmlspecialchars($check_out->format('F d, Y')); ?></td>
                                </tr>
                                <tr>
                                    <th>Number of Nights:</th>
                                    <td><?php echo $nights; ?></td>
                                </tr>
                                <tr>
                                    <th>Number of Adults:</th>
                                    <td><?php echo $reservationData['guest_adult']; ?></td>
                                </tr>
                                <tr>
                                    <th>Number of Children:</th>
                                    <td><?php echo $reservationData['guest_kid']; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Guests:</th>
                                    <td><?php echo $total_guests; ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="mb-4">
                            <h4>Billing Summary</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Base Rate:</th>
                                    <td>₱<?php echo number_format($base_rate, 2); ?> per night (includes up to <?php echo $base_capacity; ?> guests)</td>
                                </tr>
                                <tr>
                                    <th>Base Cost (<?php echo $nights; ?> nights):</th>
                                    <td>₱<?php echo number_format($base_cost, 2); ?></td>
                                </tr>
                                <?php if ($additional_guests > 0): ?>
                                <tr>
                                    <th>Additional Guests:</th>
                                    <td><?php echo $additional_guests; ?> person(s) × ₱<?php echo number_format($additional_person_rate, 2); ?> × <?php echo $nights; ?> nights</td>
                                </tr>
                                <tr>
                                    <th>Additional Guests Cost:</th>
                                    <td>₱<?php echo number_format($additional_guests_cost, 2); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Subtotal:</th>
                                    <td>₱<?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Tax (12%):</th>
                                    <td>₱<?php echo number_format($tax_amount, 2); ?></td>
                                </tr>
                                <tr class="table-primary">
                                    <th>Total Amount:</th>
                                    <td><strong>₱<?php echo number_format($total, 2); ?></strong></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="mb-3">
                            <h4>Payment Options</h4>
                            <form method="post" action="" id="paymentForm">
                                <input type="hidden" name="payment_method" id="payment_method" value="">
                                
                                <!-- Payment Method Selection -->
                                <div class="mb-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-method-selector" data-method="credit_card">
                                                <i class="bi bi-credit-card payment-icon"></i>
                                                <strong>Credit/Debit Card</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-method-selector" data-method="gcash">
                                                <i class="bi bi-wallet payment-icon"></i>
                                                <strong>GCash</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-method-selector" data-method="bank_transfer">
                                                <i class="bi bi-bank payment-icon"></i>
                                                <strong>Bank Transfer</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-method-selector" data-method="cash">
                                                <i class="bi bi-cash payment-icon"></i>
                                                <strong>Cash on Arrival</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Credit Card Payment Details -->
                                <div id="credit_card_details" class="payment-option">
                                    <h5 class="mb-3">Credit/Debit Card Details</h5>
                                    <div class="mb-3">
                                        <label for="card_name" class="form-label">Name on Card</label>
                                        <input type="text" class="form-control" id="card_name" name="card_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="card_number" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="card_expiry" class="form-label">Expiration Date</label>
                                            <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YY">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="card_cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="123">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- GCash Payment Details -->
                                <div id="gcash_details" class="payment-option">
                                    <h5 class="mb-3">GCash Details</h5>
                                    <div class="mb-3">
                                        <p>Please use the following information to make your GCash payment:</p>
                                        <p><strong>GCash Number:</strong> 09XXXXXXXXX</p>
                                        <p><strong>Account Name:</strong> Resort Name</p>
                                        <p>Please include your reservation code <strong><?php echo $reservation['reservation_code']; ?></strong> in the reference/notes.</p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gcash_number" class="form-label">Your GCash Number</label>
                                        <input type="text" class="form-control" id="gcash_number" name="gcash_number" placeholder="09XXXXXXXXX">
                                    </div>
                                </div>
                                
                                <!-- Bank Transfer Details -->
                                <div id="bank_transfer_details" class="payment-option">
                                    <h5 class="mb-3">Bank Transfer Details</h5>
                                    <div class="mb-3">
                                        <p>Please transfer the payment to the following bank account:</p>
                                        <p><strong>Bank Name:</strong> Sample Bank</p>
                                        <p><strong>Account Number:</strong> 1234567890</p>
                                        <p><strong>Account Name:</strong> Resort Name</p>
                                        <p>Please include your reservation code <strong><?php echo $reservation['reservation_code']; ?></strong> in the reference/notes.</p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="reference_number" class="form-label">Reference/Transaction Number</label>
                                        <input type="text" class="form-control" id="reference_number" name="reference_number">
                                    </div>
                                </div>
                                
                                <!-- Cash Payment Details -->
                                <div id="cash_details" class="payment-option">
                                    <h5 class="mb-3">Cash Payment on Arrival</h5>
                                    <div class="alert alert-info">
                                        <p>You have selected to pay cash upon arrival. Please note:</p>
                                        <ul>
                                            <li>Payment must be made in full at check-in</li>
                                            <li>We accept Philippine Peso (₱) only</li>
                                            <li>Your reservation will be held until your scheduled check-in time</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary" id="completePaymentBtn">Complete Reservation</button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="guest_reservation.php" class="btn btn-outline-secondary">Back to Reservation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Payment method selection
            const paymentSelectors = document.querySelectorAll('.payment-method-selector');
            const paymentMethodInput = document.getElementById('payment_method');
            const completePaymentBtn = document.getElementById('completePaymentBtn');
            
            paymentSelectors.forEach(selector => {
                selector.addEventListener('click', function() {
                    // Remove selected class from all options
                    paymentSelectors.forEach(s => s.classList.remove('selected'));
                    
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    
                    // Get the payment method
                    const method = this.getAttribute('data-method');
                    
                    // Set the hidden input value
                    paymentMethodInput.value = method;
                    
                    // Hide all payment option details
                    const paymentOptions = document.querySelectorAll('.payment-option');
                    paymentOptions.forEach(option => {
                        option.style.display = 'none';
                    });
                    
                    // Show the selected payment option details
                    const selectedOption = document.getElementById(method + '_details');
                    if (selectedOption) {
                        selectedOption.style.display = 'block';
                    }
                    
                    // Update button text based on payment method
                    if (method === 'cash') {
                        completePaymentBtn.textContent = 'Reserve Now, Pay Later';
                    } else {
                        completePaymentBtn.textContent = 'Complete Payment';
                    }
                });
            });
            
            // Form validation
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                const method = paymentMethodInput.value;
                
                if (!method) {
                    e.preventDefault();
                    alert('Please select a payment method');
                    return;
                }
                
                // Additional validation based on payment method
                switch (method) {
                    case 'credit_card':
                        const cardName = document.getElementById('card_name').value;
                        const cardNumber = document.getElementById('card_number').value;
                        const cardExpiry = document.getElementById('card_expiry').value;
                        const cardCvv = document.getElementById('card_cvv').value;
                        
                        if (!cardName || !cardNumber || !cardExpiry || !cardCvv) {
                            e.preventDefault();
                            alert('Please fill in all credit card details');
                        }
                        break;
                        
                    case 'gcash':
                        const gcashNumber = document.getElementById('gcash_number').value;
                        
                        if (!gcashNumber) {
                            e.preventDefault();
                            alert('Please enter your GCash number');
                        }
                        break;
                        
                    case 'bank_transfer':
                        const referenceNumber = document.getElementById('reference_number').value;
                        
                        if (!referenceNumber) {
                            e.preventDefault();
                            alert('Please enter the reference/transaction number');
                        }
                        break;
                }
            });
        });
    </script>
</body>
</html>