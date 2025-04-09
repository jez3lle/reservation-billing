<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require_once 'db_connect.php';
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'guest';
$guest_query = "SELECT gr.*, p.file_path 
                 FROM guest_reservation gr
                 LEFT JOIN payments p ON gr.reservation_code = p.guest_reservation_code
                 WHERE gr.status = 'Pending'";
$guest_result = $conn->query($guest_query);
if (!$guest_result) {
    die('Error fetching pending guest reservations: ' . $conn->error);
}
$user_query = "SELECT ur.*, u.email, u.first_name, u.last_name, p.file_path 
                    FROM user_reservation ur
                    LEFT JOIN user u ON ur.user_id = u.id
                    LEFT JOIN payments p ON ur.reservation_code = p.user_reservation_code
                    WHERE ur.status = 'Pending'";
$user_result = $conn->query($user_query);
if (!$user_result) {
    die('Error fetching user reservations: ' . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/adminstyle.css">
</head>
<body>
<?php include 'headers/adminheader.php'; ?>
<div class="main-content">
    <div class="container mt-4 mb-4 d-flex justify-content-between align-items-start">
        <h2>Dashboard</h2>
        <p class="mb-0">Hello, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</p>
    </div>
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?php echo ($active_tab == 'guest') ? 'active' : ''; ?>" href="?tab=guest">Guest Reservations</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($active_tab == 'user') ? 'active' : ''; ?>" href="?tab=user">User Reservations</a>
        </li>
    </ul>
    <?php if ($active_tab == 'guest'): ?>
        <h3>Pending Guest Reservations</h3>
        <?php if ($guest_result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr style="background-color: #2c3e50; color: white;">
                            <th scope="col">Reservation Code</th>
                            <th scope="col">Transaction Number</th>
                            <th scope="col">Status</th> 
                            <th scope="col">Proof of Payment</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $guest_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['reservation_code']; ?></td>
                                <td><?php echo $row['transaction_number']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <?php if (!empty($row['file_path'])): ?>
                                        <a href="<?php echo $row['file_path']; ?>" target="_blank">
                                            <button class="btn btn-info btn-sm">View Proof</button>
                                        </a>
                                    <?php else: ?>
                                        <span>No Proof of Payment</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="approve_reject.php" method="POST">
                                        <input type="hidden" name="reservation_code" value="<?php echo $row['reservation_code']; ?>">
                                        <input type="hidden" name="reservation_type" value="guest">
                                        <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No pending guest reservations.</p>
        <?php endif; ?>
    <?php elseif ($active_tab == 'user'): ?>
        <h3>Pending User Reservations</h3>
        <?php if ($user_result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr style="background-color: #2c3e50; color: white;">
                            <th scope="col">Reservation Code</th>
                            <th scope="col">User</th>
                            <th scope="col">Email</th>
                            <th scope="col">Transaction Number</th>
                            <th scope="col">Status</th>
                            <th scope="col">Proof of Payment</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $user_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['reservation_code']; ?></td>
                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['transaction_number']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <?php if (!empty($row['file_path'])): ?>
                                        <a href="<?php echo $row['file_path']; ?>" target="_blank">
                                            <button class="btn btn-info btn-sm">View Proof</button>
                                        </a>
                                    <?php else: ?>
                                        <span>No Proof of Payment</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="approve_reject.php" method="POST">
                                        <input type="hidden" name="reservation_code" value="<?php echo $row['reservation_code']; ?>">
                                        <input type="hidden" name="reservation_type" value="user">
                                        <input type="hidden" name="user_email" value="<?php echo $row['email']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No pending user reservations.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
<script type="text/javascript">
    <?php if (!empty($message)): ?>
        alert("<?php echo $message; ?>");
    <?php endif; ?>
</script>
</body>
</html>