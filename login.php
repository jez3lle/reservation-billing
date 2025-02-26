<?php
session_start();
include 'db_connect.php';

// Store check-in/check-out dates if redirected from booking page
if (isset($_GET['check_in']) && isset($_GET['check_out'])) {
    $_SESSION['check_in'] = $_GET['check_in'];
    $_SESSION['check_out'] = $_GET['check_out'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirect to reservation form if dates are stored in session
            if (isset($_SESSION['check_in']) && isset($_SESSION['check_out'])) {
                header("Location: reservation_form.php");
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with this email.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Resort & Campsite</title>
    <link rel="stylesheet" href="logincss.css">
</head>
<body>

    <div class="container">
        <div class="left-panel">
            <h1>Welcome to <br> Rainbow Forest Paradise Resort and Campsite</h1>
            <p>Your perfect escape into nature. Experience relaxation and adventure in one place.</p>
        </div>

        <div class="right-panel">
            <div class="login-container">
                <h2>Log In</h2>

                <?php 
                // Display error message
                if (!empty($error)) { 
                    echo "<div class='error-message'>$error</div>"; 
                }

                // Display success messages (Login Success / Logout)
                if (!empty($_SESSION['login_success']) || !empty($_SESSION['logout_message'])) { 
                    echo "<div class='success-message'>" . ($_SESSION['login_success'] ?? $_SESSION['logout_message']) . "</div>"; 
                    unset($_SESSION['login_success'], $_SESSION['logout_message']); // Remove both messages
                }
                ?>


                <form action="" method="post">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" name="email" id="email" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" autocomplete="off" required>
                    </div>
                    <button type="submit" class="btn">Login</button>
                </form>

                <div class="links">
                    <a href="createacc.php">Create New Account</a> | 
                    <a href="Resetpass.php">Forgot Password?</a>
                </div>
                <div class="logoutcontainer">
                    <a href="log-out.php" class="btn-logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
