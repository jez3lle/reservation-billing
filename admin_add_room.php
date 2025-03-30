<?php
include 'db_connect.php'; // Database connection
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    // Handle image upload
    $image = "";
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads_rooms/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO rooms (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Room added successfully!";
        header("Location: admin_rooms.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error adding room.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8f0; /* Light green background */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h2 {
            color: #2e7d32; /* Darker green for heading */
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: #e8f5e9; /* Lighter green for form background */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: calc(100% - 22px); /* Adjusted width for padding/border */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #c8e6c9; /* Light green border */
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #f1f8f1;
        }

        textarea {
            resize: vertical;
        }

        button,
        a {
            background-color: #4caf50; /* Green button */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover,
        a:hover {
            background-color: #388e3c; /* Darker green on hover */
        }

        a {
            background-color: #ccc; /* Grey for cancel button */
            color: #333;
            margin-left: 10px;
        }

        a:hover {
            background-color: #bbb;
        }
    </style>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
<h2>Add Room</h2>
    <label>Room Name:</label>
    <input type="text" name="name" required>

    <label>Description:</label>
    <textarea name="description" required></textarea>

    <label>Price:</label>
    <input type="number" name="price" step="0.01" required>

    <label>Upload Image:</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Add Room</button>
    <a href="admin_rooms.php">Cancel</a>
</form>

</body>
</html>
