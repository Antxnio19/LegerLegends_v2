<?php
// eventlogs.php

// Database connection setup (modify with your actual database credentials)
$servername = "localhost"; // Change if necessary
$username = "root"; // Change to your database username
$password = "root"; // Change to your database password
$dbname = "accounting_db"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch event logs
$sql = "SELECT * FROM event_logs"; // Change to your actual table name
$result = $conn->query($sql);

// Include the HTML file for rendering
include 'eventlogs.html';
