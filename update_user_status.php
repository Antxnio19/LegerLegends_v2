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

if (isset($_GET['id']) && isset($_GET['approved']) && isset($_GET['isActive'])) {
    $id = (int) $_GET['id'];
    $approved = (int) $_GET['approved'];
    $isActive = (int) $_GET['isActive']; // Get isActive value

    // Update both Approved and IsActive
    $sql = "UPDATE table1 SET Approved = ?, IsActive = ? WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $approved, $isActive, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
}
?>
