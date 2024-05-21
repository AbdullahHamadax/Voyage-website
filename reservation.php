<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservation_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if fields are empty
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

// Check for booking limit (5 is the max for the same time and date!!)
$sql = "SELECT COUNT(*) as total_bookings FROM reservations WHERE reservation_date = '$reservation_date' AND reservation_time = '$reservation_time'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($row['total_bookings'] >= 5) {
    header("Location: pages/reservation-fail.html");
    exit;
}

$sql = "INSERT INTO reservations (name, phone, person, reservation_date, reservation_time, message)
VALUES ('$name', '$phone', '$person', '$reservation_date', '$reservation_time', '$message')";

if ($conn->query($sql) === TRUE) {
    // Fetch the latest reservation details
    $reservation_id = $conn->insert_id;
    $sql = "SELECT * FROM reservations WHERE id = $reservation_id";
    $result = $conn->query($sql);
    $reservation = $result->fetch_assoc();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    $conn->close();
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              amber_orange: "hsl(37, 58%, 45%)",
            },
          },
        },
      };
    </script>
    <title>Reservation Response</title>
</head>
<body class="bg-[#0e0d0c] flex flex-col min-w-full min-h-screen text-center justify-center items-center bg-[url('/assets/images/monkey-d-luffy-clouds-one-piece-desktop-wallpaper-preview.jpg')]">
    <div class="bg-[#161718] rounded-lg flex flex-col justify-center gap-5 items-center w-[700px] h-[400px] border-solid border-white border-2 text-amber_orange">
        <h1>Reservation successful, we're happy to serve you 💖</h1>
        <h1>Reservation Information:</h1>
        <div>
            <table class="table-fixed">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                    </tr>
                    <tr>
                        <td>Person</td>
                        <td><?php echo htmlspecialchars($reservation['person']); ?></td>
                    </tr>
                    <tr>
                        <td>Reservation Date</td>
                        <td> <?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                    </tr>
                    <tr>
                        <td>Reservation Time</td>
                        <td><?php echo htmlspecialchars($reservation['reservation_time']); ?></td>
                    </tr>
                    <tr>
                        <td>Message</td>
                        <td><?php echo htmlspecialchars($reservation['message']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
