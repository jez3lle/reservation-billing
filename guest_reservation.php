<?php
session_start(); // Start the session at the beginning

// Store check-in/check-out dates if redirected from booking page
if (isset($_GET['check_in']) && isset($_GET['check_out'])) {
    $_SESSION['check_in'] = $_GET['check_in'];
    $_SESSION['check_out'] = $_GET['check_out'];
}

// No user authentication check since this is for guests
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Reservation Form - Rainbow Forest Paradise</title>
    <link rel="stylesheet" href="reservation.css">
</head>
<body>
    <div class="container">
        <h2>Guest Reservation Form</h2>
        <p>Complete the form below to make your reservation.</p>
        
        <form action="guest_reservation_process.php" method="POST" class="reservation-form" id="reservation-form">
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
                    <input type="text" name="first_name" id="first_name">
                    <div id="first_name-error" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="last_name" id="last_name">
                    <div id="last_name-error" class="error-message"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" id="email" required>
                    <div id="email-error" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="text" name="contact_number" id="contact_number">
                    <div id="contact_number-error" class="error-message"></div>
                </div>
            </div>
            
            <!-- Additional guest-specific fields -->
            <div class="form-row">
                <div class="form-group">
                    <label>Number of Guests(Adults):</label>
                    <input type="number" name="guest_adult" id="guest_adult" min="1" required>
                    <div id="guest_count-error" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Number of Guests(Kids):</label>
                    <input type="number" name="guest_kid" id="guest_kid" min="1" required>
                    <div id="guest_count-error" class="error-message"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-row">
                    <label>Special Requests:</label>
                    <textarea name="special_requests" id="special_requests" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <label>Add-Ons:</label>
                    <textarea name="special_requests" id="special_requests" placeholder="Pillows, Mattress, etc." rows="3"></textarea>
                </div>        
            </div>
            
            <p class="note">Note: A confirmation email will be sent to your provided email address.</p>
            
            <div class="form-row actions">
                <a href="home_p1.php" class="btn btn-secondary">Back to Home</a>
                <button type="submit" class="btn">Submit Reservation</button>
            </div>
        </form>
    </div>
  
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("reservation-form");

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
            
            const guestCount = document.getElementById("guest_count").value.trim();
            if (guestCount === "" || parseInt(guestCount) < 1) {
                errors.guest_count = "Valid number of guests is required";
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