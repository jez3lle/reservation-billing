<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require_once 'db_connect.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM private_accommodations WHERE id = $id");
    header("Location: rooms_private.php");
    exit;
}

// Fetch accommodations
$result = $conn->query("SELECT * FROM private_accommodations ORDER BY id DESC");
$accommodations = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $accommodations[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Private Accommodations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/adminstyle.css">
</head>
<body>
<?php include 'headers/adminheader.php'; ?>

<div class="main-content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Private Accommodations</h2>
            <div>
                <a href="admin_rooms.php" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to Rooms Dashboard
                </a>
                <a href="private_add.php" class="btn btn-success">+ Add Accommodation</a>
            </div>
        </div>
        
        <?php if (!empty($accommodations)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accommodations as $a): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($a['name']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($a['description'])); ?></td>
                            <td>
                                <?php if (!empty($a['image'])): ?>
                                    <img src="uploads/<?php echo $a['image']; ?>" alt="Room Image" style="max-width: 100px;">
                                <?php else: ?>
                                    <small class="text-muted">No image</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="private_edit.php?id=<?php echo $a['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="rooms_private.php?delete=<?php echo $a['id']; ?>" onclick="return confirm('Are you sure you want to delete this accommodation?');" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No private accommodations found.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</body>
</html>