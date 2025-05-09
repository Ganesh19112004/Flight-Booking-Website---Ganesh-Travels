<?php
session_start();
include 'db_config.php'; // Include your database connection file

// Admin authentication check
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <style>
        /* General Styles */
        body {
            font-family: "Arial", sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .bg-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: blur(3px);
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .dashboard-links {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .dashboard-links a {
            display: block;
            padding: 15px;
            background-color: #3498db;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .dashboard-links a:hover {
            background-color: #2980b9;
        }

        .dashboard-links a:active {
            background-color: #1e70a8;
        }

        .dashboard-links a.disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }

        .logout-form {
            margin-top: 20px;
            text-align: center;
        }

        .logout-form button {
            background-color: #e74c3c;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-form button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <!-- Background Video -->
    <video class="bg-video" autoplay muted loop>
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <h2>Welcome, Admin!</h2>
        <div class="dashboard-links">
            <a href="user_dashboard.php">User Dashboard</a>
            <a href="admin_dashboard.php">Admin dashboard</a>
            <a href="add_flight.php">Add Flight</a>
            <a href="edited_flights.php">Edited Flight</a>
            <a href="deleted_flights.php">Deleted Flights</a>
        </div>

        <div class="logout-form">
            <form method="POST" action="">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
