<?php
$servername = "localhost"; // Replace with your RDS endpoint
$username = "root"; // Replace with your DB username
$password = "root"; // Replace with your DB password
$dbname = "accounting_db"; // Replace with your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>