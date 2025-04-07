<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_connect.php';

$limit = 10; // Number of reservations per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "
    SELECT reservation_code, 'Guest (No Account)' AS type, transaction_number, status, created_at 
    FROM guest_reservation
    UNION
    SELECT reservation_code, 'User (With Account)' AS type, transaction_number, status, created_at 
    FROM user_reservation
    ORDER BY created_at DESC
    LIMIT $start, $limit
";

$result = $conn->query($sql);
$total = $pending = $confirmed = 0;
$reservations = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
        $total++;
        if ($row['status'] == 'Pending') $pending++;
        if ($row['status'] == 'Approved') $confirmed++;
    }
} else {
    die("Query error: " . $conn->error);
}

// Pagination: Total count of records
$total_sql = "SELECT COUNT(*) AS total_count FROM (
    SELECT reservation_code FROM guest_reservation
    UNION
    SELECT reservation_code FROM user_reservation
) AS combined";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_reservations = $total_row['total_count'];
$total_pages = ceil($total_reservations / $limit);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/adminstyle.css">
</head>
<body>
<?php include 'headers/adminheader.php'; ?>
<div class="main-content">
    <div class="container mt-4">
        <h2>All Reservations Overview</h2>
        <div class="row my-4">
            <div class="col-md-4">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h5>Total Reservations</h5>
                        <p class="fs-4"><?php echo $total; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-warning">
                    <div class="card-body">
                        <h5>Pending</h5>
                        <p class="fs-4"><?php echo $pending; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h5>Approved</h5>
                        <p class="fs-4"><?php echo $confirmed; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Separate tables for user and guest reservations -->
        <div class="row my-4">
            <div class="col-md-6">
                <h3>Guest Reservations</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Reservation Code</th>
                                <th>Type</th>
                                <th>Transaction Number</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $res): ?>
                                <?php if (strpos($res['type'], 'Guest') !== false): ?>
                                    <tr>
                                        <td><?php echo $res['reservation_code']; ?></td>
                                        <td><?php echo $res['type']; ?></td>
                                        <td><?php echo $res['transaction_number']; ?></td>
                                        <td><?php echo $res['status']; ?></td>
                                        <td>
                                            <a href="view_reservation.php?code=<?php echo $res['reservation_code']; ?>&type=guest" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <h3>User Reservations</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Reservation Code</th>
                                <th>Type</th>
                                <th>Transaction Number</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $res): ?>
                                <?php if (strpos($res['type'], 'User') !== false): ?>
                                    <tr>
                                        <td><?php echo $res['reservation_code']; ?></td>
                                        <td><?php echo $res['type']; ?></td>
                                        <td><?php echo $res['transaction_number']; ?></td>
                                        <td><?php echo $res['status']; ?></td>
                                        <td>
                                            <a href="view_reservation.php?code=<?php echo $res['reservation_code']; ?>&type=user" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <!-- Pagination -->
<div class="d-flex justify-content-center">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item <?php echo ($page == 1) ? 'active' : ''; ?>">
                <a class="page-link" href="#"><?php echo $page; ?></a>
            </li>
            <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
    </div>
</div>
</body>
</html>
