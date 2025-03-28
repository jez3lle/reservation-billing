<?php
session_start(); // Start the session at the beginning

// Check if user is logged in - make sure the session variable matches what your login script sets
if (!isset($_SESSION["user_id"])) {
    // Store the current URL in the session to redirect back after login
    $_SESSION["redirect_after_login"] = $_SERVER["REQUEST_URI"];
    
    // Make sure this path is correct relative to this file
    header("Location: login.php"); // or the correct path to your login page
    exit;
}

// Get the database connection
$mysqli = require 'database.php';

// Fetch user data using the user_id in the session
$stmt = $mysqli->prepare("SELECT first_name, last_name, email, contact_number FROM user WHERE id = ?");
if ($stmt === false) {
    die("Error preparing the query: " . $mysqli->error);
}

$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Now $user contains the personal information

// Check if user was found
if (!$user) {
    // User ID in session doesn't match any user in database
    session_destroy(); // Clear the invalid session
    header("Location: login.php"); // Redirect to login
    exit;
}

// Store check-in/check-out dates if redirected from booking page
if (isset($_GET['check_in']) && isset($_GET['check_out'])) {
    $_SESSION['check_in'] = $_GET['check_in'];
    $_SESSION['check_out'] = $_GET['check_out'];
}

$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form</title>
    <link rel="stylesheet" href="reservation.css">
    <style>
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    line-height: 1.6;
    background-color: #f4f4f4;
    color: #333;
}

/* Container */
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
    overflow: visible; /* Ensure content isn't hidden */
}

/* Page Header */
.page-header {
    background-color: #2c7a57;
    color: white;
    text-align: center;
    padding: 40px 15px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    width: 100%; /* Ensure full width */
    box-sizing: border-box; /* Include padding in width calculation */
}

.page-header h1 {
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: 15px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.page-header p {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.85);
    max-width: 800px;
    margin: 0 auto;
}

/* Content Wrapper */
.content-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    margin-top: 30px;
}

/* Form Container */
.form-container {
    flex: 1;
    min-width: 300px;
}

.reservation-form {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

/* Form Rows and Groups */
.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    flex: 1;
    min-width: 220px;
}

/* Labels */
label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #2c3e50;
}

/* Input Styles */
input[type="text"],
input[type="email"],
input[type="number"],
input[type="date"],
select,
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #d1d8e0;
    border-radius: 6px;
    font-size: 16px;
    transition: all 0.3s ease;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #2c7a57;
    outline: none;
    box-shadow: 0 0 0 3px rgba(44, 122, 87, 0.15);
}

textarea {
    resize: vertical;
    min-height: 100px;
}

/* Pricing Container */
.pricing-container {
    flex: 0 0 350px;
    background-color: #f0f9ff;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.08);
    align-self: flex-start;
    position: sticky;
    top: 20px;
}

.pricing-container h3 {
    color: #0369a1;
    margin-bottom: 20px;
    text-align: center;
    border-bottom: 2px solid #bae6fd;
    padding-bottom: 10px;
}

/* Tour Pricing Tables */
.tour-pricing {
    margin-bottom: 25px;
}

.tour-pricing h4 {
    color: #0c4a6e;
    margin-bottom: 10px;
}

.pricing-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

.pricing-table th,
.pricing-table td {
    border: 1px solid #cbd5e1;
    padding: 10px;
    text-align: center;
}

.pricing-table th {
    background-color: #e0f2fe;
    color: #0c4a6e;
    font-weight: bold;
}

/* Bill Calculation */
.bill-calculation {
    background-color: #e0f7fa;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}

.bill-calculation h4 {
    color: #0369a1;
    margin-bottom: 15px;
    text-align: center;
}

.bill-calculation p {
    margin: 10px 0;
    font-size: 16px;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 14px 28px;
    border: none;
    border-radius: 6px;
    background-color: #2c7a57;
    color: white;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-decoration: none;
}

.btn:hover {
    background-color: #1d5a3f;
}

.btn-secondary {
    background-color: #64748b;
}

.btn-secondary:hover {
    background-color: #475569;
}

.actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}

/* Extras Selection */
.extras-selection {
    background-color: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 25px;
    margin-top: 25px;
}

.extras-selection h4 {
    color: #0369a1;
    margin-bottom: 20px;
    text-align: center;
    border-bottom: 2px solid #bae6fd;
    padding-bottom: 10px;
}

.extras-selection .form-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 12px;
    background-color: white;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.extras-selection .form-row:hover {
    background-color: #f0f9ff;
    transition: background-color 0.3s ease;
}

.extras-selection label {
    margin-bottom: 0;
    font-weight: 500;
    color: #0c4a6e;
    flex-grow: 1;
}

