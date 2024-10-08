<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the form
$name = $_POST['name'];
$email = $_POST['email'];
$issue = $_POST['issue'];
$priority = $_POST['priority'];

// Retrieve userId from session
$userId = isset($_SESSION['Id']) && is_numeric($_SESSION['Id']) ? intval($_SESSION['Id']) : null;

// Debugging: Log the userId to check if it's set
error_log("User ID from session: " . $userId);

// Check if userId is null
if ($userId === null) {
    die("Error: UserId is not set.");
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO IT_TicketsTable (Name, Email, Issue, Priority, UserId) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $name, $email, $issue, $priority, $userId); // Bind the userId

// Execute the statement
if ($stmt->execute()) {
    $message = "New ticket created successfully. Ticket ID: " . $stmt->insert_id; // Retrieve the last inserted ID
} else {
    $message = "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <script>
        console.log("<?php echo addslashes($message); ?>"); // Log the message to the console
        window.location.href = './administrator_home.php'; // Redirect to home page
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>



