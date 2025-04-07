<?php
session_start();

if (!isset($_SESSION['reservation_details'])) {
    echo "No reservation details found in session.";
    exit;
}

$reservation = $_SESSION['reservation_details'];
if (!isset($reservation['reservation_code'])) {
    // Create a unique reservation code
    // Format: YYYYMMDD-RANDOMSTRING
    $date = date('Ymd');
    $random_string = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
    $reservation_code = $date . '-' . $random_string;
    
    // Add reservation code to session
    $_SESSION['reservation_details']['reservation_code'] = $reservation_code;
}
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
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .billing-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .billing-header {
            text-align: center;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .reservation-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .billing-section {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .bank-details {
            background-color: #f0f0f0;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-top: 20px;
        }
        .payment-instructions {
            background-color: #e9ecef;
            border-radius: 5px;
            padding: 15px;
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
        .btn-secondary {
            background-color: #6c757d;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .upload-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        #imagePreview {
            max-width: 100%;
            max-height: 300px;
            margin: 20px 0;
            display: none;
        }
        #fileInput {
            display: none;
        }
        .custom-file-upload {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
        }
        #referenceNumberInput {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #uploadButton {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        #uploadButton:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        #statusMessage {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
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
<div class="billing-container">
    <div class="billing-header">
        <h1>Reservation Billing</h1>
        <p>Rainbow Forest Paradise Resort and Campsite</p>
    </div>
    
    <div class="reservation-code">
        Reservation Code: <?php echo htmlspecialchars($reservation['reservation_code']); ?>
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

        <div class="upload-container">
        <h2>Upload Payment Proof</h2>
        <p>Please upload your bank transfer receipt and enter the reference number.</p>

        <input type="text" id="referenceNumberInput" placeholder="Enter Reference Number">

        <input type="file" id="fileInput" accept="image/*" capture="environment">
        <label for="fileInput" class="custom-file-upload">
            Select Payment Proof Image
        </label>

        <img id="imagePreview" src="#" alt="Image Preview">

        <button id="uploadButton" disabled>Upload Payment Proof</button>

        <div id="statusMessage"></div>
    </div>

    <script>
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

            try {
                console.log('Sending file:', file.name, 'Size:', file.size, 'Type:', file.type);
                console.log('Reference number:', referenceNumber);
                
                const response = await fetch('process_payment.php', {
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
</div>
</body>
</html>