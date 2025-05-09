<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ticket</title>
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
            margin: 100px auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 1;
            position: relative; /* Ensure this is on top of the video */
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #3498db; /* Change focus border color */
            outline: none;
        }

        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <video class="bg-video" autoplay muted loop>
        <source src="plane.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <h2>Enter Ticket Number</h2>
        <form action="ticket_details.php" method="GET">
            <input type="text" name="ticket_number" placeholder="Enter your ticket number" required>
            <button type="submit">View Ticket</button>
        </form>
    </div>
</body>
</html>