.extras-selection .extra-price {
    color: #64748b;
    margin-right: 15px;
    font-size: 0.9rem;
}

.extras-selection input[type="number"] {
    width: 80px;
    padding: 8px;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    text-align: center;
}

/* Error Messages */
.error-message {
    color: #dc2626;
    font-size: 14px;
    margin-top: 5px;
}

.note {
    font-style: italic;
    color: #64748b;
    margin-top: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 2.2rem;
    }

    .content-wrapper {
        flex-direction: column;
    }

    .form-container,
    .pricing-container {
        width: 100%;
        max-width: 100%;
        flex: none;
    }

    .form-row {
        flex-direction: column;
    }

    .form-group {
        min-width: 100%;
    }

    .actions {
        flex-direction: column;
        gap: 15px;
    }

    .btn {
        width: 100%;
    }
}
    </style>
</head>
<body>
<div class="container">
    <!-- Full Page Header -->
    <div class="page-header">
        <h1>Comprehensive Guided Tour Reservation Form and Booking Details</h1>
        <div class="subtitle">
            Complete your tour reservation by providing accurate personal and group information, 
            selecting your preferred tour type, and choosing additional extras
        </div>
    </div>
    <div class="content-wrapper">
        <div class="form-container">
            <form action="reservation_process.php" method="POST" class="reservation-form" id="reservation-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Check-in Date:</label>
                        <input type="date" name="check_in" required value="<?php echo isset($_SESSION['check_in']) ? htmlspecialchars($_SESSION['check_in']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Check-out Date:</label>
                        <input type="date" name="check_out" required value="<?php echo isset($_SESSION['check_out']) ? htmlspecialchars($_SESSION['check_out']) : ''; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name:</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                        <div id="first_name-error" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label>Last Name:</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                        <div id="last_name-error" class="error-message"></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                        <div id="email-error" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="text" name="contact_number" id="contact_number" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>">
                        <div id="contact_number-error" class="error-message"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Number of Guests(Adults):</label>
                        <input type="number" name="adult_count" id="adult_count" min="1" required>
                        <div id="adult_count-error" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label>Number of Guests(Kids):</label>
                        <input type="number" name="kid_count" id="kid_count" min="0">
                        <div id="kid_count-error" class="error-message"></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tour Type:</label>
                        <select name="tour_type" id="tour_type" required>
                            <option value="">Select Tour Type</option>
                            <option value="whole_day">Whole Day</option>
                            <option value="day_tour">Day Tour</option>
                            <option value="night_tour">Night Tour</option>
                        </select>
                        <div id="tour_type-error" class="error-message"></div>
                    </div>
                </div>

                <!-- Extras selection section (moved below bill calculation) -->
                <div class="extras-selection">
                    <h4>Additional Extras</h4>
                    <div class="form-row">
                        <label>Extra Mattress (₱150 each)</label>
                        <input type="number" id="extra-mattress" name="extra_mattress" min="0" value="0">
                    </div>
                    <div class="form-row">
                        <label>Extra Pillow (₱50 each)</label>
                        <input type="number" id="extra-pillow" name="extra_pillow" min="0" value="0">
                    </div>
                    <div class="form-row">
                        <label>Extra Blanket (₱50 each)</label>
                        <input type="number" id="extra-blanket" name="extra_blanket" min="0" value="0">
                    </div>
                </div> 
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Special Requests:</label>
                        <textarea name="special_requests" id="special_requests" rows="3"></textarea>
                    </div>
                </div>
                
                <p class="note">Note: A confirmation email will be sent to your provided email address.</p>
                
                <div class="form-row actions">
                    <a href="home_p1.php" class="btn btn-secondary">Back to Home</a>
                    <button type="submit" class="btn">Submit Reservation</button>
                </div>
            </form>
        </div>
        
        <!-- Pricing information sidebar -->
        <div class="pricing-container" id="pricing-container">
            <h3>Tour Pricing Information</h3>
            
            <!-- Whole Day Tour pricing -->
            <div id="whole_day-pricing" class="tour-pricing">
                <h4>Whole Day Tour</h4>
                <p>Time: 9:00 AM to 7:00 AM (next day)</p>
                <p>or 8:00 PM to 6:00 PM (next day)</p>
                <table class="pricing-table">
                    <tr>
                        <th>Number of Guests</th>
                        <th>Price (PHP)</th>
                    </tr>
                    <tr>
                        <td>1-10 persons</td>
                        <td>₱12,000</td>
                    </tr>
                    <tr>
                        <td>11-15 persons</td>
                        <td>₱13,000</td>
                    </tr>
                    <tr>
                        <td>16-20 persons</td>
                        <td>₱15,000</td>
                    </tr>
                    <tr>
                        <td>21-25 persons</td>
                        <td>₱16,000</td>
                    </tr>
                    <tr>
                        <td>26-30 persons</td>
                        <td>₱18,000</td>
                    </tr>
                    <tr>
                        <td colspan="2">Additional ₱600 per person beyond 30</td>
                    </tr>
                </table>
            </div>
            
            <!-- Day Tour pricing -->
            <div id="day_tour-pricing" class="tour-pricing">
                <h4>Day Tour</h4>
                <p>Time: 9:00 AM to 6:00 PM</p>
                <table class="pricing-table">
                    <tr>
                        <th>Number of Guests</th>
                        <th>Price (PHP)</th>
                    </tr>
                    <tr>
                        <td>1-10 persons</td>
                        <td>₱7,000</td>
                    </tr>
                    <tr>
                        <td>11-15 persons</td>
                        <td>₱8,000</td>
                    </tr>
                    <tr>
                        <td>16-20 persons</td>
                        <td>₱9,000</td>
                    </tr>
                    <tr>
                        <td>21-25 persons</td>
                        <td>₱10,000</td>
                    </tr>
                    <tr>
                        <td>26-30 persons</td>
                        <td>₱11,000</td>
                    </tr>
                    <tr>
                        <td colspan="2">Additional ₱400 per person beyond 30</td>
                    </tr>
                </table>
            </div>
            
            <!-- Night Tour pricing -->
            <div id="night_tour-pricing" class="tour-pricing">
                <h4>Night Tour</h4>
                <p>Time: 8:00 PM to 7:00 AM (next day)</p>
                <table class="pricing-table">
                    <tr>
                        <th>Number of Guests</th>
                        <th>Price (PHP)</th>
                    </tr>
                    <tr>
                        <td>1-10 persons</td>
                        <td>₱8,000</td>
                    </tr>
                    <tr>
                        <td>11-15 persons</td>
                        <td>₱9,000</td>
                    </tr>
                    <tr>
                        <td>16-20 persons</td>
                        <td>₱10,000</td>
                    </tr>
                    <tr>
                        <td>21-25 persons</td>
                        <td>₱11,000</td>
                    </tr>
                    <tr>
                        <td>26-30 persons</td>
                        <td>₱12,000</td>
                    </tr>
                    <tr>
                        <td colspan="2">Additional ₱500 per person beyond 30</td>
                    </tr>
                </table>
            </div>
            
            <!-- Bill calculation section -->
            <div class="bill-calculation">
                <h4>Your Estimated Bill</h4>
                <p id="total-guests">Total Guests: 0</p>
                <p id="tour-type-display">Tour Type: Not selected</p>
                <p id="estimated-price">Estimated Price: ₱0</p>
            </div>
        </div>
    </div>
