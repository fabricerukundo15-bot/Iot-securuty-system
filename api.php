<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "security_db";

$conn = new mysqli($servername, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Database connection failed!" . $conn->connect_error);
    }
else {
    echo "Database connection went successful!";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $distance = $_POST['distance'];
    $eventStatus = $_POST['eventStatus'];

    $sql = "INSERT INTO intrusion_events(Distance_Detected,Event_status) values('$distance','$eventStatus')";
    $conn->query($sql);
}
?>