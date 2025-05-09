<?php
session_start();
include 'db_config.php'; // Include your database connection file

// Check if session variable is set
if (!isset($_SESSION['reset_user'])) {
    header("Location: admin_forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $username = $_SESSION['reset_user'];

    // Validate the password for minimum 8 characters and at least 1 special character
    if (strlen($new_password) < 8 || !preg_match('/[^a-zA-Z0-9]/', $new_password)) {
        echo "Password must be at least 8 characters long and contain at least 1 special character.";
    } else {
        // Store the password as plain text (not recommended for security)
        $plain_password = $new_password;

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ? AND role = 'admin'");
        $stmt->bind_param("ss", $plain_password, $username);

        if ($stmt->execute()) {
            echo "Password reset successfully!";
            unset($_SESSION['reset_user']); // Clear the session variable
            header("Location: admin_login.php"); // Redirect to admin login page
        } else {
            echo "Error updating password: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reset Password</title>
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

        .container {
            width: 90%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        h2 {
            text-align: center;
            color: #50b3a2;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 100px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background: #50b3a2;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <!-- Background Video -->
    <video class="bg-video" autoplay muted loop>
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST" action="">
            <input type="password" name="new_password" placeholder="New Password" required><br>
            <input type="submit" value="Update Password">
        </form>
    </div>
</body>
</html>
