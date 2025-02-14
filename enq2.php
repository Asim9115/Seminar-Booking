<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); 
    exit();
}
$logged_in_email = '';
$user_name = '';
if (isset($_SESSION['booking_success'])) {
    echo "<script>alert('" . $_SESSION['booking_success'] . "');</script>";
    unset($_SESSION['booking_success']);
}

if (isset($_SESSION['login-email'])) {
    $logged_in_email = $_SESSION['login-email'];

    // Prepare the SQL query to fetch the user's name
    $stmt = $con->prepare("SELECT name FROM prof WHERE email_id = ?");
    $stmt->bind_param("s", $logged_in_email);
    $stmt->execute();
    $stmt->bind_result($user_name);
    $stmt->fetch();
    $stmt->close();
} else {
    $user_name = 'No user found in session.';
}

if (isset($_POST['action']) && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $sql = "UPDATE booking_requests SET status='approved' WHERE id='$booking_id' AND name='$user_name'";
    } elseif ($action == 'reject') {
        $sql = "UPDATE booking_requests SET status='rejected' WHERE id='$booking_id' AND name='$user_name'";
    }

    if ($con->query($sql) === TRUE) {
        echo "<script>alert('Booking status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating status: " . $con->error . "');</script>";
    }
}

// Fetch all booking requests for the logged-in user
$sql = "SELECT * FROM booking_requests WHERE name='$user_name' ORDER BY date DESC";
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

        .sidebar .active {
            background-color: #007bff;
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

        button {
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
        }

        button.approve {
            background-color: green;
        }

        button.reject {
            background-color: red;
        }

        button:disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }
        .sidebar h2{
            margin: 0px;
            padding: 10px;
            color: white;
            text-align: center;
        }

    </style>
</head>
<body>
<div class="sidebar">
<h2>Welcome,<br> <?php echo htmlspecialchars($user_name); ?></h2>
        <a href="enquiry.php" >Enquiry</a>
        <a href="enq2.php" class="active">My Bookings</a>
        <a href="calender.php">Check other Bookings</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>My Booking status</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Purpose</th>
                    <th>Capacity</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Rejection Reason</th> <!-- Added this column for rejection reason -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['capacity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['from_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['to_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                        echo "<td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>";

                        // Display rejection reason only if status is rejected
                        if ($row['status'] == 'Rejected') {
                            echo "<td>" . htmlspecialchars($row['rejection_reason']) . "</td>";
                        } else {
                            echo "<td>-</td>"; // Display a dash if not rejected
                        }
                        
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close connection
$con->close();
?>
