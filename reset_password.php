<?php
session_start();
include 'db_config.php'; // Include your database connection file

if (!isset($_SESSION['reset_user'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $username = $_SESSION['reset_user'];

    // Validate the password: at least 8 characters long and contains at least 1 special character
    if (strlen($new_password) < 8 || !preg_match('/[^a-zA-Z0-9]/', $new_password)) {
        echo "Password must be at least 8 characters long and contain at least one special character.";
    } else {
        // Update the password in the database without hashing
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $username);

        if ($stmt->execute()) {
            echo "Password reset successfully!";
            unset($_SESSION['reset_user']); // Clear the session variable
            header("Location: login.php"); // Redirect to login page
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
    <title>Reset Password</title>
    <style>
        body, html {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
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
        }

        .container {
            width: 90%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
        }

        h2 {
            text-align: center;
            color: #007bff; /* Change header color */
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #007bff; /* Change border color */
            border-radius: 10px; /* Rounded input */
            margin-bottom: 15px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="password"]:focus {
            border-color: #0056b3; /* Change focus border color */
            outline: none;
        }

        input[type="submit"] {
            background: #007bff; /* Change button color */
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Change hover color */
        }
    </style>
</head>
<body>
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
