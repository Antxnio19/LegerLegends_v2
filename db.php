<?php
$servername = "localhost"; 
$username = "sa"; 
$password = "dcpomc21dcpomc21felka."; 
$dbname = "your_db_name"; 

$conn = mysqli_connect("localhost", "sa", "dcpomc21dcpomc21felka.", "your_db_name");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>