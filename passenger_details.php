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

// Check if the necessary data was posted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_id = $_POST['flight_id'];
    $class = $_POST['class'];
    $number_of_passengers = $_POST['number_of_passengers'];
    $departure_place = $_POST['departure_place'];
    $destination = $_POST['destination'];
    $departure_date = $_POST['departure_date'];

    // Fetch the selected flight details
    $stmt = $conn->prepare("SELECT * FROM flights WHERE id = ?");
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $flight = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} else {
    header("Location: select_flight.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Details</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 15px 20px;
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

        .container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
        }

        /* Flight Details Styles */
        .flight-details {
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .flight-details h3 {
            margin: 0 0 15px 0;
            color: #007bff;
            font-size: 22px;
        }

        .flight-details p {
            margin: 6px 0;
            color: #495057;
            font-size: 16px;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .passenger-section {
            background-color: #f1f3f5;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .passenger-section h3 {
            margin-bottom: 15px;
            color: #343a40;
            font-size: 20px;
        }

        label {
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
            display: block;
            font-size: 15px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
            background-color: #ffffff;
            color: #495057;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #80bdff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }

        input[type="submit"] {
            width: 100%;
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 15px;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .flight-details h3,
            .passenger-section h3 {
                font-size: 18px;
            }

            label,
            input[type="text"],
            input[type="email"],
            input[type="number"],
            select,
            input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="?logout=true">Logout</a>
    </div>

    <div class="container">
        <h2>Passenger Details</h2>

        <div class="flight-details">
            <h3>Flight Details</h3>
            <p><strong>Flight Number:</strong> <?php echo htmlspecialchars($flight['flight_number']); ?></p>
            <p><strong>From:</strong> <?php echo htmlspecialchars($departure_place); ?></p>
            <p><strong>To:</strong> <?php echo htmlspecialchars($destination); ?></p>
            <p><strong>Departure Date:</strong> <?php echo htmlspecialchars($departure_date); ?></p>
            <p><strong>Class:</strong> <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($class))); ?></p>
            <p><strong>Number of Passengers:</strong> <?php echo htmlspecialchars($number_of_passengers); ?></p>
        </div>

        <form method="POST" action="process_booking.php">
            <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($flight_id); ?>">
            <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">
            <input type="hidden" name="number_of_passengers" value="<?php echo htmlspecialchars($number_of_passengers); ?>">
            <input type="hidden" name="departure_place" value="<?php echo htmlspecialchars($departure_place); ?>">
            <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
            <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($departure_date); ?>">

            <?php for ($i = 1; $i <= $number_of_passengers; $i++): ?>
                <div class="passenger-section">
                    <h3>Passenger <?php echo $i; ?></h3>

                    <label for="passenger_name_<?php echo $i; ?>">Name:</label>
                    <input type="text" name="passenger_name_<?php echo $i; ?>" id="passenger_name_<?php echo $i; ?>" required>

                    <label for="passenger_email_<?php echo $i; ?>">Email:</label>
                    <input type="email" name="passenger_email_<?php echo $i; ?>" id="passenger_email_<?php echo $i; ?>" required>

                    <label for="passenger_age_<?php echo $i; ?>">Age:</label>
                    <input type="number" name="passenger_age_<?php echo $i; ?>" id="passenger_age_<?php echo $i; ?>" min="1" required>

                    <label for="passenger_gender_<?php echo $i; ?>">Gender:</label>
                    <select name="passenger_gender_<?php echo $i; ?>" id="passenger_gender_<?php echo $i; ?>" required>
                        <option value="">--Select Gender--</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            <?php endfor; ?>

            <input type="submit" value="Confirm Booking">
        </form>
    </div>
</body>
</html>
