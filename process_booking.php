<?php
session_start();
include 'db_config.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Process the booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_id = intval($_POST['flight_id']);
    $class = htmlspecialchars($_POST['class']);
    $number_of_passengers = intval($_POST['number_of_passengers']);
    $user_id = intval($_SESSION['user_id']);

    // Validate inputs
    if ($number_of_passengers <= 0) {
        die("Invalid number of passengers.");
    }
    if (!in_array($class, ['first_class', 'business_class', 'economy_class'])) {
        die("Invalid class selected.");
    }

    // Insert booking into the database
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, flight_id, class, number_of_passengers) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $user_id, $flight_id, $class, $number_of_passengers);

    if (!$stmt->execute()) {
        die("Failed to insert booking: " . $stmt->error);
    }

    $booking_id = $stmt->insert_id;
    $stmt->close();

    // Insert each passenger's details into the database
    for ($i = 1; $i <= $number_of_passengers; $i++) {
        $name = htmlspecialchars($_POST["passenger_name_$i"]);
        $email = filter_var($_POST["passenger_email_$i"], FILTER_SANITIZE_EMAIL);
        $age = intval($_POST["passenger_age_$i"]);
        $gender = htmlspecialchars($_POST["passenger_gender_$i"]);

        // Validate passenger data
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format for passenger $i.");
        }
        if ($age <= 0) {
            die("Invalid age for passenger $i.");
        }

        $stmt = $conn->prepare("INSERT INTO passengers (booking_id, name, email, age, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issis", $booking_id, $name, $email, $age, $gender);

        if (!$stmt->execute()) {
            die("Failed to insert passenger $i: " . $stmt->error);
        }
    }

    $stmt->close();

    // Redirect to confirmation page
    header("Location: booking_confirmation.php?booking_id=$booking_id");
    exit();
} else {
    header("Location: select_flight.php");
    exit();
}
?>
