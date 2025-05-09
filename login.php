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

        // Verify the password
        if ($password === $user['password']) {
            // Store user information in session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] == 'user') {
                header("Location: search_flights.php");
            } else {
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard if role is admin
            }
            exit();
        } else {
            echo "<div class='alert'>Invalid password!</div>";
        }
    } else {
        echo "<div class='alert'>No user found with that username!</div>";
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
    <title>Flight Booking System - Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            animation: fadeIn 1s ease-in-out; /* Fade in animation for the entire page */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        #background-video {
            position: absolute;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            filter: brightness(50%);
        }

        .container {
            width: 90%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 1;
            opacity: 0; /* Start hidden for animation */
            animation: fadeIn 1s forwards; /* Fade-in animation */
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
        }

        h2 {
            text-align: center;
            color: #00c0ff; /* Match your homepage header color */
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px; /* Rounded input */
            margin-bottom: 15px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #00c0ff; /* Match focus color to navbar */
            outline: none;
        }

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

        .forgot-password,
        .signup {
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password a,
        .signup a {
            color: #00c0ff; /* Match link color to navbar */
            text-decoration: none;
        }

        .forgot-password a:hover,
        .signup a:hover {
            text-decoration: underline;
        }

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

        .alert {
            color: red;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <!-- Background video -->
    <video autoplay muted loop id="background-video">
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <!-- Home Button -->
    <div class="home-link">
        <a href="index.php">Home</a>
    </div>

    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot your password?</a>
        </div>
        <div class="signup">
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
    </div>
</body>
</html>
