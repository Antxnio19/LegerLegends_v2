<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

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

if (isset($_GET['id']) && isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $userId = (int) $_GET['id']; // Cast to int to prevent SQL injection
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    // Set the account to inactive during suspension using a prepared statement
    $updateActiveStmt = $conn->prepare("UPDATE Table1 SET IsActive = 0 WHERE Id = ?");
    $updateActiveStmt->bind_param('i', $userId);
    $updateActiveStmt->execute();

    // Insert suspension dates into the UserSuspensions table using a prepared statement
    $insertSuspensionStmt = $conn->prepare("INSERT INTO UserSuspensions (UserId, StartDate, EndDate) VALUES (?, ?, ?)");
    $insertSuspensionStmt->bind_param('iss', $userId, $startDate, $endDate);
    $insertSuspensionStmt->execute();

    if ($updateActiveStmt->affected_rows > 0 && $insertSuspensionStmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

    // Close statements
    $updateActiveStmt->close();
    $insertSuspensionStmt->close();
}

$conn->close();
?>