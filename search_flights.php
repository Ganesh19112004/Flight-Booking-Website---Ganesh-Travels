<?php
session_start();
include 'db_config.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Flights</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 600px;
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

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #34495e;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 15px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        /* Focus Styles */
        input:focus {
            border-color: #3498db;
            outline: none;
        }

        /* Logout Button Styles */
        .header {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            background-color: #2c3e50;
        }

        .header a {
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 4px;
            background-color: #e74c3c;
            transition: background-color 0.3s;
        }

        .header a:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="?logout=true">Logout</a>
    </div>
    <div class="container">
        <h2>Search Flights</h2>
        <form method="POST" action="select_flight.php">
            <label for="departure_place">Departure Place:</label>
            <input type="text" name="departure_place" id="departure_place" required><br>

            <label for="destination">Destination:</label>
            <input type="text" name="destination" id="destination" required><br>

            <label for="departure_date">Departure Date:</label>
            <input type="date" name="departure_date" id="departure_date" required><br>

            <input type="submit" value="Search Flights">
        </form>
    </div>
</body>
</html>
