<?php
include("connection.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    if (isset($_POST['signup-name'], $_POST['department'], $_POST['signup-email'], $_POST['signup-password'])) {
        $name = $_POST['signup-name'];
        $department = $_POST['department'];
        $email_id = $_POST['signup-email'];
        $password = $_POST['signup-password'];

        if (!strpos($email_id, '@aiktc.ac.in')) {
            echo "<script>alert('Please enter your AIKTC email id');</script>";
            echo "<script>window.location.href='signup.php';</script>";
            exit();
        }

        $checkemail = "SELECT * FROM prof where email_id ='$email_id'";
        $result = $con->query($checkemail);
        if($result -> num_rows >0)
        {
            echo "<script>alert('Email already exists');</script>";
            echo "<script>window.location.href='signup.php';</script>";
        }
        else{
            
            $stmt = $con->prepare("INSERT INTO prof (name, email_id, password, department) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email_id, $password, $department);
            if ($stmt->execute()) {
                
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        echo "Please fill in all required fields.";
    }
}


$con->close();
?>
 <style>
        .bg{display: flex;
            
            position: absolute;
            background-image: url(new1.jpg);
            height: 100%;
            width: 100%;
            z-index: -2;
            background-size: cover;
        }
        body {  
            font-family: Arial, sans-serif;
           
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
           backdrop-filter: blur(5px);
            padding: 20px;
            border: 5px solid;
            background-color: burlywood;
            border-color: rgb(194, 233, 194);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 380px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;

        }
        .form-group button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .links {
            text-align: center;
        }
        .links a {
            color: #007bff;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <style>
     
    </style>
</head>

<body>
<div class="bg"></div>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="signup.php" method="post">
            <div class="form-group">
                <label for="signup-name">Name:</label>
                <input type="text" id="signup-name" name="signup-name" required>
            </div>
            <div class="form-group">
                <label for="signup-email">Email:</label>
                <input type="email" id="signup-email" name="signup-email" required>
            </div>
            <div class="form-group">
                <label for="signup-password">Password:</label>
                <input type="password" id="signup-password" name="signup-password" required>
            </div>
            <div class="form-group">
                <label for="signup-department">Department:</label>
                <input type="text" id="signup-department" name="department" required>
            </div>
            <div class="form-group">
                <button type="submit">Sign Up</button>
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
