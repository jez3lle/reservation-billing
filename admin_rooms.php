<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require_once 'db_connect.php';

// Count public rooms
$public_result = $conn->query("SELECT COUNT(*) AS total FROM rooms");
$public_count = $public_result ? $public_result->fetch_assoc()['total'] : 0;

// Count private accommodations
$private_result = $conn->query("SELECT COUNT(*) AS total FROM private_accommodations");
$private_count = $private_result ? $private_result->fetch_assoc()['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/adminstyle.css">
    <style>
        .room-card {
            transition: 0.3s ease;
            cursor: pointer;
        }
        .room-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .room-icon {
            font-size: 2.5rem;
        }
    </style>
</head>
<body>
<?php include 'headers/adminheader.php'; ?>

<div class="main-content">
    <div class="container mt-4">
        <h2 class="mb-4">Rooms Overview</h2>

        <div class="row g-4">
            <!-- Public Rooms Card -->
            <div class="col-md-6">
                <a href="rooms_public.php" style="text-decoration: none;">
                    <div class="card border-primary room-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 text-primary">
                                
                            </div>
                            <div>
                                <h5 class="card-title mb-1 text-dark">Public Rooms</h5>
                                <p class="mb-0 text-muted">Total: <strong><?php echo $public_count; ?></strong></p>
                                <small>View and manage public room types</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Private Accommodations Card -->
            <div class="col-md-6">
                <a href="rooms_private.php" style="text-decoration: none;">
                    <div class="card border-success room-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 text-success">
                                
                            </div>
                            <div>
                                <h5 class="card-title mb-1 text-dark">Private Accommodations</h5>
                                <p class="mb-0 text-muted">Total: <strong><?php echo $private_count; ?></strong></p>
                                <small>View and manage private area facilities</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="mt-5 text-muted">
            <p class="text-center">Choose a category above to manage room details.</p>
        </div>
    </div>
</div>
</body>
</html>
