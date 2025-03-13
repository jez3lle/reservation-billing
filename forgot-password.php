<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <form action="sendpassword-reset.php" method="post">
        <div class="user-info">
            <label for="email">Email</label>
            <input type="text" id="email" name="email"> 
            <button class="submit-btn">Submit</button>
        </div>
    </form>
</body>
</html>