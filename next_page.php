<?php
// Check if 'selected_date' exists in the URL query parameters
if (isset($_GET['selected_date'])) {
    $selectedDate = $_GET['selected_date'];
} else {
    $selectedDate = "No date selected";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selected Date</title>
</head>
<body>

<h1>Selected Date: <?php echo htmlspecialchars($selectedDate); ?></h1>

<a href="calender.php">Go Back to Calendar</a>

</body>
</html>
