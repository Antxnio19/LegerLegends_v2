<?php
$servername = "localhost"; // Replace with your RDS endpoint
$username = "sa"; // Replace with your DB username
$password = "dcpomc21dcpomc21felka."; // Replace with your DB password
$dbname = "your_db_name"; // Replace with your database name

// Create connection
$conn = mysqli_connect("localhost", "sa", "dcpomc21dcpomc21felka.", "your_db_name");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>