</div>
  
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("reservation-form");
    const adultCountInput = document.getElementById("adult_count");
    const kidCountInput = document.getElementById("kid_count");
    const tourTypeSelect = document.getElementById("tour_type");
    const pricingContainer = document.getElementById("pricing-container");
    
    // Elements for bill calculation
    const totalGuestsElement = document.getElementById("total-guests");
    const tourTypeElement = document.getElementById("tour-type-display");
    const estimatedPriceElement = document.getElementById("estimated-price");
    
    // Extras inputs
    const extraMattressInput = document.getElementById("extra-mattress");
    const extraPillowInput = document.getElementById("extra-pillow");
    const extraBlanketInput = document.getElementById("extra-blanket");
    
    // Pricing data
    const pricingData = {
        whole_day: {
            brackets: [
                { max: 10, price: 12000 },
                { max: 15, price: 13000 },
                { max: 20, price: 15000 },
                { max: 25, price: 16000 },
                { max: 30, price: 18000 }
            ],
            additionalPerPerson: 600,
            displayName: "Whole Day"
        },
        day_tour: {
            brackets: [
                { max: 10, price: 7000 },
                { max: 15, price: 8000 },
                { max: 20, price: 9000 },
                { max: 25, price: 10000 },
                { max: 30, price: 11000 }
            ],
            additionalPerPerson: 400,
            displayName: "Day Tour"
        },
        night_tour: {
            brackets: [
                { max: 10, price: 8000 },
                { max: 15, price: 9000 },
                { max: 20, price: 10000 },
                { max: 25, price: 11000 },
                { max: 30, price: 12000 }
            ],
            additionalPerPerson: 500,
            displayName: "Night Tour"
        }
    };

    // Show pricing table based on selected tour type
    function showPricingTable(tourType) {
        // Hide all pricing tables first
        document.querySelectorAll('.tour-pricing').forEach(table => {
            table.style.display = 'none';
        });
        
        // Always show the pricing container
        pricingContainer.style.display = 'block';
        
        // Show the relevant pricing table if a tour type is selected
        if (tourType) {
            const selectedTable = document.getElementById(`${tourType}-pricing`);
            if (selectedTable) {
                selectedTable.style.display = 'block';
            }
        }
    }

    // Calculate price based on tour type and guest count
    function calculatePrice(tourType, totalGuests) {
        if (!pricingData[tourType]) {
            return 0;
        }
        
        const pricing = pricingData[tourType];
        
        // Find the appropriate price bracket
        let basePrice = 0;
        for (const bracket of pricing.brackets) {
            if (totalGuests <= bracket.max) {
                basePrice = bracket.price;
                break;
            }
        }
        
        // If guests exceed the highest bracket
        if (basePrice === 0) {
            const lastBracket = pricing.brackets[pricing.brackets.length - 1];
            const extraGuests = totalGuests - 30;
            basePrice = lastBracket.price + (extraGuests * pricing.additionalPerPerson);
        }
        
        // Calculate extras price
        const extrasPrice = 
            (parseInt(extraMattressInput.value) * 150) +
            (parseInt(extraPillowInput.value) * 50) +
            (parseInt(extraBlanketInput.value) * 50);
        
        return basePrice + extrasPrice;
    }

    // Update bill calculation based on guest count
    function updateGuestInfo() {
        const adultCount = parseInt(adultCountInput.value) || 0;
        const kidCount = parseInt(kidCountInput.value) || 0;
        const totalGuests = adultCount + kidCount;
        const tourType = tourTypeSelect.value;
        
        // Show/hide pricing table based on tour type
        showPricingTable(tourType);
        
        // Update bill calculation
        totalGuestsElement.textContent = `Total Guests: ${totalGuests}`;
        
        if (tourType) {
            const estimatedPrice = calculatePrice(tourType, totalGuests);
            tourTypeElement.textContent = `Tour Type: ${pricingData[tourType].displayName}`;
            
            // Calculate extras details
            const extraMattress = parseInt(extraMattressInput.value);
            const extraPillow = parseInt(extraPillowInput.value);
            const extraBlanket = parseInt(extraBlanketInput.value);
            
            let extrasInfo = '';
            if (extraMattress > 0) extrasInfo += `${extraMattress} Extra Mattress(es), `;
            if (extraPillow > 0) extrasInfo += `${extraPillow} Extra Pillow(s), `;
            if (extraBlanket > 0) extrasInfo += `${extraBlanket} Extra Blanket(s)`;
            
            extrasInfo = extrasInfo.replace(/, $/, '');
            
            estimatedPriceElement.textContent = `Estimated Price: ₱${estimatedPrice.toLocaleString()}` + 
                (extrasInfo ? ` (${extrasInfo})` : '');
        } else {
            tourTypeElement.textContent = "Tour Type: Not selected";
            estimatedPriceElement.textContent = "Estimated Price: ₱0";
        }
    }
    
    // Add event listeners to update the guest info when inputs change
    adultCountInput.addEventListener("input", updateGuestInfo);
    kidCountInput.addEventListener("input", updateGuestInfo);
    tourTypeSelect.addEventListener("change", updateGuestInfo);
    extraMattressInput.addEventListener("input", updateGuestInfo);
    extraPillowInput.addEventListener("input", updateGuestInfo);
    extraBlanketInput.addEventListener("input", updateGuestInfo);
    
    // Initial hide of all pricing tables
    showPricingTable(null);
    
    // Initial check when the page loads
    updateGuestInfo();

    // Form validation
    form.addEventListener("submit", function(event) {
        let errors = {};

        const firstName = document.getElementById("first_name").value.trim();
        if (firstName === "") {
            errors.first_name = "First Name is required";
        }

        const lastName = document.getElementById("last_name").value.trim();
        if (lastName === "") {
            errors.last_name = "Last Name is required";
        }
        
        const email = document.getElementById("email").value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === "") {
            errors.email = "Email is required";
        } else if (!emailRegex.test(email)) {
            errors.email = "Invalid email format";
        }

        const contactNumber = document.getElementById("contact_number").value.trim();
        const contactNumberRegex = /^\+?[0-9]{10,15}$/;
        if (contactNumber === "") {
            errors.contact_number = "Contact number is required";
        } else if (!contactNumberRegex.test(contactNumber)) {
            errors.contact_number = "Invalid contact number format";
        }
        
        const tourType = document.getElementById("tour_type").value;
        if (tourType === "") {
            errors.tour_type = "Please select a tour type";
        }

        if (Object.keys(errors).length > 0) {
            event.preventDefault();
            document.querySelectorAll('.error-message').forEach(field => field.textContent = '');
            for (const [field, message] of Object.entries(errors)) {
                const errorElement = document.getElementById(`${field}-error`);
                if (errorElement) {
                    errorElement.textContent = message;
                }
            }
        }
    });
});
</script>
</body>
</html>