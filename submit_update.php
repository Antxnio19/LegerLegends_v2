<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];

// Database connection
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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST data
    $user_id = intval($_POST['user_id']);

    // Get the other form data
    $first_name = $_POST['first-name'] ?? '';
    $last_name = $_POST['last-name'] ?? '';
    $address = $_POST['address'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? ''; // Get the password from the form
    $position = $_POST['user-type-id'] ?? ''; // Use the user type ID
    $expiry_duration = isset($_POST['expiry-duration']) ? intval($_POST['expiry-duration']) : 0;

    // Hash the password if it's being updated
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare a statement to update user data
    $stmt = $conn->prepare("UPDATE Table1 SET FirstName = ?, LastName = ?, Address = ?, DateOfBirth = ?, EmailAddress = ?, Username = ?, Password = ?, UserTypeId = ?, ExpiryDuration = ? WHERE Id = ?");

    // Bind parameters correctly
    $stmt->bind_param("ssssssssii", $first_name, $last_name, $address, $dob, $email, $username, $hashed_password, $position, $expiry_duration, $user_id);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Redirect or display success message
        header("Location: ./update_success.php?user_id=" . $user_id); 
        exit();
    } else {
        echo "Error updating record: " . $stmt->error; // Display error if the update fails
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
