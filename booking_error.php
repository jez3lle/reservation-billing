<?php
// Check if there is an error message in the URL
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']); // Sanitize the message
} else {
    $message = "An unknown error occurred. Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #D9534F; /* Red for error */
        }
        p {
            color: #555;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Booking Error</h1>
        <p>Oops! Something went wrong with your booking.</p>
        <p>Error details: <strong><?php echo $message; ?></strong></p>
        <p>If the problem persists, please contact us.</p>
        <p><a href="index.php">Return to Home</a></p>
    </div>

</body>
</html>
