<?php
session_start();  // Ensure session is started

require 'connection.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($booking_id > 0) {
    $stmt = $con->prepare("SELECT purpose, capacity, from_time, to_time, date, status FROM booking_requests WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->bind_result($purpose, $capacity, $from_time, $to_time, $date, $status);
    $stmt->fetch();
    $stmt->close();
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
    </style>
</head>
<body>
    <div class="booking-details">
        <p><strong>Purpose:</strong> <?php echo htmlspecialchars($purpose); ?></p>
        <p><strong>Capacity:</strong> <?php echo htmlspecialchars($capacity); ?></p>
        <p><strong>From:</strong> <?php echo htmlspecialchars($from_time); ?></p>
        <p><strong>To:</strong> <?php echo htmlspecialchars($to_time); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($date); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($status); ?></p>
    </div>
</body>
</html>
