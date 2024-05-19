<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "newsletter_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if email address is provided
if (empty($_POST['email_address'])) {
    echo "Email address is required.";
    exit;
}

$email = $conn->real_escape_string($_POST['email_address']);

$sql = "INSERT INTO newsletter (email) VALUES ('$email')";

if ($conn->query($sql) === TRUE) {
    echo "You've subscribed to the newsletter. Enjoy a 25% discount! :)";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
