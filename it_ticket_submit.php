<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$issue = $_POST['issue'];
$priority = $_POST['priority'];

$userId = isset($_SESSION['Id']) && is_numeric($_SESSION['Id']) ? intval($_SESSION['Id']) : null;

error_log("User ID from session: " . $userId);

if ($userId === null) {
    die("Error: UserId is not set.");
}

$stmt = $conn->prepare("INSERT INTO IT_TicketsTable (Name, Email, Issue, Priority, UserId) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $name, $email, $issue, $priority, $userId); 

if ($stmt->execute()) {
    $message = "New ticket created successfully. Ticket ID: " . $stmt->insert_id; 
} else {
    $message = "Error: " . $stmt->error;
}

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
        console.log("<?php echo addslashes($message); ?>");
        window.location.href = './administrator_home.php'; 
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>



