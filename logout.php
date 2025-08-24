<?php
// Start the session
session_start();

// Log the logout activity before destroying the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Unknown';
$logout_time = date('Y-m-d H:i:s');
error_log("User {$user_id} logged out at {$logout_time}");

// Clear all session variables
$_SESSION = array();

// Delete the session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destroy the session
session_destroy();

// Prepare a success message for the login page
$redirect_url = "login.php?logout=success&time=" . urlencode($logout_time);

// Set a message for the user
$_SESSION['flash_message'] = "You have been successfully logged out.";

// Redirect to login page with a success parameter
header("Location: " . $redirect_url);
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #00b09b, #0083B0);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        
        .logout-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        
        .logo {
            margin-bottom: 20px;
        }
        
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #00b09b;
            width: 40px;
            height: 40px;
            margin: 20px auto;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .message {
            margin-top: 20px;
            font-size: 16px;
            color: #666;
        }
        
        .redirect-link {
            display: inline-block;
            margin-top: 20px;
            color: #0083B0;
            text-decoration: none;
            font-weight: 500;
        }
        
        .redirect-link:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // Auto-redirect after 2 seconds
        window.onload = function() {
            setTimeout(function() {
                window.location.href = "<?php echo $redirect_url; ?>";
            }, 2000);
        };
    </script>
</head>
<body>
    <div class="logout-container">
        <div class="logo">
            <!-- Placeholder for logo -->
            <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#00b09b" stroke-width="2"/>
                <path d="M8 12L16 12" stroke="#00b09b" stroke-width="2" stroke-linecap="round"/>
                <path d="M13 9L16 12L13 15" stroke="#00b09b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1>Logging Out</h1>
        <div class="spinner"></div>
        <p class="message">Securely ending your session...</p>
        <a href="login.php" class="redirect-link">Click here if you're not redirected automatically</a>
    </div>
</body>
</html>
