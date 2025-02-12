<?php
include('db.php'); // Ensure the database connection is included

// Fetch all reservations
$query = "SELECT r.id, r.arrival_date, r.departure_date, r.guest_name, r.guest_email, r.guest_phone, r.reservation_status, p.file_path
          FROM reservations r
          LEFT JOIN proof_of_payment p ON r.id = p.reservation_id
          ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $query);

$reservations = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }
} else {
    $reservations = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', Arial, sans-serif;
      background-color:#ffffff;
      color: #2C3E50;
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: hidden;
    }

    /* Header */
    .main-header {
      width: 100%;
      background: #184D47;
      color: #E8F5E9;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
      flex-shrink: 0;
    }

    .main-header h1 {
      font-size: clamp(1.2rem, 2vw, 1.6rem);
      margin: 0;
    }

    .user-profile {
      display: flex;
      margin-right: 25px;
      align-items: center;
    }

    .user-icon {
      width: 50px;
      height: 50px;
      align-items: center;
      border-radius: 50%;
    }

    /* Sidebar */
    .sidebar {
      background-color: #E8F5E9;
      width: 250px;
      color: #042420;
      display: flex;
      flex-direction: column;
      padding: 20px;
      overflow-y: auto;
      flex-shrink: 0;
    }

    .nav-links {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .nav-links li {
      margin: 10px 0;
    }

    .nav-links a {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: #042420;
      padding: 12px 16px;
      font-size: 1rem;
      border-radius: 5px;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .nav-links a:hover,
    .nav-links a.active {
      background-color: #508E87;
      color: #c6f4ef;
      transform: translateX(8px);
      transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease;
    }

    .logout-btn {
      margin-top: auto;
      background-color: #508E87;
      color: #ffffff;
      border: none;
      padding: 10px 13px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s ease;
    }

    .logout-btn:hover {
      background-color: #2E7D32;
    }

    /* Content */
    .content {
      flex-grow: 1;
      padding: 20px;
      background-color: #ffffff;
      overflow-y: auto;
      border-radius: 8px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .content-container {
      display: flex;
      flex: 1;
      overflow: hidden;
      flex-wrap: nowrap;
    }

    .sidebar {
      width: 250px;
      flex-shrink: 0;
    }

    .content {
      flex-grow: 1;
      min-width: 0;
      padding: 20px;
      background-color: #ffffff;
      overflow-y: auto;
      border-radius: 8px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .content p {
      font-size: 1.8rem;
      font-weight: bold;
      color: #002f25;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table th {
        background-color: #89BAA9;
        color: white;
    }

    form select {
        padding: 5px;
        font-size: 14px;
        margin: 5px 0;
    }

    form button {
        padding: 5px 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    form button:hover {
        background-color: #45a049;
    }
  </style>
</head>
<body>
  <header class="main-header">
    <div class="logo">
      <h1>Rainbow Forest Paradise Resort and Campsite</h1>
    </div>
    <div class="user-profile">
      <span>Hello, Admin!</span>
      <img src="login-active.png" alt="Admin Profile Picture" class="user-icon">
    </div>
  </header>

  <div class="content-container">
    <nav class="sidebar">
      <ul class="nav-links">
        <li><a href="#dashboard" class="active" aria-current="page">Dashboard</a></li>
        <li><a href="#reservations">Reservations</a></li>
        <li><a href="#payments">Payments</a></li>
        <li><a href="#guests">Guests</a></li>
        <li><a href="#reports">Reports</a></li>
        <li><a href="#calendar">Calendar</a></li>
        <li><a href="#settings">Room Management</a></li>
        <li><a href="#user-management">User Management</a></li>
        <li><a href="#settings">Settings</a></li>
      </ul>
      <button class="logout-btn">Log Out</button>
    </nav>

    <main class="content">
      <h1>Admin Panel - Reservation List</h1>
      <?php if (!empty($reservations)): ?>
        <table>
          <tr style="background-color: #89BAA9; color: white;">
            <th>ID</th>
            <th>Guest Name</th>
            <th>Arrival Date</th>
            <th>Departure Date</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Proof of Payment</th>
            <th>Change Status</th>
          </tr>
          <?php foreach ($reservations as $row): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= $row['guest_name'] ?></td>
              <td><?= $row['arrival_date'] ?></td>
              <td><?= $row['departure_date'] ?></td>
              <td><?= $row['guest_email'] ?></td>
              <td><?= $row['guest_phone'] ?></td>
              <td style="text-align: center;"><?= $row['reservation_status'] ?></td>
              <td>
                <?php if ($row['file_path']): ?>
                  <a href="<?= $row['file_path'] ?>" target="_blank">View Proof</a>
                <?php else: ?>
                  No Proof Uploaded
                <?php endif; ?>
              </td>
              <td>
                <form action="update-status.php" method="POST">
                  <select name="status">
                    <option value="Pending" <?= $row['reservation_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Approved" <?= $row['reservation_status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="Declined" <?= $row['reservation_status'] == 'Declined' ? 'selected' : '' ?>>Declined</option>
                  </select>
                  <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
                  <button type="submit">Update</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php else: ?>
        <p>No reservations found.</p>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
