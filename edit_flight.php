<?php
include 'db_config.php';

// Admin authentication check
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Ensure the 'id' parameter is present
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='error'>No flight ID provided. Please go back and try again.</p>";
    exit();
}

$flight_id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flight_number = $_POST['flight_number'];
    $departure_place = $_POST['departure_place'];
    $destination = $_POST['destination'];
    $departure_date = $_POST['departure_date'];
    $departure_time = $_POST['departure_time'];
    $arrival_date = $_POST['arrival_date'];
    $arrival_time = $_POST['arrival_time'];
    $first_class_price = $_POST['first_class_price'];
    $business_class_price = $_POST['business_class_price'];
    $economy_class_price = $_POST['economy_class_price'];
    $first_class_passengers = $_POST['first_class_passengers'];
    $business_class_passengers = $_POST['business_class_passengers'];
    $economy_class_passengers = $_POST['economy_class_passengers'];

    // Debugging: Print posted data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE flights 
        SET flight_number = ?, departure_place = ?, destination = ?, departure_date = ?, departure_time = ?, 
            arrival_date = ?, arrival_time = ?, first_class_price = ?, business_class_price = ?, economy_class_price = ?, 
            first_class_passengers = ?, business_class_passengers = ?, economy_class_passengers = ? 
        WHERE id = ?");
    $stmt->bind_param("ssssssssssssss", 
        $flight_number, 
        $departure_place, 
        $destination, 
        $departure_date, 
        $departure_time, 
        $arrival_date, 
        $arrival_time, 
        $first_class_price, 
        $business_class_price, 
        $economy_class_price,
        $first_class_passengers, 
        $business_class_passengers, 
        $economy_class_passengers,
        $flight_id
    );

    if ($stmt->execute()) {
        // Insert into edited_flights table
        $stmt_edit = $conn->prepare("INSERT INTO edited_flights 
            (flight_number, departure_place, destination, departure_date, departure_time, 
            arrival_date, arrival_time, first_class_price, business_class_price, economy_class_price, 
            first_class_passengers, business_class_passengers, economy_class_passengers, edited_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt_edit->bind_param("sssssssssssss", 
            $flight_number, 
            $departure_place, 
            $destination, 
            $departure_date, 
            $departure_time, 
            $arrival_date, 
            $arrival_time, 
            $first_class_price, 
            $business_class_price, 
            $economy_class_price,
            $first_class_passengers, 
            $business_class_passengers, 
            $economy_class_passengers
        );

        if ($stmt_edit->execute()) {
            echo "<p class='success'>Flight updated successfully! <a href='edited_flights.php'>View Edited Flights</a></p>";
        } else {
            echo "<p class='error'>Error saving to edited_flights: " . $stmt_edit->error . "</p>";
        }

        $stmt_edit->close();
    } else {
        echo "<p class='error'>Error updating flight: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Fetch flight details for the given id
    $stmt = $conn->prepare("SELECT * FROM flights WHERE id = ?");
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "<p class='error'>Flight not found. Please go back and try again.</p>";
        $stmt->close();
        $conn->close();
        exit();
    }

    $flight = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Flight</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 800px;
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

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"] {
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

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #2ecc71;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #27ae60;
        }

        .success, .error {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
        }

        .success {
            color: #27ae60;
            background-color: rgba(39, 174, 96, 0.1);
        }

        .error {
            color: #e74c3c;
            background-color: rgba(231, 76, 60, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_home.php" class="back-button">Back to Home</a>
        <h2>Edit Flight</h2>
        <form method="POST" action="">
            <label for="flight_number">Flight Number:</label>
            <input type="text" name="flight_number" id="flight_number" value="<?php echo htmlspecialchars($flight['flight_number']); ?>" required>

            <label for="departure_place">Departure Place:</label>
            <input type="text" name="departure_place" id="departure_place" value="<?php echo htmlspecialchars($flight['departure_place']); ?>" required>

            <label for="destination">Destination:</label>
            <input type="text" name="destination" id="destination" value="<?php echo htmlspecialchars($flight['destination']); ?>" required>

            <label for="departure_date">Departure Date:</label>
            <input type="date" name="departure_date" id="departure_date" value="<?php echo htmlspecialchars($flight['departure_date']); ?>" required>

            <label for="departure_time">Departure Time:</label>
            <input type="time" name="departure_time" id="departure_time" value="<?php echo htmlspecialchars($flight['departure_time']); ?>" required>

            <label for="arrival_date">Arrival Date:</label>
            <input type="date" name="arrival_date" id="arrival_date" value="<?php echo htmlspecialchars($flight['arrival_date']); ?>" required>

            <label for="arrival_time">Arrival Time:</label>
            <input type="time" name="arrival_time" id="arrival_time" value="<?php echo htmlspecialchars($flight['arrival_time']); ?>" required>

            <label for="first_class_price">First Class Price:</label>
            <input type="number" step="0.01" name="first_class_price" id="first_class_price" value="<?php echo htmlspecialchars($flight['first_class_price']); ?>" required>

            <label for="first_class_passengers">First Class Passengers:</label>
            <input type="number" name="first_class_passengers" id="first_class_passengers" value="<?php echo htmlspecialchars($flight['first_class_passengers']); ?>" required>

            <label for="business_class_price">Business Class Price:</label>
            <input type="number" step="0.01" name="business_class_price" id="business_class_price" value="<?php echo htmlspecialchars($flight['business_class_price']); ?>" required>

            <label for="business_class_passengers">Business Class Passengers:</label>
            <input type="number" name="business_class_passengers" id="business_class_passengers" value="<?php echo htmlspecialchars($flight['business_class_passengers']); ?>" required>

            <label for="economy_class_price">Economy Class Price:</label>
            <input type="number" step="0.01" name="economy_class_price" id="economy_class_price" value="<?php echo htmlspecialchars($flight['economy_class_price']); ?>" required>

            <label for="economy_class_passengers">Economy Class Passengers:</label>
            <input type="number" name="economy_class_passengers" id="economy_class_passengers" value="<?php echo htmlspecialchars($flight['economy_class_passengers']); ?>" required>

            <input type="submit" value="Update Flight">
        </form>
    </div>
</body>
</html>
