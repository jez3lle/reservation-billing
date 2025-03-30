<?php
include 'db_connect.php'; // Database connection

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Backend Password Validation (PHP)
    if (strlen($password) < 8 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[\W]/', $password)) {
        $error = "Password must be at least 8 characters long and include an uppercase letter, a number, and a special character.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO admin_users (name, email, role, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $role, $hashed_password);

        if ($stmt->execute()) {
            $success = "User added successfully!";
        } else {
            $error = "Error adding user!";
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
    <title>Add User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f8fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .message-box {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: center;
            font-size: 14px;
        }
        .error {
            background-color: #ffe5e5;
            color: #d9534f;
            border: 1px solid #d9534f;
        }
        .success {
            background-color: #e6f9e6;
            color: #28a745;
            border: 1px solid #28a745;
        }
        label {
            font-size: 14px;
            font-weight: 500;
            color: #555;
            display: block;
            text-align: left;
            margin-top: 10px;
        }
        input, select {
            width: 98%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }
        input:focus, select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        }
        .btn {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            margin-top: 20px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover {
            background:rgb(52, 126, 55);
        }
        .back-link {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            transition: 0.3s;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add User</h2>

    <!-- Display Messages -->
    <?php if (!empty($error)): ?>
        <div class="message-box error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="message-box success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Role:</label>
        <select name="role">
            <option value="Admin/Owner">Admin/Owner</option>
            <option value="Admin">Admin</option>
            <option value="Moderator">Moderator</option>
        </select>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Add User</button>
    </form>

    <a href="admin_user.php" class="back-link">Back to User Management</a>
</div>

</body>
</html>
