<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIKTC Seminar Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #2C2F33;
            color: #EAEAEA;
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        header {
            background-color: rgba(229, 219, 210, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 10;
            animation: fadeInDown 0.5s ease forwards;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        header h1 {
            font-size: 2.5rem;
            color: #5E0606;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .login-container {
            border-radius: 10px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            min-height: 100vh;
            width: 100vw;
            position: relative;
            padding: 20px;
            overflow: hidden;
        }

        .login-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('17.jpeg');
            background-position: center;
            background-size: cover;
            filter: brightness(0.5);
            z-index: -1;
        }

        .login-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: slideIn 0.8s ease forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .login-button {
            background-color: #8B0000;
            color: #fff;
            border: none;
            padding: 20px 50px;
            margin: 12px;
            border-radius: 30px;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s, color 0.3s;
            width: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-button:hover {
            background-color: #cf3434;
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
            color: #FFD700;
        }

        .login-button i {
            margin-right: 10px;
            transition: transform 0.3s;
        }

        .login-button:hover i {
            transform: rotate(15deg);
        }

        .description-box {
            background-color: rgba(49, 45, 45, 0.9);
            color: #fbf2f2;
            padding: 20px;
            border-radius: 10px;
            max-width: 350px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            z-index: 1;
            animation: fadeIn 1s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .description-box h2 {
            color: #ffcd39;
            margin-bottom: 10px;
        }

        .description-box p {
            font-size: 1rem;
            line-height: 1.5;
        }

        .extra-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
            margin: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 8px;
        }

        .column {
            text-align: left;
            width: 200px;
            margin: 10px;
        }

        h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            font-size: 12px;
            margin-bottom: 10px;
        }

        .end-of-page {
            background-color: #711010;
            color: #c7d314;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            margin-top: 20px;
        }

        @media (max-width: 600px) {
            .login-container {
                flex-direction: column;
            }

            .login-button {
                margin-bottom: 20px;
                width: 80%;
            }

            .extra-box {
                flex-direction: column;
                align-items: center;
            }

            .column {
                width: 100%;
                margin-bottom: 20px;
            }
        }

        .extra-box a {
            color: rgb(255, 255, 255);
            text-decoration: none;
            transition: color 0.3s;
        }

        .extra-box a:hover {
            color: #ffcd39;
            text-decoration: underline;
        }

        /* Slider styles */
        .slider {
            position: absolute;
            left: 5%;
            padding: 0%;
            width: 500px;
            height: 300px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .slides {
            display: flex;
            animation: slide 20s infinite;
        }

        .slide {
            flex-shrink: 0;
            width: 100%;
        }

        .slide img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        @keyframes slide {
            0% {
                transform: translateX(0%);
            }

            25% {
                transform: translateX(-100%);
            }

            50% {
                transform: translateX(-200%);
            }

            75% {
                transform: translateX(-300%);
            }

            100% {
                transform: translateX(0%);
            }
        }

    </style>
</head>

<body>
    <header>
        <h1>AIKTC Seminar Booking</h1>
    </header>

    <div class="login-container">
        <div class="login-buttons">
            <button onclick="location.href='admlogin.php'" class="login-button" id="admin-login">
                <i class="fas fa-crown"></i><br> Admin Login
            </button>
            <button onclick="location.href='login.php'" class="login-button" id="professor-login">
                <i class="fas fa-user-tie"></i><br> Professor Login
            </button>
        </div>
        <div class="description-box">
            <h2>About AIKTC Seminar Booking</h2>
            <p>This platform allows professors and admins to book seminars, view schedules, and manage events efficiently.</p>
            <p>Stay updated on upcoming events and ensure smooth planning and coordination.</p>
            <p>Maximum Capacity of 100 seatings.</p>
        </div>
    </div>

    <div class="extra-box">
        <div class="column">
            <h2>Photos</h2>
            <div class="slider">
                <div class="slides">
                    <div class="slide">
                        <img src="seminar1.jpg" alt="Seminar 1">
                    </div>
                    <div class="slide">
                        <img src="seminar2.jpg" alt="Seminar 2">
                    </div>
                    <div class="slide">
                        
                        <img src="seminar1.jpg" alt="Seminar 3">
                    </div>
                    <div class="slide">
                        <img src="seminar2.jpg" alt="Seminar 4">
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="https://anjumaniislam.org/">Anjuman-I-Islam</a></li>
                <li><a href="https://www.aicte-india.org/">AICTE</a></li>
                <li><a href="https://dte.maharashtra.gov.in/">Directorate of Technical Education</a></li>
                <li><a href="https://mu.ac.in/">University of Mumbai</a></li>
                <li><a href="https://www.coa.gov.in/">Council of Architecture</a></li>
                <li><a href="https://www.pci.nic.in/">Pharmacy Council of India</a></li>
                <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSeSTda6gy87PejW_iFJ9DZ9l28tGPTh2DKX6AXYQPHZGepasQ/viewform">Online Grievance Redressal</a></li>
                <li><a href="https://www.nbaind.org/">National Board of Accreditation</a></li>
                <li><a href="http://www.naac.gov.in/index.php/en/">NAAC</a></li>
                <li><a href="https://www.aicte-india.org/feedback/students.php">AICTE Student Feedback</a></li>
                <li><a href="https://www.aicte-india.org/feedback/faculty.php">AICTE Faculty Feedback</a></li>
            </ul>
        </div>
        <div class="column">
            <h2>Contact Us</h2>
            <ul>
                <li class="phone">+91 8788685686</li>
                <li class="email">email@aiktc.ac.in</li>
                <li class="location">Plot No. 2 & 3, Sector-16, Near Thana Naka, Khandagao, New Panvel, Navi Mumbai</li>
            </ul>
        </div>
    </div>

    <footer class="end-of-page">
        &copy; 2024 All rights reserved.
    </footer>
</body>

</html>
