<?php
include 'db_config.php';

// Admin authentication check
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flight_number = $_POST['flight_number'];
    $destination = $_POST['destination'];
    $departure_place = $_POST['departure_place'];
    $departure_date = $_POST['departure_date'];
    $departure_time = $_POST['departure_time'];
    $arrival_date = $_POST['arrival_date'];
    $arrival_time = $_POST['arrival_time'];
    $first_class_passengers = $_POST['first_class_passengers'];
    $first_class_price = $_POST['first_class_price'];
    $business_class_passengers = $_POST['business_class_passengers'];
    $business_class_price = $_POST['business_class_price'];
    $economy_class_passengers = $_POST['economy_class_passengers'];
    $economy_class_price = $_POST['economy_class_price'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO flights 
        (flight_number, departure_place, destination, departure_date, departure_time, arrival_date, arrival_time, 
        first_class_passengers, first_class_price, business_class_passengers, business_class_price, 
        economy_class_passengers, economy_class_price) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Correct type definition string for bind_param
    $stmt->bind_param("sssssssssssss", 
        $flight_number, $departure_place, $destination, $departure_date, $departure_time, 
        $arrival_date, $arrival_time, $first_class_passengers, $first_class_price, 
        $business_class_passengers, $business_class_price, $economy_class_passengers, $economy_class_price);

    if ($stmt->execute()) {
        echo "<p class='success'>Flight added successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Flight</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
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
            filter: blur(4px);
        }

        .container {
            width: 90%;
            max-width: 800px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 28px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"] {
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border 0.3s ease;
            width: 100%;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus,
        input[type="number"]:focus {
            border-color: #3498db;
            outline: none;
        }

        input[type="submit"], .back-button {
            background: #3498db;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        input[type="submit"]:hover,
        .back-button:hover {
            background-color: #2980b9;
        }

        .success, .error {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
        }

        .success {
            color: #27ae60;
            background-color: rgba(39, 174, 96, 0.1);
        }

        .error {
            color: #e74c3c;
            background-color: rgba(231, 76, 60, 0.1);
        }

        .back-button {
            background: #e74c3c;
            margin-bottom: 20px; /* Spacing between back button and form */
        }

        @media (max-width: 768px) {
            input[type="text"],
            input[type="date"],
            input[type="time"],
            input[type="number"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Background video -->
    <video class="bg-video" autoplay muted loop>
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <a href="admin_home.php" class="back-button">Back</a> <!-- Back Button -->
        <h2>Add New Flight</h2>
        <form method="POST" action="">
            <input type="text" name="flight_number" placeholder="Flight Number" required>
            <input type="text" name="destination" placeholder="Destination" required>
            <input type="text" name="departure_place" placeholder="Departure Place" required>
            <input type="date" name="departure_date" required>
            <input type="time" name="departure_time" required>
            <input type="date" name="arrival_date" required>
            <input type="time" name="arrival_time" required>
            <input type="number" name="first_class_passengers" placeholder="First Class Passengers" required>
            <input type="number" step="0.01" name="first_class_price" placeholder="First Class Price" required>
            <input type="number" name="business_class_passengers" placeholder="Business Class Passengers" required>
            <input type="number" step="0.01" name="business_class_price" placeholder="Business Class Price" required>
            <input type="number" name="economy_class_passengers" placeholder="Economy Class Passengers" required>
            <input type="number" step="0.01" name="economy_class_price" placeholder="Economy Class Price" required>
            <input type="submit" value="Add Flight">
        </form>
    </div>
</body>
</html>
