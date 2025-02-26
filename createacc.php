<?php
include("db_connect.php");

$message = "";
$account_created = false; // Flag to control form visibility

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $contactnum = trim($_POST['contactnum']);
    $password = trim($_POST['password']);

    $verify_query = $conn->prepare("SELECT Email FROM users WHERE Email = ?");
    $verify_query->bind_param("s", $email);
    $verify_query->execute();
    $verify_query->store_result();

    if ($verify_query->num_rows > 0) {
        $message = "<div class='error-message'><p>This email is already in use.</p></div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_query = $conn->prepare("INSERT INTO users (Email, Firstname, Lastname, Contactnum, Password) VALUES (?, ?, ?, ?, ?)");
        $insert_query->bind_param("sssss", $email, $firstname, $lastname, $contactnum, $hashed_password);

        if ($insert_query->execute()) {
            $message = "<div class='success-message'>
                            <h2>Account Created Successfully! </h2>
                            <p>You can now log in to your account.</p>
                            <a href='login.php' class='loginbtn'>Login Now</a>
                        </div>";
            $account_created = true; // Set flag to true, hiding the form
        } else {
            $message = "<div class='error-message'><p>Error: " . $conn->error . "</p></div>";
        }

        $insert_query->close();
    }

    $verify_query->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="design.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            background: #ffffff;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: flex;
            width: 900px;
            border: 5px solid #2a5d3e;
            height: 650px;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
        }
        .left-panel {
            width: 50%;
            background: url('images/ril.png') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 20px;
        }
        .left-panel h1 {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .left-panel p {
            font-size: 20px;
        }
        .right-panel {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .form-container {
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .form-container h2 {
            color: #2a5d3e;
            margin: 10px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 8px;
            transition: 0.3s;
        }
        .form-group input:focus {
            border-color: #2a5d3e;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #2a5d3e;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #3c7f59;
        }

        .loginbtn {
            width: 100%;
            margin-top: 20px;
            padding: 12px 18px;
            background-color: #2a5d3e;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .loginbtn:hover {
            background-color:rgb(133, 196, 160);
        }
        .links {
            text-align: center;
            margin-top: 10px;
        }
        .links p {
            color: #2a5d3e;
            margin-bottom: 10px;
        }

        .login-link {
            background-color: #2a5d3e; /* Background color */
            padding: 10px 30px; /* Padding for spacing */
            border-radius: 5px; /* Rounded edges */
            display: inline-block; /* Make it wrap the content */
            margin: 5px; /* Space between text and button */
            cursor: pointer;
            transition: 0.3s;
        }
        .login-link:hover {
            background-color: #3c7f59;
        }

        .login-link a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }


        .error-message, .success-message {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            margin-top: 10px;
            font-size: 14px;
        }
        .error-message {
            color: red;
            background: #ffe5e5;
        }
        .success-message {
            color: green;
            background: #eaffea;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left-panel">
        <h1>WELCOME!</h1>
        <p>Start your journey with us today.</p>
    </div>

    <div class="right-panel">
        <div class="form-container">

            <!-- Show message above form -->
            <?php echo $message; ?>

            <?php if (!$account_created): ?> 
                <h2>Create Account</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="email">Email Address*</label>
                        <input type="email" id="email" name="email" placeholder="Enter your Email Address" required>
                    </div>

                    <div class="form-group">
                        <label for="firstname">First Name*</label>
                        <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>
                    </div>

                    <div class="form-group">
                        <label for="lastname">Surname*</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>
                    </div>

                    <div class="form-group">
                        <label for="contactnum">Contact Number*</label>
                        <input type="text" id="contactnum" name="contactnum" placeholder="Enter contact number" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password*</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn">Sign Up</button>

                    <div class="links">
                        <p>Already have an account?</p>
                        <div class="login-link">
                            <a href="login.php">LOGIN</a>
                        </div>
                    </div>
                </form>
            <?php endif; ?> <!-- Correct placement of endif -->

        </div> <!-- Close form-container -->
    </div> <!-- Close right-panel -->
</div> <!-- Close container -->

</body>
</html>