<?php
include 'db_config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Validate password length and special character
    if (strlen($password) < 8 || !preg_match('/[^a-zA-Z0-9]/', $password)) {
        echo "Password must be at least 8 characters long and contain at least one special character.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Username or email already exists
            echo "Username or email already taken. Please choose a different one.";
        } else {
            // Prepare the SQL statement to insert user data without hashing the password
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $username, $password, $email);

            if ($stmt->execute()) {
                // Redirect to login page upon successful registration
                header("Location: login.php");
                exit(); // Ensure no further code is executed
            } else {
                echo "Error: " . $stmt->error;
            }
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
    <title>Register</title>
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

        .bg-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: blur(3px);
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
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0; /* Start hidden for animation */
            animation: fadeIn 1s forwards; /* Fade-in animation */
        }

        h2 {
            text-align: center;
            color: #00c0ff; /* Match your login page header color */
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px; /* Rounded input */
            margin-bottom: 15px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
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

        .login {
            text-align: center;
            margin-top: 10px;
        }

        .login a {
            color: #00c0ff; /* Match link color to navbar */
            text-decoration: none;
        }

        .login a:hover {
            text-decoration: underline;
        }

        /* Home Button Styling */
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
<video class="bg-video" autoplay muted loop>
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Home Button -->
    <div class="home-link">
        <a href="index.php">Home</a>
    </div>

    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="submit" value="Register">
        </form>
        <div class="login">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
