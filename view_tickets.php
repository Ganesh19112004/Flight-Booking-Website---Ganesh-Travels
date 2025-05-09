<?php
include 'db_config.php';

// Assume the user is logged in and we have their ID in session
session_start();
$user_id = $_SESSION['user_id'];

$sql = "SELECT bookings.id, flights.flight_number, flights.origin, flights.destination, flights.departure_time, flights.arrival_time, flights.price 
        FROM bookings 
        JOIN flights ON bookings.flight_id = flights.id 
        WHERE bookings.user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>My Booked Tickets</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Flight Number</th><th>Origin</th><th>Destination</th><th>Departure</th><th>Arrival</th><th>Price</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['flight_number']}</td>
                        <td>{$row['origin']}</td>
                        <td>{$row['destination']}</td>
                        <td>{$row['departure_time']}</td>
                        <td>{$row['arrival_time']}</td>
                        <td>{$row['price']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No tickets booked yet.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
