<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit;
}

$mysqli = require __DIR__ . "/database.php";
$stmt = $mysqli->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles/mystyle.css">
    <title>User Account</title>
</head>
<body>
<?php include 'headers/header.php'; ?>



    <div class="account-info">
        <h1>Account Page</h1>
        <h2>Your Reservations</h2>
<table border="1">
    <tr>
        <th>Reservation Code</th>
        <th>Date Reserved</th>
        <th>Status</th>
        <th>Proof of Payment</th>
        <th>Action</th>
    </tr>
    <?php
    $stmt = $mysqli->prepare("SELECT * FROM user_reservation WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()):
    ?>
        <tr>
            <td><?= htmlspecialchars($row['reservation_code']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
     
            <td>
                <?php if ($row['payment_proof']): ?>
                    <a href="uploads/<?= htmlspecialchars($row['payment_proof']) ?>" target="_blank">View</a>
                <?php else: ?>
                    Not uploaded
                <?php endif; ?>
            </td>
            <td>
                <?php if (!$row['payment_proof']): ?>
                    <form action="upload_payment.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
                        <input type="file" name="payment_proof" required>
                        <input type="text" name="transaction_number" placeholder="Transaction #" required>
                        <button type="submit">Upload</button>
                    </form>
                <?php else: ?>
                    Uploaded
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

        
    </div>




    <?php include 'headers/footer.php'; ?>

    <script>
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            const hamburger = document.querySelector('.hamburger');
            const hamburgerVertical = document.querySelector('.hamburger-vertical');
            const header = document.querySelector('.page-header');
            menu.classList.toggle('active');
            header.classList.toggle('hidden');
            if (menu.classList.contains('active')) {
                hamburger.style.display = 'none';
                hamburgerVertical.style.display = 'block';
            } else {
                hamburger.style.display = 'block';
                hamburgerVertical.style.display = 'none';
            }
        }

        function bookNow(phase) {
            alert(`You clicked Book Now for ${phase}!`);
        }
    </script>
</body>
</html>