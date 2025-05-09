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

// Process the search form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $departure_place = $_POST['departure_place'];
    $destination = $_POST['destination'];
    $departure_date = $_POST['departure_date'];

    // Convert the selected date to timestamp
    $departure_timestamp = strtotime($departure_date);

    // Create an array to store nearby dates (+/- 3 days)
    $date_range = [];
    for ($i = -3; $i <= 3; $i++) {
        $date_range[] = date('Y-m-d', strtotime("$i day", $departure_timestamp));
    }

    // Prepare query for nearby dates
    $placeholders = implode(',', array_fill(0, count($date_range), '?'));
    $query = "SELECT * FROM flights WHERE departure_place = ? AND destination = ? AND departure_date IN ($placeholders)";
    $stmt = $conn->prepare($query);
    
    // Dynamically bind all dates along with departure_place and destination
    $params = array_merge([$departure_place, $destination], $date_range);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store fetched flights
    $flights = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $flights[$row['departure_date']][] = $row;
        }
    }

    $stmt->close();
} else {
    header("Location: search_flights.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Flight</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
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
            max-width: 1000px;
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

        /* Date Navigation Bar */
        .date-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1E90FF;
            color: #fff;
            border-radius: 10px;
            overflow: hidden;
            padding: 15px;
            margin-bottom: 20px;
        }

        .date-bar a {
            text-decoration: none;
            color: #fff;
            font-size: 16px;
        }

        .date-item {
            text-align: center;
            flex: 1;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .date-item:hover {
            background-color: #0073e6;
        }

        .date-item.active {
            background-color: #0056b3;
        }

        .flights {
            margin-top: 20px;
        }

        .flight-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .flight-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .flight-details h4 {
            margin: 0;
        }

        .book-btn {
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .book-btn:hover {
            background-color: #2980b9;
        }

        .flight-list {
            list-style: none;
            padding: 0;
        }

        .flight-list li {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .flight-list li h3 {
            margin: 0;
            color: #007bff;
            font-size: 18px;
        }

        .flight-list li p {
            margin: 8px 0;
            color: #495057;
        }

        .flight-list li form {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
        }

        .flight-list li input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .flight-list li input[type="submit"]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .flight-list li select,
        .flight-list li input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        /* No Flights Message */
        .no-flights {
            text-align: center;
            color: #dc3545;
            font-size: 20px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="?logout=true">Logout</a>
    </div>
    <div class="container">
        <h2>Select Flight</h2>

        <!-- Date Navigation Bar -->
        <div class="date-bar">
            <?php foreach ($date_range as $date): ?>
                <div class="date-item <?php echo ($date == $departure_date) ? 'active' : ''; ?>" onclick="changeDate('<?php echo $date; ?>')">
                    <p><?php echo date('D, d M', strtotime($date)); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Flights for the Selected Date -->
        <div class="flights">
            <?php if (!empty($flights)): ?>
                <ul class="flight-list">
                    <?php foreach ($flights as $flight_date => $flight_list): ?>
                        <li>
                            <h3>Flights for <?php echo date('D, d M', strtotime($flight_date)); ?></h3>
                            <ul>
                                <?php foreach ($flight_list as $flight): ?>
                                    <li>
                                        <h3><?php echo htmlspecialchars($flight['flight_number']); ?> - <?php echo htmlspecialchars($flight['destination']); ?></h3>
                                        <p><strong>Departure:</strong> <?php echo htmlspecialchars($flight['departure_place']); ?> at <?php echo htmlspecialchars($flight['departure_time']); ?></p>
                                        <p><strong>Arrival:</strong> <?php echo htmlspecialchars($flight['arrival_date']); ?> at <?php echo htmlspecialchars($flight['arrival_time']); ?></p>
                                        <form method="POST" action="passenger_details.php">
                                            <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($flight['id']); ?>">
                                            <input type="hidden" name="departure_place" value="<?php echo htmlspecialchars($departure_place); ?>">
                                            <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
                                            <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($flight_date); ?>">
                                            <label for="class">Class:</label>
                                            <select name="class" id="class" required>
                                                <option value="">--Select Class--</option>
                                                <option value="first_class">First Class</option>
                                                <option value="business_class">Business Class</option>
                                                <option value="economy_class">Economy Class</option>
                                            </select>
                                            <label for="number_of_passengers">Number of Passengers:</label>
                                            <input type="number" id="number_of_passengers" name="number_of_passengers" min="1" required>
                                            <input type="submit" value="Book Now">
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-flights">No flights available for the selected dates.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // JavaScript function to handle date click
        function changeDate(date) {
            const urlParams = new URLSearchParams(window.location.search);
            const departurePlace = urlParams.get('departure_place');
            const destination = urlParams.get('destination');
            
            // Redirect to the same page with updated departure_date
            window.location.href = `select_flight.php?departure_place=${encodeURIComponent(departurePlace)}&destination=${encodeURIComponent(destination)}&departure_date=${encodeURIComponent(date)}`;
        }
    </script>
</body>
</html>
