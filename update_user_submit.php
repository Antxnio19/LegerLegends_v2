<?php
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$userId = intval($_POST['id']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
$lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
$isActive = intval($_POST['isActive']);
$lockoutUntil = $_POST['lockoutUntil'] ? $_POST['lockoutUntil'] : null;

// Get the current user's username for ModifiedBy
$modifiedBy = $_SESSION['username']; // Assuming you set the username in the session

// Prepare SQL query to update user data
$sql = "UPDATE EmployeeAccounts SET 
            Username = '$username',
            EmailAddress = '$email',
            FirstName = '$firstName',
            LastName = '$lastName',
            IsActive = $isActive,
            LockoutUntil = " . ($lockoutUntil ? "'$lockoutUntil'" : "NULL") . ",
            ModifiedBy = '$modifiedBy'
        WHERE Id = $userId";

if ($conn->query($sql) === TRUE) {
    // If update is successful, redirect
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redirecting...</title>
        <script>
            window.location.href = "./administrator_home.php"; // Redirect to home page
        </script>
    </head>
    <body>
        <p>Redirecting...</p>
    </body>
    </html>';
} else {
    // If there is an error, show the error message
    echo "Error updating user: " . $conn->error;
}

// Close the connection
$conn->close();
?>
