<?php
session_start();
include "connection.php";
$error = '';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    if (isset($_POST['name'], $_POST['password'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];

        
        $stmt = $con->prepare("SELECT * FROM admin WHERE name = ? AND password = ?");
        $stmt->bind_param("ss", $name, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            
            $_SESSION['name'] = $name;
            $_SESSION['log'] = true;
            header("Location: admin_page.php");
            exit();
        } else {
           
            $error = "Invalid login credentials";
        }

        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}


$con->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN LOGIN</title>
    <link rel="stylesheet" href="style.css"> 

    <?php if ($error): ?>
    <script>
        alert("<?php echo addslashes($error); ?>");
    </script>
<?php endif; ?>


<div class="bg-image"></div>
<div class="login-container">
    <h1>LOGIN</h1>
    <form  method="post">
        <input class="login-container text" type="text" id="email" name="name" placeholder="Enter your name" autofocus>
        <input class="login-container passw" type="password" id="pass" name="password" placeholder="Password">
        <input type="checkbox" id="showpass" onclick="showPassword()" >
        <button id="otpbutton">LOGIN</button>
    </form>
   
</div>
<div id="blur"></div>

<script>
    function showPassword() {
        var x = document.getElementById("pass");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
</html>

<style>body {
  font-family: Arial, sans-serif;
  
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
 
}

.bg-image {
  position: absolute;
display: flex;
  background-image: url(bg.jpeg);
  height: 100%;
  width: 100%;
background-size: cover;
  background-repeat: no-repeat;
  filter: blur(2px); 
  z-index: -2;
}

.login-container {
 backdrop-filter: blur(12px);
  border: 3px solid;
  padding: 20px;
  border-color: beige;
  border-radius: 30px;
  box-shadow: 0 0 10px rgba(78, 78, 78, 0.1);
  width: 300px;
  height: 280px;
}

h1 {
  text-align: center;
  margin-bottom: 20px;

}

input[type="text"] {
  width: 90%;
  height: 20px;
  margin-bottom: 20px;
  padding: 10px;
  border: 2px solid;
  border-radius: 100px;
  border-color: rgb(150, 150, 10);
  text-align: center;
}
.passw{
  width: 90%;
  height: 20px;
  margin-bottom: 20px;
  padding: 10px;
  border: 2px solid;
  border-radius: 100px;
  border-color: rgb(150, 150, 10);
  text-align: center;
  position: relative;
}
#otpbutton{

  position: relative;
  left:100px;
  width:100px;
height: 30px;
border-radius: 100px;
border: 2px solid;
border-radius: 100px;
background-color: white;
border-color: rgb(150, 150, 10);
}

#showpass {
  position: absolute;
  right: 70px;
  top: 210px;
  display: flex;
  text-align: center;
}</style>