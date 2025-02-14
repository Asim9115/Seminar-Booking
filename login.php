<?php

include("connection.php");
session_start();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    if (isset($_POST['login-email'], $_POST['login-password'])) {
        $email = $_POST['login-email'];
        $password = $_POST['login-password'];
      
        $result = mysqli_query($con,"SELECT * FROM prof WHERE email_id = '$email'");
        $row =  mysqli_fetch_assoc($result);
        if(mysqli_num_rows($result)>0){
            if($password == $row["password"]){
                $_SESSION["loggedin"] = true;
                $_SESSION['login-email'] = $email;
                header("Location: enquiry.php");
            }
            else{
                echo
                "<script> alert('wrong Password')</script>";
            }
        }
        else{echo
            "<script> alert('User not registered')</script>";
        }




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
    <title>Login Page</title>
    
</head>
<body>
<div class="bg"></div>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" name="login-email" required>
            </div>
            <div class="form-group">
                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="login-password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
