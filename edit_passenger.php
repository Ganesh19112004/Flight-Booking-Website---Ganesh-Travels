<?php
session_start();
include 'db_config.php'; // Include your database connection file

// Admin authentication check
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get passenger ID from query string
$passenger_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the current details of the passenger
$sql = "SELECT * FROM passengers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $passenger_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Passenger not found!";
    exit();
}

$passenger = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    // Update passenger details in the database
    $update_sql = "UPDATE passengers SET name = ?, email = ?, age = ?, gender = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssisi", $name, $email, $age, $gender, $passenger_id);

    if ($update_stmt->execute()) {
        echo "Passenger details updated successfully!";
    } else {
        echo "Error updating passenger details!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Passenger</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 600px;
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

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            color: #2c3e50;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .save-button {
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .save-button:hover {
            background-color: #2980b9;
        }

        .back-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Passenger Details</h2>

        <form method="post">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($passenger['name']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($passenger['email']); ?>" required>

            <label for="age">Age</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($passenger['age']); ?>" required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php if($passenger['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($passenger['gender'] === 'Female') echo 'selected'; ?>>Female</option>
            </select>

            <button type="submit" class="save-button">Save Changes</button>
        </form>

        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
