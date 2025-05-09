<?php
session_start();
include 'db_config.php'; // Include your database connection file

// Admin authentication check
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get flight ID from query string
$flight_id = isset($_GET['flight_id']) ? intval($_GET['flight_id']) : 0;

// Fetch passengers for the given flight
$sql = "SELECT passengers.*, bookings.ticket_number 
        FROM passengers
        JOIN bookings ON passengers.booking_id = bookings.id
        WHERE bookings.flight_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $flight_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Passengers</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            overflow-y: auto; /* Enable vertical scroll for the entire page */
            position: relative;
            min-height: 100vh; /* Ensure body covers the full viewport height */
        }

        /* Background Image */
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
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .back-button, .print-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px 0;
            text-decoration: none;
        }

        .back-button:hover, .print-button:hover {
            background-color: #2980b9;
        }

        .print-button {
            float: right; /* Position print button to the right */
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <!-- Background video -->
    <video class="bg-video" autoplay muted loop>
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <h2>Passengers for Flight ID: <?php echo htmlspecialchars($flight_id); ?></h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Booking ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Ticket Number</th> <!-- Added Ticket Number Column -->
                <th>Actions</th>
            </tr>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['booking_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['gender']}</td>
                        <td>{$row['ticket_number']}</td> <!-- Display Ticket Number -->
                        <td class='actions'>
                            <a href='edit_passenger.php?id={$row['id']}'>Edit</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>

        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
        <button class="print-button" onclick="printPage()">Print</button> <!-- Print Button -->
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
