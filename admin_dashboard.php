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

// Fetch all flights
$sql = "SELECT * FROM flights";
$flights = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .admin-home-link {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            z-index: 10;
        }

        .admin-home-link:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .container {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: auto; /* Allows scrolling inside the container */
            max-height: 80vh; /* Set a max height for vertical scrolling */
        }

        .logo {
            width: 150px;
            display: block;
            margin: 0 auto 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
            letter-spacing: 1px;
        }

        h3 {
            color: #555;
            margin-bottom: 20px;
            font-size: 22px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 16px;
            overflow-x: auto;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #3498db;
            color: white;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #e1e1e1;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .actions a {
            margin-right: 10px;
        }

        .add-flight-button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 15px 0;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .add-flight-button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .logout-form {
            text-align: center;
            margin-top: 30px;
        }

        .logout-form button {
            padding: 12px 25px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .logout-form button:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        /* Ensure the whole body is scrollable if content overflows */
        html, body {
            overflow: auto;
        }

        .container::-webkit-scrollbar {
            width: 10px;
        }

        .container::-webkit-scrollbar-thumb {
            background-color: #3498db;
            border-radius: 5px;
        }

    </style>
</head>
<body>
    <video class="bg-video" autoplay muted loop>
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Admin Home Link -->
    <a href="admin_home.php" class="admin-home-link">Home</a>

    <div class="container">
        <img src="image1.jpg" class="logo" alt="Logo">
        <h2>Admin Dashboard</h2>

        <h3>Manage Flights</h3>
        <table>
            <tr>
                <th>Flight Number</th>
                <th>Departure Place</th>
                <th>Destination</th>
                <th>Departure Date</th>
                <th>Departure Time</th>
                <th>Arrival Date</th>
                <th>Arrival Time</th>
                <th>First Class Price</th>
                <th>First Class Passengers</th>
                <th>Business Class Price</th>
                <th>Business Class Passengers</th>
                <th>Economy Class Price</th>
                <th>Economy Class Passengers</th>
                <th>Actions</th>
            </tr>
            <?php
            while ($row = $flights->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['flight_number']}</td>
                        <td>{$row['departure_place']}</td>
                        <td>{$row['destination']}</td>
                        <td>{$row['departure_date']}</td>
                        <td>{$row['departure_time']}</td>
                        <td>{$row['arrival_date']}</td>
                        <td>{$row['arrival_time']}</td>
                        <td>{$row['first_class_price']}</td>
                        <td>{$row['first_class_passengers']}</td>
                        <td>{$row['business_class_price']}</td>
                        <td>{$row['business_class_passengers']}</td>
                        <td>{$row['economy_class_price']}</td>
                        <td>{$row['economy_class_passengers']}</td>
                        <td class='actions'>
                            <a href='edit_flight.php?id={$row['id']}'>Edit</a> | 
                            <a href='delete_flight.php?id={$row['id']}'>Delete</a> |
                            <a href='view_passengers.php?flight_id={$row['id']}'>View Passengers</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
        <a href="add_flight.php" class="add-flight-button">Add New Flight</a>

        <div class="logout-form">
            <form method="POST" action="">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
