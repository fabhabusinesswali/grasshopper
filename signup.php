<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start session
    session_start();

    // Database connection (Replace with your database details)
    $conn = new mysqli("localhost", "username", "password", "database_name");

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate input fields
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password']; // No need to filter; will be hashed
    $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_NUMBER_INT);

    // Ensure fields are not empty
    if (empty($username) || empty($password) || empty($phone_number)) {
        die("All fields are required.");
    }

    // Securely hash the password
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO users (username, password, phone_number) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $phone_number);

    if ($stmt->execute()) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
