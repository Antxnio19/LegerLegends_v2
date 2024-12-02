<?php
$servername = "localhost"; 
$username = "your_username"; 
$password = "your_password"; 
$dbname = "your_database"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = "Admin";

$sql = "SELECT * FROM event_logs"; 
$result = $conn->query($sql);

include 'eventlogs.html';
