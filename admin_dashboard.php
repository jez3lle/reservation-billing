<?php
session_start();

// Debugging
error_log('Session data: ' . print_r($_SESSION, true));

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Get database connection
$mysqli = require 'database.php';

// Get reservation statistics
$pending_count = 0;
$confirmed_count = 0;
$completed_count = 0;
$cancelled_count = 0;
$total_revenue = 0;

// Count pending reservations
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM guest_reservations WHERE status = 'pending'");
$stmt->execute();
$stmt->bind_result($pending_count);
$stmt->fetch();
$stmt->close();

// Count confirmed reservations
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM guest_reservations WHERE status = 'confirmed'");
$stmt->execute();
$stmt->bind_result($confirmed_count);
$stmt->fetch();
$stmt->close();

// Count completed reservations
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM guest_reservations WHERE status = 'completed'");
$stmt->execute();
$stmt->bind_result($completed_count);
$stmt->fetch();
$stmt->close();

// Count cancelled reservations
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM guest_reservations WHERE status = 'cancelled'");
$stmt->execute();
$stmt->bind_result($cancelled_count);
$stmt->fetch();
$stmt->close();

// Calculate total revenue
$stmt = $mysqli->prepare("SELECT SUM(payment_amount) FROM guest_reservations WHERE status IN ('confirmed', 'completed')");
$stmt->execute();
$stmt->bind_result($total_revenue);
$stmt->fetch();
$stmt->close();
if ($total_revenue === null) {
    $total_revenue = 0;
}

// Get recent reservations
$stmt = $mysqli->prepare("SELECT reservation_code, first_name, last_name, check_in_date, check_out_date, guest_adult + guest_kid as total_guests, payment_amount, status, created_at 
                         FROM guest_reservations 
                         ORDER BY created_at DESC 
                         LIMIT 5");
$stmt->execute();
$recent_reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get upcoming check-ins (next 7 days)
$today = date('Y-m-d');
$next_week = date('Y-m-d', strtotime('+7 days'));

$stmt = $mysqli->prepare("SELECT reservation_code, first_name, last_name, check_in_date, guest_adult + guest_kid as total_guests, status
                         FROM guest_reservations 
                         WHERE check_in_date BETWEEN ? AND ?
                         AND status IN ('confirmed', 'pending')
                         ORDER BY check_in_date ASC");
$stmt->bind_param("ss", $today, $next_week);
$stmt->execute();
$upcoming_checkins = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar-link i {
            margin-right: 10px;
        }
        .admin-content {
            padding: 20px;
        }
        .stats-card {
            border-left: 4px solid;
            border-radius: 4px;
        }
        .stats-icon {
            font-size: 28px;
            opacity: 0.8;
        }
        .stats-pending { border-color: #dc3545; }
        .stats-confirmed { border-color: #198754; }
        .stats-completed { border-color: #0d6efd; }
        .stats-cancelled { border-color: #6c757d; }
        .stats-revenue { border-color: #fd7e14; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 px-0 sidebar">
                <div class="d-flex flex-column">
                    <div class="p-3 text-center">
                        <h4>Resort Admin</h4>
                        <hr>
                    </div>
                    <a href="admin_dashboard.php" class="sidebar-link active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="admin_pending.php" class="sidebar-link">
                        <i class="bi bi-hourglass-split"></i> Pending Reservations
                    </a>
                    <a href="admin_confirmed.php" class="sidebar-link">
                        <i class="bi bi-check-circle"></i> Confirmed Reservations
                    </a>
                    <a href="admin_complete.php" class="sidebar-link">
                        <i class="bi bi-calendar-check"></i> Completed Reservations
                    </a>
                    <a href="admin_cancelled.php" class="sidebar-link">
                        <i class="bi bi-x-circle"></i> Cancelled Reservations
                    </a>
                    <a href="admin_settings.php" class="sidebar-link">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                    <hr>
                    <a href="admin_logout.php" class="sidebar-link">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-10 col-md-9 ms-auto admin-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Dashboard</h2>
                    <div>
                        <span class="me-2"><?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?></span>
                        <a href="admin_logout.php" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl">
                        <div class="card stats-card stats-pending mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted">Pending</h6>
                                        <h3><?php echo $pending_count; ?></h3>
                                    </div>
                                    <i class="bi bi-hourglass-split stats-icon text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl">
                        <div class="card stats-card stats-confirmed mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted">Confirmed</h6>
                                        <h3><?php echo $confirmed_count; ?></h3>
                                    </div>
                                    <i class="bi bi-check-circle stats-icon text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl">
                        <div class="card stats-card stats-completed mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted">Completed</h6>
                                        <h3><?php echo $completed_count; ?></h3>
                                    </div>
                                    <i class="bi bi-calendar-check stats-icon text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl">
                        <div class="card stats-card stats-cancelled mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted">Cancelled</h6>
                                        <h3><?php echo $cancelled_count; ?></h3>
                                    </div>
                                    <i class="bi bi-x-circle stats-icon text-secondary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl">
                        <div class="card stats-card stats-revenue mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted">Total Revenue</h6>
                                        <h3>₱<?php echo number_format($total_revenue, 2); ?></h3>
                                    </div>
                                    <i class="bi bi-cash-stack stats-icon text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Recent Reservations -->
                    <div class="col-md-8 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Recent Reservations</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Guest</th>
                                                <th>Check-in</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($recent_reservations)): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center py-3">No recent reservations found</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($recent_reservations as $reservation): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($reservation['reservation_code']); ?></td>
                                                        <td><?php echo htmlspecialchars($reservation['first_name']); ?></td>
                                                        <td><?php echo date('M d, Y', strtotime($reservation['check_in_date'])); ?></td>
                                                        <td>₱<?php echo number_format($reservation['payment_amount'], 2); ?></td>
                                                        <td>
                                                            <?php
                                                            $status_class = '';
                                                            switch ($reservation['status']) {
                                                                case 'pending':
                                                                    $status_class = 'badge bg-warning';
                                                                    break;
                                                                case 'confirmed':
                                                                    $status_class = 'badge bg-success';
                                                                    break;
                                                                case 'completed':
                                                                    $status_class = 'badge bg-primary';
                                                                    break;
                                                                case 'cancelled':
                                                                    $status_class = 'badge bg-secondary';
                                                                    break;
                                                            }
                                                            ?>
                                                            <span class="<?php echo $status_class; ?>">
                                                                <?php echo ucfirst($reservation['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="admin_view_reservation.php?code=<?php echo $reservation['reservation_code']; ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="admin_pending.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Upcoming Check-ins -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Upcoming Check-ins</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <?php if (empty($upcoming_checkins)): ?>
                                        <li class="list-group-item text-center py-3">No upcoming check-ins</li>
                                    <?php else: ?>
                                        <?php foreach ($upcoming_checkins as $checkin): ?>
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($checkin['first_name']); ?></strong><br>
                                                        <small class="text-muted">
                                                            <?php echo date('M d, Y', strtotime($checkin['check_in_date'])); ?> • 
                                                            <?php echo $checkin['total_guests']; ?> guests
                                                        </small>
                                                    </div>
                                                    <span class="badge <?php echo $checkin['status'] == 'confirmed' ? 'bg-success' : 'bg-warning'; ?>">
                                                        <?php echo ucfirst($checkin['status']); ?>
                                                    </span>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="card-footer text-end">
                                <a href="admin_checkins.php" class="btn btn-sm btn-outline-success">View Calendar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>