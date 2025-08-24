<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grasshopper Clone</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 20px;
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: #43cea2;
            border-radius: 2px;
        }

        button {
            background: #43cea2;
            color: white;
            padding: 15px 25px;
            border: none;
            width: 100%;
            cursor: pointer;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(67, 206, 162, 0.2);
        }

        button:hover {
            background: #3bb592;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 206, 162, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        button:last-child {
            background: #185a9d;
            box-shadow: 0 4px 15px rgba(24, 90, 157, 0.2);
        }

        button:last-child:hover {
            background: #144e89;
            box-shadow: 0 6px 20px rgba(24, 90, 157, 0.3);
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px;
                padding: 30px 20px;
            }

            h2 {
                font-size: 24px;
            }

            button {
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Grasshopper Clone</h2>
        <button onclick="window.location.href='signup.php'">Sign Up</button>
        <button onclick="window.location.href='login.php'">Login</button>
    </div>
</body>
</html>
