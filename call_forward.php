<?php
session_start();
// Secure session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// CSRF protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$currentNumber = isset($_SESSION['forward_number']) ? htmlspecialchars($_SESSION['forward_number']) : '';

// Form processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = '<div class="alert alert-danger">Security validation failed.</div>';
    } else {
        // Validate phone number format
        $forward_number = filter_input(INPUT_POST, 'forward_number', FILTER_SANITIZE_STRING);
        if (preg_match('/^[0-9+\-\(\) ]{6,20}$/', $forward_number)) {
            $_SESSION['forward_number'] = $forward_number;
            $message = '<div class="alert alert-success">Call successfully forwarded to ' . htmlspecialchars($forward_number) . '</div>';
            $currentNumber = htmlspecialchars($forward_number);
        } else {
            $message = '<div class="alert alert-danger">Please enter a valid phone number.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Forwarding</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #fff;
            border-radius: 5px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .current-settings {
            background-color: #e9f7fe;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Set Call Forwarding</h2>
        
        <?php echo $message; ?>
        
        <?php if (!empty($currentNumber)): ?>
        <div class="current-settings">
            <strong>Current Forwarding Number:</strong> <?php echo $currentNumber; ?>
        </div>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="forward_number">Forward calls to:</label>
                <input type="text" id="forward_number" name="forward_number" 
                       placeholder="Enter phone number (e.g., +1-555-123-4567)" 
                       value="<?php echo $currentNumber; ?>" required>
            </div>
            
            <button type="submit">Save Settings</button>
        </form>
    </div>
</body>
</html>
