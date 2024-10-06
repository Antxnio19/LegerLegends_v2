<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the table if it does not exist
$table_sql = "CREATE TABLE IF NOT EXISTS Table1 (
    Id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    UserTypeId CHAR(10) NOT NULL,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    EmailAddress VARCHAR(100) NOT NULL,
    DateOfBirth DATE NOT NULL,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    SecurityQuestions CHAR(10) NULL,
    SecurityAnswers CHAR(10) NULL,
    FailedAttempts INT DEFAULT 0,
    LockoutUntil DATETIME NULL,
    CreatedDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    ModifiedDate DATETIME ON UPDATE CURRENT_TIMESTAMP,
    ModifiedBy VARCHAR(50),
    IsActive BOOLEAN DEFAULT FALSE -- Add the IsActive column
)";

if (!$conn->query($table_sql)) {
    die("Error creating table: " . $conn->error);
}

// Retrieve form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$address = $_POST['address'];
$username = $_POST['generatedUsername'];  // Use the generated username from hidden field
$password = $_POST['password'];
$security_question = $_POST['security_question'];
$security_answer = $_POST['security_answer'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL statement for insertion, including IsActive set to FALSE
$in = $conn->prepare("INSERT INTO Table1 (UserTypeId, Username, Password, EmailAddress, DateOfBirth, FirstName, LastName, Address, SecurityQuestions, SecurityAnswers, IsActive) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Bind parameters
$userTypeId = "Accountant"; // Placeholder for now
$isActive = 0; // 0 represents FALSE (inactive)
$in->bind_param('ssssssssssi', $userTypeId, $username, $hashedPassword, $email, $dob, $first_name, $last_name, $address, $security_question, $security_answer, $isActive);

// Execute the statement
if ($in->execute()) {
    header('Location: login.html');  // Redirect to login page after successful insertion
    exit(); // Add exit to stop script execution after redirection
} else {
    die("Error inserting data: " . $conn->error);
}

// Close the connection
$conn->close();
?>
