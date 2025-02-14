<?php
// Start session if needed
session_start();

// Include database connection
require 'connection.php'; // Make sure this file contains the $con variable for database connection

// Check if the booking ID is provided
$booking_id = isset($_GET['id']) ? intval($_GET['id']) : '';

if ($booking_id) {
    // Prepare the SQL query to fetch the booking details
    $stmt = $con->prepare("SELECT name, purpose, capacity, from_time, to_time FROM booking_requests WHERE id = ?");
    
    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }
    
    // Bind the booking ID parameter
    $stmt->bind_param("i", $booking_id);

    // Execute the query
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    
    // Bind result variables
    $stmt->bind_result($name, $purpose, $capacity, $from_time, $to_time);

    // Fetch the result
    if ($stmt->fetch()) {
        $stmt->close();
    } else {
        echo "No results found for Booking ID: $booking_id";
        $stmt->close();
        exit();
    }
} else {
    echo "Invalid booking ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
        }
        .details {
            margin-bottom: 15px;
        }
        .details label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Booking Details</h2>
        <div class="details">
            <label>Name:</label> <?php echo htmlspecialchars($name); ?>
        </div>
        <div class="details">
            <label>Purpose:</label> <?php echo htmlspecialchars($purpose); ?>
        </div>
        <div class="details">
            <label>Capacity:</label> <?php echo htmlspecialchars($capacity); ?>
        </div>
        <div class="details">
            <label>From Time:</label> <?php echo htmlspecialchars($from_time); ?>
        </div>
        <div class="details">
            <label>To Time:</label> <?php echo htmlspecialchars($to_time); ?>
        </div>
    </div>
</body>
</html>
