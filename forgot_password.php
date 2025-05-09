<?php
session_start();
include 'db_config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Check if username and email match
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect to reset password page
        $_SESSION['reset_user'] = $username; // Store username in session for later use
        header("Location: reset_password.php");
        exit();
    } else {
        $error_message = "No user found with that username and email!";
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
    <title>Forgot Password</title>
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
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px; /* Rounded input */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 1;
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
        }

        h2 {
            text-align: center;
            color: #00c0ff; /* Match your navbar color */
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
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
        input[type="email"]:focus {
            border-color: #00c0ff; /* Match focus color to navbar */
            outline: none;
        }

        input[type="submit"],
        .back-button {
            background: #00c0ff; /* Match button color to navbar */
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 10px; /* Space between buttons */
        }

        input[type="submit"]:hover,
        .back-button:hover {
            background-color: #2980b9;
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
    <div class="container">
        <h2>Forgot Password</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="submit" value="Reset Password">
            <button type="button" class="back-button" onclick="window.location.href='login.php';">Back</button>
        </form>
    </div>
</body>
</html>
