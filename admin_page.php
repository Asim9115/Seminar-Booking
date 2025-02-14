<?php
session_start();
if (!isset($_SESSION['log']) || $_SESSION['log'] !== true) {
    header('Location: admlogin.php'); 
    exit();
}

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "prologin_db";
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    if ($action == "approve") {
        // Approve the booking
        $sql = "UPDATE booking_requests SET status='approved' WHERE id='$booking_id'";

        if (mysqli_query($con, $sql)) {
            // Fetch approved booking details
            $fetch_sql = "SELECT * FROM booking_requests WHERE id='$booking_id'";
            $result = mysqli_query($con, $fetch_sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $approved_date = $row['date'];
                $approved_from_time = $row['from_time'];
                $approved_to_time = $row['to_time'];

                // Reject conflicting bookings
                $reject_sql = "UPDATE booking_requests 
                               SET status='rejected' 
                               WHERE status='pending' AND date='$approved_date' 
                               AND ((from_time < '$approved_to_time' AND to_time > '$approved_from_time')) 
                               AND id != '$booking_id'"; 
                mysqli_query($con, $reject_sql);
            }
            echo "<script>alert('Booking approved and conflicting bookings rejected successfully!');</script>";
        } else {
            echo "<script>alert('Error: Could not update the booking status.');</script>";
        }
    } elseif ($action == "reject") {
        // Reject the booking and ask for reason
        $rejection_reason = $_POST['rejection_reason']; // Get rejection reason from form

        // Update booking status to rejected and store rejection reason
        $sql = "UPDATE booking_requests SET status='rejected', rejection_reason=? WHERE id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('si', $rejection_reason, $booking_id);

        if ($stmt->execute()) {
            echo "<script>alert('Booking rejected successfully with the reason provided!');</script>";
        } else {
            echo "<script>alert('Error: Could not update the booking status.');</script>";
        }
    }
}

// Fetch pending bookings
$sql = "SELECT * FROM booking_requests WHERE status = 'pending' OR status IS NULL";
$result = mysqli_query($con, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
            margin-left: 270px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: calc(100% - 270px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f8f8f8;
        }

        form {
            display: inline-block;
        }

        button {
            padding: 8px 16px;
            margin-right: 5px;
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        button.reject {
            background-color: #dc3545;
        }

        .sidebar .active {
            background-color: #007bff;
        }

        .sidebar h2{
            margin: 10px;
            padding: 10px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <script>
        function openModal(bookingId) {
            document.getElementById("rejectionModal").style.display = "block";
            document.getElementById("modal_booking_id").value = bookingId;
        }

        function closeModal() {
            document.getElementById("rejectionModal").style.display = "none";
        }
    </script>
</head>
<body>

    <div class="sidebar">
        <h2 style="color:white;text-align:center;">Welcome Admin</h2>
        <a href="admin_page.php" class="active">Pending Requests</a>
        <a href="admin_page2.php">Check Previous Bookings</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Pending Booking Requests</h2>
        <table>
            <thead>
                <tr>
                <th>Department</th>
                    <th>Name</th>
                   
                    <th>Purpose</th>
                    <th>Capacity</th>
                    <th>From Time</th>
                    <th>To Time</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['department'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        
                        echo "<td>" . $row['purpose'] . "</td>";
                        echo "<td>" . $row['capacity'] . "</td>";
                        echo "<td>" . $row['from_time'] . "</td>";
                        echo "<td>" . $row['to_time'] . "</td>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . ucfirst($row['status']) . "</td>";
                        echo "<td>
                                <form method='POST'>
                                    <input type='hidden' name='booking_id' value='" . $row['id'] . "'>
                                    <button type='submit' name='action' value='approve'>Approve</button>
                                </form>
                                <button class='reject' type='button' onclick='openModal(" . $row['id'] . ")'>Reject</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No pending booking requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Rejection Reason Modal -->
    <div id="rejectionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Provide Rejection Reason</h2>
            <form method="POST">
                <input type="hidden" name="booking_id" id="modal_booking_id">
                <label for="rejection_reason">Reason:</label>
                <textarea name="rejection_reason" required></textarea><br><br>
                <button type="submit" name="action" value="reject">Submit Rejection</button>
            </form>
        </div>
    </div>

</body>
</html>

<?php
mysqli_close($con);
?>
