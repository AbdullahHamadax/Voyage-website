<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservation_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if fields are not empty
if (empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['reservation-date'])) {
    echo "All fields are required.";
    exit;
}

$name = $conn->real_escape_string($_POST['name']);
$phone = $conn->real_escape_string($_POST['phone']);
$person = $conn->real_escape_string($_POST['person']);
$reservation_date = $conn->real_escape_string($_POST['reservation-date']);
$reservation_time = $conn->real_escape_string($_POST['reservation-time']);
$message = $conn->real_escape_string($_POST['message']);

// Check for booking limit
$sql = "SELECT COUNT(*) as total_bookings FROM reservations WHERE reservation_date = '$reservation_date'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($row['total_bookings'] >= 5) {
    echo "No available tables for the selected date.";
    exit;
}

// Insert reservation
$sql = "INSERT INTO reservations (name, phone, person, reservation_date, reservation_time, message)
VALUES ('$name', '$phone', '$person', '$reservation_date', '$reservation_time', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "Reservation successful!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
