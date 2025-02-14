<?php
session_start();
if (!isset($_SESSION['log']) || $_SESSION['log'] !== true) {
    header('Location: admlogin.php'); 
    exit();
}

$con = new mysqli("localhost", "root", "", "prologin_db");

if ($con->connect_error) {
    die('Database connection failed: ' . $con->connect_error);
}

if (isset($_POST['action']) && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $sql = "UPDATE booking_requests SET status='approved' WHERE id='$booking_id'";
    } elseif ($action == 'reject') {
        $sql = "UPDATE booking_requests SET status='rejected' WHERE id='$booking_id'";
    }

    if ($con->query($sql) === TRUE) {
        echo "<script>alert('Booking status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating status: " . $con->error . "');</script>";
    }
}

$sql = "SELECT * FROM booking_requests 
        WHERE status != 'pending'
        ORDER BY 
        CASE WHEN status = 'approved' THEN 1 
             WHEN status = 'rejected' THEN 2 
             ELSE 3 END, 
        date DESC, from_time ASC";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            background-color: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px;
            text-align: left;
            display: block;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .container {
            width: calc(100% - 250px); 
            margin-left: 250px; 
            padding: 20px;
            background-color: #fff;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f8f8f8;
        }

        .sidebar .active {
            background-color: #007bff;
        }

        .sidebar h2 {
            margin: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2 style="color:white;text-align:center;">Welcome Admin</h2>
        <a href="admin_page.php">Pending Requests</a>
        <a href="admin_page2.php" class="active">Check Previous Bookings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Manage Seminar Hall Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Name</th>
                    <th>Purpose</th>
                    <th>Capacity</th>
                    <th>Date</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th>Rejection Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $formatted_date = date("d-m-Y", strtotime($row['date']));
                        
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['capacity']) . "</td>";
                        echo "<td>" . $formatted_date . "</td>";
                        echo "<td>" . htmlspecialchars($row['from_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['to_time']) . "</td>";
                        echo "<td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>";

                        if ($row['status'] == 'Rejected') {
                            echo "<td>" . htmlspecialchars($row['rejection_reason']) . "</td>";
                        } else {
                            echo "<td>-</td>"; // Display a dash if not rejected
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$con->close();
?>
