<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require_once 'db_connect.php';

// Get all rooms (excluding private if needed)
$sql = "SELECT * FROM rooms WHERE name NOT LIKE '%Private%'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Public Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/adminstyle.css">
</head>
<body>
<?php include 'headers/adminheader.php'; ?>

<div class="main-content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Public Rooms</h2>
            <div>
                <a href="admin_rooms.php" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to Rooms Dashboard
                </a>
                <a href="add_room.php" class="btn btn-success">+ Add Room</a>
            </div>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Day Tour Price</th>
                            <th>Night Tour Price</th>
                            <th>Quantity</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($room = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($room['name']) ?></td>
                            <td>₱<?= number_format($room['day_tour_price'], 2) ?></td>
                            <td>₱<?= number_format($room['night_tour_price'], 2) ?></td>
                            <td><?= $room['quantity'] ?></td>
                            <td>
                                <?php if (!empty($room['image'])): ?>
                                    <img src="uploads/<?= $room['image'] ?>" width="100" alt="Room Image">
                                <?php else: ?>
                                    <small class="text-muted">No image</small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($room['status']) ?></td>
                            <td>
                                <a href="edit_room.php?id=<?= $room['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_room.php?id=<?= $room['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this room?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No public rooms found.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</body>
</html>