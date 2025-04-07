<?php
// Initialize error messages array
$errorMessages = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate First Name
    if (empty($_POST["first_name"])) {
        $errorMessages[] = "First Name is required";
    }

    // Validate Last Name
    if (empty($_POST["last_name"])) {
        $errorMessages[] = "Last Name is required";
    }

    // Validate Email
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errorMessages[] = "Valid email is required";
    }

    // Validate Contact Number
    if (empty($_POST['contact_number'])) {
        $errorMessages[] = "Contact number is required.";
    }

    // Remove any non-numeric characters (optional)
    $contact_number = preg_replace("/[^0-9]/", "", $_POST['contact_number']);

    // Check if the contact number is valid (e.g., length, format)
    if (strlen($contact_number) < 10 || strlen($contact_number) > 15) {
        $errorMessages[] = "Contact number must be between 10 and 15 digits.";
    }

    // If you want to check for specific formats (e.g., starting with a country code)
    if (!preg_match("/^\+?[0-9]\d{1,14}$/", $contact_number)) {
        $errorMessages[] = "Invalid contact number format.";
    }

    // Validate Password
    if (empty($_POST['password'])) {
        $errorMessages[] = "Please input password";
    } elseif (strlen($_POST['password']) < 8) {
        $errorMessages[] = "Password too short";
    } elseif (!preg_match('/[a-z]/i', $_POST['password'])) {
        $errorMessages[] = "Password must contain at least one letter";
    } elseif (!preg_match('/[0-9]/', $_POST['password'])) {
        $errorMessages[] = "Password must contain at least one number";
    } elseif ($_POST['password'] !== $_POST['password_confirmation']) {
        $errorMessages[] = "Passwords don't match";    
    }

    // If there are any error messages, display them and stop further execution
    if (count($errorMessages) > 0) {
        foreach ($errorMessages as $message) {
            echo $message . "<br>";
        }
        exit; // Stop further execution if there are validation errors
    }

    // If no validation errors, proceed with registration
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $activation_token = bin2hex(random_bytes(16));
    $activation_token_hash = hash("sha256", $activation_token);   

    $mysqli = require __DIR__ . "/database.php";

    // Check if the email already exists
    $checkEmailQuery = $mysqli->prepare("SELECT * FROM user WHERE email = ?");
    $checkEmailQuery->bind_param("s", $_POST['email']);
    $checkEmailQuery->execute();
    $result = $checkEmailQuery->get_result();

    if ($result->num_rows > 0) {
        echo "This email is already registered.";
        exit;
    } else {
        // Insert new user
        $sql = "INSERT INTO user (first_name, last_name, email, contact_number, password_hash, account_activation_hash) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            echo "SQL error: " . $mysqli->error;
            exit;
        }

        // Bind parameters including contact number
        $stmt->bind_param("ssssss", $_POST['first_name'], $_POST['last_name'], $_POST['email'], $contact_number, $password_hash, $activation_token_hash);
        
        if ($stmt->execute()) {
            // Check if the user was inserted
            if ($mysqli->affected_rows > 0) {
                $mail = require __DIR__ . "/mailer.php";
                $mail->setFrom("noreply@example.com");
                $mail->addAddress($_POST["email"]);
                $mail->Subject = "Account Activation";
            
                // Correctly embed the token in the email body
                $resetLink = "http://localhost/rainbow/activate-account.php?token=" . urlencode($activation_token);
                $mail->Body = <<<END
                Click <a href="$resetLink">here</a> to activate your account.
                END;
            
                try {
                    $mail->send();
                    echo "Message sent, please check your inbox.";
                } catch (Exception $e) {
                    echo "Message can't be sent. Mailer error: {$mail->ErrorInfo}";
                    exit;
                }
                
                // After everything is successful, redirect to success page
                header("Location: signup-success.html");
                exit;
            } else {
                echo "Error: " . $stmt->error;
                exit;
            }
        }
        $checkEmailQuery->close();
    }
}
?>
