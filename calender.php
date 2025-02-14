<?php
session_start();  

require 'connection.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

$logged_in_email = '';
$user_name = '';
$department = '';

// Retrieve logged-in user's name and department from session or database
if (isset($_SESSION['login-email'])) {
    $logged_in_email = $_SESSION['login-email'];

    $stmt = $con->prepare("SELECT name, department FROM prof WHERE email_id = ?");
    $stmt->bind_param("s", $logged_in_email);
    $stmt->execute();
    $stmt->bind_result($user_name, $department);
    $stmt->fetch();
    $stmt->close();
} else {
    $user_name = 'No user found in session.';
    $department = 'No department found in session.';
}

// Fetch approved booking dates with timings, sorting by date and then from_time
$sql = "SELECT date, from_time, to_time FROM booking_requests WHERE status = 'approved' ORDER BY date ASC, from_time ASC";
$result = $con->query($sql);

$booked_dates = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert date format from YYYY-MM-DD to DD-MM-YYYY
        $formatted_date = date("d-m-Y", strtotime($row['date']));
        $booked_dates[] = [
            'date' => $formatted_date,
            'from_time' => $row['from_time'],
            'to_time' => $row['to_time']
        ];
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked Dates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f4f4;
        }

        /* Sidebar styling */
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

        .sidebar .active {
            background-color: #007bff;
        }

        .sidebar h2 {
            text-align: center;
            color: white;
            margin: 0;
            padding: 10px;
        }

        /* Main content styling */
        .container {
             background-color: #fff;
            width: calc(100% - 250px);
            margin-left: 250px;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f8f8f8;
        }

        .highlight {
          
            color: red;
        }
    </style>
</head>
<body>

<div class="sidebar">
<h2>Welcome,<br> <?php echo htmlspecialchars($user_name); ?></h2>
    <a href="enquiry.php">Enquiry</a>
    <a href="enq2.php">My Bookings</a>
    <a href="calender.php" class="active">Check other Bookings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h1>Booked Dates and Timings</h1>

    <?php if (!empty($booked_dates)): ?>
        <table>
            <thead>
                <tr >
                    <th>Date </th>
                    <th>From Time</th>
                    <th>To Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($booked_dates as $booking): ?>
                    <tr class="highlight">
                        <td><?php echo htmlspecialchars($booking['date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['from_time']); ?></td>
                        <td><?php echo htmlspecialchars($booking['to_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</div>

</body>
</html>
