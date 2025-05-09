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

    // Generate random ticket number (for example: TKT followed by 10 random digits)
    $ticket_number = 'TKT' . mt_rand(1000000000, 9999999999);

    // Save the booking with the generated ticket number
    $stmt = $conn->prepare("UPDATE bookings SET ticket_number = ? WHERE id = ?");
    $stmt->bind_param("si", $ticket_number, $booking_id);
    $stmt->execute();
    $stmt->close();

    // Fetch booking details
    $stmt = $conn->prepare("
        SELECT b.*, f.flight_number, f.departure_date, f.departure_time, f.arrival_date, f.arrival_time,
               f.departure_place, f.destination, f.first_class_price, f.business_class_price, f.economy_class_price,
               f.first_class_passengers, f.business_class_passengers, f.economy_class_passengers
        FROM bookings b
        JOIN flights f ON b.flight_id = f.id
        WHERE b.id = ?
    ");
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

    // Update each passenger with the same ticket number
    foreach ($passengers as $passenger) {
        $stmt = $conn->prepare("UPDATE passengers SET ticket_number = ? WHERE id = ?");
        $stmt->bind_param("si", $ticket_number, $passenger['id']);
        $stmt->execute();
        $stmt->close();
    }

    // Generate QR code with ticket number
    $qr_data = "Ticket Number: {$ticket_number}\nBooking ID: {$booking_id}\nFlight Number: {$booking['flight_number']}\nDeparture Place: {$booking['departure_place']}\nDestination: {$booking['destination']}\nDeparture Date: {$booking['departure_date']}\nDeparture Time: {$booking['departure_time']}\nArrival Date: {$booking['arrival_date']}\nArrival Time: {$booking['arrival_time']}\nClass: {$booking['class']}";
    $qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qr_data);
} else {
    echo "Booking ID not provided.";
    exit();
}

// Determine price based on class
$class = htmlspecialchars($booking['class']);
$price = 0;

switch ($class) {
    case 'first_class':
        $price = htmlspecialchars($booking['first_class_price']);
        break;
    case 'business_class':
        $price = htmlspecialchars($booking['business_class_price']);
        break;
    case 'economy_class':
        $price = htmlspecialchars($booking['economy_class_price']);
        break;
    default:
        $price = 'N/A'; // Handle unexpected class values
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
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

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 200px;
            height: auto;
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
        <div class="logo">
            <img src="image1.jpg" alt="Logo">
        </div>

        <h2>Booking Confirmation</h2>

        <div class="booking-details">
            <h3>Booking Details</h3>
            <p><strong>Ticket Number:</strong> <?php echo htmlspecialchars($ticket_number); ?></p>
            <p><strong>Flight Number:</strong> <?php echo htmlspecialchars($booking['flight_number']); ?></p>
            <p><strong>Departure Place:</strong> <?php echo htmlspecialchars($booking['departure_place']); ?></p>
            <p><strong>Destination:</strong> <?php echo htmlspecialchars($booking['destination']); ?></p>
            <p><strong>Departure Date:</strong> <?php echo htmlspecialchars($booking['departure_date']); ?></p>
            <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($booking['departure_time']); ?></p>
            <p><strong>Arrival Date:</strong> <?php echo htmlspecialchars($booking['arrival_date']); ?></p>
            <p><strong>Arrival Time:</strong> <?php echo htmlspecialchars($booking['arrival_time']); ?></p>
            <p><strong>Class:</strong> <?php echo ucfirst($class); ?></p>
            <p><strong>Price:</strong> $<?php echo $price; ?></p>
            <p><strong>Number of Passengers:</strong> <?php echo htmlspecialchars($booking['number_of_passengers']); ?></p>
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
                            <td><?php echo htmlspecialchars($ticket_number); ?></td> <!-- Display the same Ticket Number -->
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
