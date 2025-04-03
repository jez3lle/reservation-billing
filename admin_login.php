<?php
session_start();

// Check if admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

// Process login form
$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get database connection
    $mysqli = require 'database.php';
    if ($mysqli->connect_error) {
        die("Database connection failed: " . $mysqli->connect_error);
    }

    // Get form data
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST["password"];

    // Validate input
    if (empty($username) || empty($password)) {
        $login_error = "All fields are required";
    } else {
        // Check if username exists
        $stmt = $mysqli->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $admin["password_hash"])) {
                session_regenerate_id();
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $login_error = "Invalid login credentials";
            }
        } else {
            $login_error = "Invalid login credentials";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Rainbow Forest Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="mystyle.css">
    <style>
          body {
            background-color: #eaf2e3; 
        }
       
        .login-container {
            max-width: 500px;
            margin: 80px auto;
        }
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 12px;
        }
        .card-header {
            background-color: #508E87; /* Dark green */
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .resort-name {
            font-size: 22px;
            font-weight: bold;
            line-height: 1.3;
        }
        .admin-login-text {
            font-size:18px;
            margin-top: 2px;
            opacity: 0.9;
        }
        .input-group-text {
            background-color: #3b6d3b;
            color: white;
            border: none;
        }
        .btn-primary {
            background-color: #508E87;
            border: none;
        }
        .btn-primary:hover {
            background-color: #295629; /* Darker green */
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card">
            <div class="card-header">
                <h4 class="resort-name">Rainbow Forest Paradise<br>Resort & Campsite</h4>
                <p class="admin-login-text">Admin Login</p>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($login_error)): ?>
                    <div class="alert alert-danger"><?php echo $login_error; ?></div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
