<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username and admin ID from the session
$username = $_SESSION['username'];
$adminId = $_SESSION['user_id']; // Assuming the admin's ID is stored in the session

// Include the event logger function
include 'eventLogger.php'; // Adjust the path as necessary

// Database connection (replace with your actual connection details)
$host = 'localhost'; // Database host
$user = 'root'; // Database username
$pass = 'root'; // Database password
$db = 'accounting_db'; // Database name

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $username = $_POST['generatedUsername'];  // Use the generated username
    $password = $_POST['password'];
    $security_question = $_POST['security_question'];
    $security_answer = $_POST['security_answer'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement for insertion, including IsActive set to FALSE
    $in = $conn->prepare("INSERT INTO Table1 (UserTypeId, Username, Password, EmailAddress, DateOfBirth, FirstName, LastName, Address, SecurityQuestions, SecurityAnswers, IsActive) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,
