<?php
session_start();
include 'db_config.php'; // Include your database connection file

// Admin authentication check
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['edited_flight_id'])) {
    $edited_flight_id = intval($_GET['edited_flight_id']);

    // Fetch passengers associated with the edited flight, including ticket_number
    $sql = "SELECT name, email, age, gender, ticket_number FROM edited_passengers WHERE edited_flight_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edited_flight_id);
    $stmt->execute();
    $passengers = $stmt->get_result();
} else {
    header("Location: edited_flights.php?message=No+edited+flight+ID+provided");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edited Flight Passengers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Background video styling */
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
            max-width: 1200px;
            margin: 20px auto;
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

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
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
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #e9ecef;
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
        <div class="admin-home-link">
            <a href="admin_home.php">Admin Home</a>
        </div>
        <h2>Passengers of Edited Flight</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Ticket Number</th> <!-- Added Ticket Number Column -->
            </tr>
            <?php
            while ($row = $passengers->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['gender']}</td>
                        <td>{$row['ticket_number']}</td> <!-- Display Ticket Number -->
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
