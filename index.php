<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Booking System - Homepage</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            animation: fadeIn 1s ease-in-out; /* Fade in animation for the entire page */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        #background-video {
            position: absolute;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            filter: brightness(50%);
        }

        .logo {
            max-width: 150px;
            margin: 20px auto;
            display: block;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5); /* Added shadow on hover */
        }

        .header {
            background-color: rgba(0, 192, 255, 0.9);
            padding: 20px;
            text-align: center;
            font-size: 2.5em;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            margin: 20px;
            animation: slideIn 1s ease; /* Slide in animation for the header */
        }

        @keyframes slideIn {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.8);
            overflow: hidden;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            margin: 0 20px;
            animation: fadeIn 1.5s ease; /* Fade in for navbar */
        }

        .navbar a {
            font-size: 1.2em;
            font-weight: bold;
            text-decoration: none;
            color: #333;
            padding: 14px 20px;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease;
        }

        .navbar a:hover {
            background-color: rgba(0, 192, 255, 0.5);
            color: white;
            transform: scale(1.1); /* Scale effect on hover */
        }

        .content {
            padding: 50px;
            text-align: center;
            color: white;
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
            animation: fadeIn 2s ease; /* Fade in for content */
        }

        .content p {
            margin: 15px 0;
            font-size: 1.2em;
            line-height: 1.6;
            animation: popIn 0.5s ease; /* Pop in animation for paragraphs */
        }

        @keyframes popIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        footer {
            background-color: rgba(0, 192, 255, 0.9);
            padding: 15px;
            position: relative;
            width: 100%;
            text-align: center;
            color: white;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.3);
            margin-top: auto;
            animation: fadeIn 3s ease; /* Fade in for footer */
        }

        .footer-button {
            background-color: magenta;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1em;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .footer-button:hover {
            background-color: #ddd;
            color: black;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .header {
                font-size: 2em;
            }

            .navbar a {
                font-size: 1em;
            }

            .content p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>

    <!-- Background video -->
    <video autoplay muted loop id="background-video">
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <!-- Logo Section -->
    <img src="image1.jpg" alt="Logo" class="logo">

    <!-- Header Section -->
    <div class="header">
        Welcome to Flight Booking System
    </div>

    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="admin_login.php">Admin</a>
        <a href="contact.php">Contact Us</a>
        <a href="login.php">Login</a>
        <a href="view_ticket.php">View Ticket</a>
    </div>

    <!-- Main Content Section -->
    <div class="content">
        <p>Explore and book flights conveniently. Our platform offers an easy-to-use interface that allows you to search, compare, and book flights from various airlines around the world.</p>
        <p>Whether you're traveling for business or leisure, our system helps you find the best deals and provides seamless booking experiences. Join us today and make your travel plans hassle-free!</p>
    </div>

    <!-- Footer Section -->
    <footer>
        <a href="terms.php" class="footer-button">Terms & Conditions</a>
        <p>&copy; 2024 Bookstore Inc. All rights reserved.</p>
    </footer>

</body>
</html>
