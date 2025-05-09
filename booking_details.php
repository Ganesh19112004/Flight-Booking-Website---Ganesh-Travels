<?php
session_start();
include 'db_config.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if booking_id is provided
if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);

    // Fetch booking details
    $stmt = $conn->prepare("SELECT b.*, f.flight_number, f.departure_date, f.departure_time, f.arrival_date, f.arrival_time,
        f.departure_place, f.destination, f.first_class_price, f.business_class_price, f.economy_class_price, b.ticket_number
        FROM bookings b
        JOIN flights f ON b.flight_id = f.id
        WHERE b.id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();

    if (!$booking) {
        echo "Booking not found.";
        exit();
    }

    // Fetch passenger details
    $stmt = $conn->prepare("SELECT * FROM passengers WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $passengers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Calculate total price based on class
    $price_per_person = 0;
    $class = strtolower(trim($booking['class'])); // Normalize class value

    switch ($class) {
        case 'first_class':
            $price_per_person = floatval($booking['first_class_price']);
            break;
        case 'business_class':
            $price_per_person = floatval($booking['business_class_price']);
            break;
        case 'economy_class':
            $price_per_person = floatval($booking['economy_class_price']);
            break;
        default:
            $price_per_person = 0;
            break;
    }

    $number_of_passengers = intval($booking['number_of_passengers']);
    $total_price = $price_per_person * $number_of_passengers;

    // Generate QR code with ticket number
    $qr_data = "Ticket Number: {$booking['ticket_number']}\nBooking ID: {$booking_id}\nFlight Number: {$booking['flight_number']}\nDeparture Place: {$booking['departure_place']}\nDestination: {$booking['destination']}\nDeparture Date: {$booking['departure_date']}\nDeparture Time: {$booking['departure_time']}\nArrival Date: {$booking['arrival_date']}\nArrival Time: {$booking['arrival_time']}\nClass: {$class}";
    $qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qr_data);

    $conn->close();
} else {
    echo "Booking ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
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

        .booking-details, .passenger-details {
            margin-bottom: 20px;
        }

        .booking-details p, .passenger-details p {
            font-size: 16px;
            color: #34495e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .print-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .print-button:hover {
            background-color: #2980b9;
        }

        .qr-code {
            text-align: center;
            margin-top: 20px;
        }

        .qr-code img {
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Booking Details</h2>

        <div class="booking-details">
            <h3>Booking Details</h3>
            <p><strong>Ticket Number:</strong> <?php echo htmlspecialchars($booking['ticket_number']); ?></p>
            <p><strong>Flight Number:</strong> <?php echo htmlspecialchars($booking['flight_number']); ?></p>
            <p><strong>Departure Place:</strong> <?php echo htmlspecialchars($booking['departure_place']); ?></p>
            <p><strong>Destination:</strong> <?php echo htmlspecialchars($booking['destination']); ?></p>
            <p><strong>Departure Date:</strong> <?php echo htmlspecialchars($booking['departure_date']); ?></p>
            <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($booking['departure_time']); ?></p>
            <p><strong>Arrival Date:</strong> <?php echo htmlspecialchars($booking['arrival_date']); ?></p>
            <p><strong>Arrival Time:</strong> <?php echo htmlspecialchars($booking['arrival_time']); ?></p>
            <p><strong>Class:</strong> <?php echo ucfirst(htmlspecialchars($class)); ?></p>
            <p><strong>Number of Passengers:</strong> <?php echo htmlspecialchars($booking['number_of_passengers']); ?></p>
            <p><strong>Total Price:</strong> $<?php echo number_format($total_price, 2); ?></p>
        </div>

        <div class="passenger-details">
    <h3>Passenger Details</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Ticket Number</th> <!-- Added Ticket Number Column -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($passengers as $passenger): ?>
                <tr>
                    <td><?php echo htmlspecialchars($passenger['name']); ?></td>
                    <td><?php echo htmlspecialchars($passenger['email']); ?></td>
                    <td><?php echo htmlspecialchars($passenger['age']); ?></td>
                    <td><?php echo htmlspecialchars($passenger['gender']); ?></td>
                    <td><?php echo htmlspecialchars($passenger['ticket_number']); ?></td> <!-- Display Ticket Number -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


        <div class="qr-code">
            <h3>QR Code</h3>
            <img src="<?php echo $qr_code_url; ?>" alt="QR Code">
        </div>

        <button class="print-button" onclick="printPage()">Print</button>
    </div>
</body>
</html>
