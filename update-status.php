<?php
include('db.php'); // Include your database connection

if (isset($_POST['reservation_id']) && isset($_POST['status'])) {
    $reservation_id = $_POST['reservation_id'];
    $status = $_POST['status'];

    // Update reservation status
    $query = "UPDATE reservations SET reservation_status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $status, $reservation_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p>Status updated successfully!</p>";
        echo "<a href='admin.php'>Go back to Admin Panel</a>";
    } else {
        echo "<p>Error updating status.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
