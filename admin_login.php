<?php
session_start();
include 'db_config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL injection by using prepared statements
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password (direct comparison for plain text)
        if ($password == $user['password']) {
            // Store user information in session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] == 'admin') {
                header("Location: admin_home.php"); // Redirect to admin_home.php for admin
            } else {
                header("Location: user_dashboard.php"); // Redirect to user_dashboard.php for regular users
            }
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that username!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Booking System - Admin Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Body and Animation */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Background Video Styling */
        #background-video {
            position: absolute;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            filter: brightness(50%);
        }

        /* Container Styling */
        .container {
            width: 90%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 1;
            opacity: 0;
            animation: fadeIn 1s forwards;
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Text and Input Styling */
        h2 {
            text-align: center;
            color: #00c0ff;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #00c0ff;
            outline: none;
        }

        /* Submit Button */
        input[type="submit"] {
            background: #00c0ff; /* Match button color to navbar */
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
            transform: scale(1.05); /* Slight scale effect on hover */
        }

        /* Links */
        .forgot-password, .signup {
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password a, .signup a {
            color: #00c0ff;
            text-decoration: none;
        }

        .forgot-password a:hover, .signup a:hover {
            text-decoration: underline;
        }

        /* Home Link */
        .home-link {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .home-link a {
            text-decoration: none;
            color: #00c0ff;
            font-size: 18px;
            padding: 10px 20px;
            border: 1px solid #00c0ff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .home-link a:hover {
            background-color: #00c0ff;
            color: white;
        }

        /* Alert Styling */
        .alert {
            color: red;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="home-link">
        <a href="index.php">Home</a>
    </div>

    <div class="container">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>
        <div class="forgot-password">
            <a href="admin_forgot_password.php">Forgot your password?</a>
        </div>
    </div>
</body>
</html>
