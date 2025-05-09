<?php
include 'db_config.php';

// Admin authentication check
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Check if 'id' parameter is present
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $message = "No flight ID provided. Please go back and try again.";
    $message_type = "error";
} else {
    $flight_id = intval($_GET['id']);

    // Check if the deletion was confirmed
    if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] === 'yes') {
        // Begin transaction
        $conn->begin_transaction();

        try {
            // Fetch flight details
            $stmt = $conn->prepare("SELECT * FROM flights WHERE id = ?");
            $stmt->bind_param("i", $flight_id);
            $stmt->execute();
            $flight_result = $stmt->get_result();
            $flight_data = $flight_result->fetch_assoc();
            $stmt->close();

            // Insert into deleted_flights table
            $stmt = $conn->prepare("
                INSERT INTO deleted_flights (
                    flight_number, departure_place, destination, departure_date, departure_time, arrival_date, arrival_time, class, price, 
                    first_class_passengers, first_class_price, business_class_passengers, business_class_price, economy_class_passengers, economy_class_price
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "sssssssssssssss",
                $flight_data['flight_number'], $flight_data['departure_place'], $flight_data['destination'],
                $flight_data['departure_date'], $flight_data['departure_time'], $flight_data['arrival_date'],
                $flight_data['arrival_time'], $flight_data['class'], $flight_data['price'],
                $flight_data['first_class_passengers'], $flight_data['first_class_price'],
                $flight_data['business_class_passengers'], $flight_data['business_class_price'],
                $flight_data['economy_class_passengers'], $flight_data['economy_class_price']
            );
            $stmt->execute();
            $deleted_flight_id = $stmt->insert_id;
            $stmt->close();

            // Fetch passengers of the flight
            $stmt = $conn->prepare("SELECT * FROM passengers WHERE booking_id = ?");
            $stmt->bind_param("i", $flight_id);
            $stmt->execute();
            $passenger_result = $stmt->get_result();

            // Insert passengers into deleted_passengers table
            $stmt = $conn->prepare("INSERT INTO deleted_passengers (deleted_flight_id, name, email, age, gender) VALUES (?, ?, ?, ?, ?)");
            while ($passenger = $passenger_result->fetch_assoc()) {
                $stmt->bind_param("issis", $deleted_flight_id, $passenger['name'], $passenger['email'], $passenger['age'], $passenger['gender']);
                $stmt->execute();
            }
            $stmt->close();

            // Delete the flight from the flights table
            $stmt = $conn->prepare("DELETE FROM flights WHERE id = ?");
            $stmt->bind_param("i", $flight_id);
            $stmt->execute();
            $stmt->close();

            // Commit transaction
            $conn->commit();
            $message = "Flight deleted successfully!";
            $message_type = "success";
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
            $message_type = "error";
        }

        $conn->close();

        // Redirect after 3 seconds
        header("refresh:3; url=admin_dashboard.php");
    } else {
        $message = "Are you sure you want to delete this flight?";
        $message_type = "warning";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Deletion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .success {
            color: #27ae60;
        }

        .error {
            color: #e74c3c;
        }

        .warning {
            color: #f39c12;
        }

        p {
            font-size: 18px;
        }

        .redirect {
            margin-top: 20px;
            font-size: 16px;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-group {
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Flight Deletion Status</h2>
        <p class="<?php echo $message_type; ?>"><?php echo $message; ?></p>
        <?php if ($message_type === 'warning'): ?>
            <form method="POST">
                <div class="btn-group">
                    <button type="submit" name="confirm_delete" value="yes" class="btn btn-danger">Yes, Delete</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php elseif ($message_type !== 'warning'): ?>
            <div class="redirect">
                You will be redirected to the admin dashboard in a few seconds. 
                <a href="admin_dashboard.php">Click here if you are not redirected automatically.</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
