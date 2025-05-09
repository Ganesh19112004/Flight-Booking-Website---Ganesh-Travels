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

// Fetch deleted flights with error handling
$sql = "SELECT * FROM deleted_flights";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted Flights</title>
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
            filter: blur(3px);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin: 20px 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .admin-home-link {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .admin-home-link a {
            background-color: #3498db;
            color: #ffffff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .admin-home-link a:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #3498db;
            color: white;
            font-size: 16px;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #e9ecef;
        }

        .actions a {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }

        .actions a:hover {
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
</head>
<body>
<video class="bg-video" autoplay muted loop>
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <div class="admin-home-link">
            <a href="admin_home.php">Admin Home</a>
        </div>
        <h2>Deleted Flights</h2>
        <table>
            <tr>
                <th>Flight Number</th>
                <th>Departure Place</th>
                <th>Destination</th>
                <th>Departure Date</th>
                <th>Departure Time</th>
                <th>Arrival Date</th>
                <th>Arrival Time</th>
                <th>Class</th>
                <th>Price</th>
                <th>First Class Passengers</th>
                <th>First Class Price</th>
                <th>Business Class Passengers</th>
                <th>Business Class Price</th>
                <th>Economy Class Passengers</th>
                <th>Economy Class Price</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch and display results
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['flight_number']}</td>
                        <td>{$row['departure_place']}</td>
                        <td>{$row['destination']}</td>
                        <td>{$row['departure_date']}</td>
                        <td>{$row['departure_time']}</td>
                        <td>{$row['arrival_date']}</td>
                        <td>{$row['arrival_time']}</td>
                        <td>{$row['class']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['first_class_passengers']}</td>
                        <td>{$row['first_class_price']}</td>
                        <td>{$row['business_class_passengers']}</td>
                        <td>{$row['business_class_price']}</td>
                        <td>{$row['economy_class_passengers']}</td>
                        <td>{$row['economy_class_price']}</td>
                        <td><a href='view_deleted_passengers.php?deleted_flight_id={$row['id']}'>View Passengers</a></td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
