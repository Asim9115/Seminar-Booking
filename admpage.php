<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to login page
    header('Location: admlogin.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <link rel="stylesheet" href="admin_page.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-section">
                <h2>Welcome Admin</h2>
            </div>
            <nav class="navigation">
                <a href="#events" class="nav-item">Ongoing Event</a>
                <a href="#approval" class="nav-item">Approval</a>
                <a href="#declined" class="nav-item">Declined</a>
                <a href="#history" class="nav-item">History</a>
            </nav>
        </aside>
        <main class="content">
            <div id="events" class="content-section">
                <h3>Events</h3>
                <div class="event">
                    
                </div>
            </div>
            <div id="approval" class="content-section">
                <h3>Approval Section</h3>
            </div>
            <div id="declined" class="content-section">
                <h3>Declined Section</h3>
            </div>
            <div id="history" class="content-section">
                <h3>History Section</h3>
            </div>
            <div id="logout"class = "content-section" ><h3>logout</h3></div>
        </main>
    </div>
     <script>
        const navItems = document.querySelectorAll('.nav-item');
        
        document.querySelector('#events').style.display = 'block';
        
        navItems.forEach((navItem) => {
            navItem.addEventListener('click', (e) => {
                e.preventDefault();
                
                const href = navItem.getAttribute('href');
                
                document.querySelectorAll('.content-section').forEach((section) => {
                    section.style.display = 'none';
                });
                
                if (href) {
                    document.querySelector(href).style.display = 'block';
                }
            });
        });
       
        

    </script>
</body>
</html>

<style>
    body {
    position: relative;
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    height: 100vh;
}

.container {
    display: flex;
    width: 100%;
    max-width: 1200px;
    margin: auto;
    height: 100%;
}

.sidebar {
    position:absolute; 
    left: 0%;
    width: 250px;
    background-color: #333;
    color: #fff;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.sidebar-section {
    margin-bottom: 20px;
}

.sidebar-section h2 {
    font-size: 20px;
    margin: 0;
}

.navigation {
    flex: 1;
}

.nav-item {
  
    left: 0%;
    display: block;
    color: #fff;
    text-decoration: none;
    padding: 15px;
    margin: 5px 0;
    background-color: #444;
    border-radius: 5px;
    text-align: center;
}

.nav-item:hover {
    background-color: #555;
}

.content {
    flex: 1;
    padding: 20px;
    background-color: #f9f9f9;
}

.content-section {
    position: absolute;
    left: 300px;
    margin-bottom: 20px;
}

.content-section h3 {
    margin-top: 0;
}
.content-section {
    display: none;
}
</style>