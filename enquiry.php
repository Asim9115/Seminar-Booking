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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $purpose = $_POST['purpose'];
    $capacity = $_POST['capacity'];
    $from_time = $_POST['slot1'];
    $to_time = $_POST['slot2'];
    $date = $_POST['date'];

    // Check for existing bookings
    $check_sql = "
        SELECT * FROM booking_requests 
        WHERE date = ? 
        AND status = 'approved' 
        AND (
            (from_time <= ? AND to_time > ?) OR 
            (from_time < ? AND to_time >= ?)
        )";
        
    $stmt_check = $con->prepare($check_sql);
    $stmt_check->bind_param('sssss', $date, $from_time, $from_time, $to_time, $to_time);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('The selected date and time are already booked. Please choose a different time.');</script>";
    } else {
        // Insert booking request with department
        $stmt = $con->prepare("INSERT INTO booking_requests (name, department, purpose, capacity, from_time, to_time, date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $status = 'pending'; // Default status
        $stmt->bind_param("ssssssss", $user_name, $department, $purpose, $capacity, $from_time, $to_time, $date, $status);

        if ($stmt->execute()) {
            $_SESSION['booking_success'] = 'Your Booking Request has been sent for approval';
            header("Location: enq2.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seminar Hall Booking</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
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

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
        }

        h2 {
            text-align: center;
            color: #343a40;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #495057;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            color: #495057;
            outline: none;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 5px rgba(128, 189, 255, 0.5);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .availability {
            text-align: center;
            margin-top: 20px;
            color: #28a745;
            font-weight: bold;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .container {
                width: 100%;
                max-width: 100%;
                padding: 20px;
            }
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
        <a href="enquiry.php" class="active">Enquiry</a>
        <a href="enq2.php">My Bookings</a>
        <a href="calender.php"  >Check other Bookings</a>
        <a href="logout.php">Logout</a>
    </div>

   
    <div class="content">
        <div class="container">
            
            <h2>Seminar Hall Booking</h2>
            <form id="bookingForm" method="POST">
                <div class="form-group">
                    <label for="purpose">Purpose of Booking</label>
                    <input type="text" id="purpose" name="purpose" placeholder="Enter the purpose" required>
                </div>
                <div class="form-group">
                    <label for="capacity">Capacity of Students</label>
                    <input type="number" id="capacity" name="capacity" placeholder="Enter number of students" required>
                </div>
                <div class="form-group">
                    <label for="slot1">From Time</label>
                    <select id="slot1" name="slot1" required>
                        <option value="">Select Time</option>
                        <option value="09:00">09:00</option>
                        <option value="09:30">09:30</option>
                       
                    <option value="10:00">10:00</option>
                    <option value="10:30">10:30</option>
                    <option value="11:00">11:00</option>
                    <option value="11:30">11:30</option>
                    <option value="12:00">12:00</option>
                    <option value="12:30">12:30</option>
                    <option value="13:00">13:00</option>
                    <option value="13:30">13:30</option>
                    <option value="14:00">14:00</option>
                    <option value="14:30">14:30</option>
                    <option value="15:00">15:00</option>
                    <option value="15:30">15:30</option>
                    <option value="16:00">16:00</option>
                  
                        <option value="16:30">16:30</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="slot2">To Time</label>
                    <select id="slot2" name="slot2" required >
                        <option value="">Select Time</option>
                        <option value="09:30">09:30</option>
                       
                    <option value="10:00">10:00</option>
                    <option value="10:30">10:30</option>
                    <option value="11:00">11:00</option>
                    <option value="11:30">11:30</option>
                    <option value="12:00">12:00</option>
                    <option value="12:30">12:30</option>
                    <option value="13:00">13:00</option>
                    <option value="13:30">13:30</option>
                    <option value="14:00">14:00</option>
                    <option value="14:30">14:30</option>
                    <option value="15:00">15:00</option>
                    <option value="15:30">15:30</option>
                    <option value="16:00">16:00</option>
                    <option value="16:30">16:30</option>
                        <option value="16:30">16:30</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <button class="btn" type="submit">Book</button>
            </form>
            <div class="availability" id="availability"></div>
        </div>
    </div>
</body>
</html>