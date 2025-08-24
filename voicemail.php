<?php
// Start session with secure settings
session_start();
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// Authentication check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$message = '';
$messageType = '';
$currentNumber = isset($_SESSION['forward_number']) ? htmlspecialchars($_SESSION['forward_number']) : '';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Form submission handler
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Security validation failed. Please try again.';
        $messageType = 'error';
    } else {
        // Sanitize and validate phone number
        $forward_number = filter_input(INPUT_POST, 'forward_number', FILTER_SANITIZE_STRING);
        
        // Basic phone number validation - accepts various formats
        if (preg_match('/^[0-9+\-\(\) ]{6,20}$/', $forward_number)) {
            $_SESSION['forward_number'] = $forward_number;
            $message = "Call forwarding successfully set to " . htmlspecialchars($forward_number);
            $messageType = 'success';
            $currentNumber = htmlspecialchars($forward_number);
            
            // Log the change (in a production environment, consider using a proper logging system)
            error_log("User ID: {$_SESSION['user_id']} updated forwarding number to: $forward_number");
        } else {
            $message = "Invalid phone number format. Please use a standard format (e.g., +1-555-123-4567).";
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Call Forwarding Settings</title>
    <style>
        :root {
            --primary-color: #4a6cf7;
            --error-color: #f44336;
            --success-color: #4caf50;
            --light-gray: #f5f5f5;
            --dark-gray: #333333;
            --border-radius: 6px;
            --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: var(--dark-gray);
            background-color: var(--light-gray);
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
        }
        
        h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-gray);
        }
        
        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
        }
        
        .message.success {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .message.error {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        .current-setting {
            background-color: rgba(74, 108, 247, 0.05);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary-color);
        }
        
        form {
            margin-top: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: border 0.3s ease;
        }
        
        input[type="text"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(74, 108, 247, 0.2);
        }
        
        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 500;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }
        
        button:hover {
            background-color: #3a5ad9;
        }
        
        button:active {
            transform: translateY(1px);
        }
        
        .help-text {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Call Forwarding Settings</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($currentNumber)): ?>
            <div class="current-setting">
                <strong>Current Forwarding Number:</strong> <?php echo $currentNumber; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" id="forwarding-form">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="forward_number">Forward Calls To:</label>
                <input 
                    type="text" 
                    id="forward_number" 
                    name="forward_number" 
                    placeholder="Enter phone number (e.g., +1-555-123-4567)" 
                    value="<?php echo $currentNumber; ?>"
                    required
                    pattern="[0-9+\-\(\) ]{6,20}"
                    title="Enter a valid phone number"
                >
                <p class="help-text">Format examples: +1-555-123-4567, (555) 123-4567, 5551234567</p>
            </div>
            
            <button type="submit">Save Settings</button>
        </form>
        
        <div class="footer">
            <p>Last updated: <?php echo date("Y-m-d H:i", isset($_SESSION['last_update']) ? $_SESSION['last_update'] : time()); ?></p>
        </div>
    </div>
    
    <script>
        // Simple client-side validation
        document.getElementById('forwarding-form').addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('forward_number');
            const phonePattern = /^[0-9+\-\(\) ]{6,20}$/;
            
            if (!phonePattern.test(phoneInput.value)) {
                e.preventDefault();
                alert('Please enter a valid phone number');
                phoneInput.focus();
            }
        });
    </script>
</body>
</html>
