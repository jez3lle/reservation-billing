<?php
include('db.php'); // Ensure the database connection is included

// Fetch all reservations
$query = "SELECT r.id, r.arrival_date, r.departure_date, r.guest_name, r.guest_email, r.guest_phone, r.reservation_status, p.file_path
          FROM reservations r
          LEFT JOIN proof_of_payment p ON r.id = p.reservation_id
          ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Admin Panel - Reservation List</h1>";
    echo "<table border='1' style='width: 100%; border-collapse: collapse;'>"; // Table width set for better view
    echo "<tr style='background-color: #89BAA9; color: white;'>
            <th>ID</th>
            <th>Guest Name</th>
            <th>Arrival Date</th>
            <th>Departure Date</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Proof of Payment</th>
            <th>Change Status</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['guest_name'] . "</td>";
        echo "<td>" . $row['arrival_date'] . "</td>";
        echo "<td>" . $row['departure_date'] . "</td>";
        echo "<td>" . $row['guest_email'] . "</td>";
        echo "<td>" . $row['guest_phone'] . "</td>";
        echo "<td style='text-align: center;'>" . $row['reservation_status'] . "</td>";

        // If proof of payment exists, display it as a link
        if ($row['file_path']) {
            echo "<td><a href='" . $row['file_path'] . "' target='_blank'>View Proof</a></td>";
        } else {
            echo "<td>No Proof Uploaded</td>";
        }

        // Add a dropdown to change the status
        echo "<td>
                <form action='update-status.php' method='POST'>
                    <select name='status' style='padding: 5px; font-size: 14px; margin: 5px 0;'>
                        <option value='Pending' " . ($row['reservation_status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                        <option value='Approved' " . ($row['reservation_status'] == 'Approved' ? 'selected' : '') . ">Approved</option>
                        <option value='Declined' " . ($row['reservation_status'] == 'Declined' ? 'selected' : '') . ">Declined</option>
                    </select>
                    <input type='hidden' name='reservation_id' value='" . $row['id'] . "'>
                    <button type='submit' style='background-color: #4CAF50; color: white; border: none; padding: 5px 10px;'>Update</button>
                </form>
              </td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No reservations found.</p>";
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #89BAA9; /* Light Green */
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
