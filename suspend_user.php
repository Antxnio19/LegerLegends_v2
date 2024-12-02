<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$host = 'localhost'; 
$user = 'root'; 
$pass = 'root'; 
$db = 'accounting_db'; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id']) && isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $userId = (int) $_GET['id']; 
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    $updateActiveStmt = $conn->prepare("UPDATE Table1 SET IsActive = 0 WHERE Id = ?");
    $updateActiveStmt->bind_param('i', $userId);
    $updateActiveStmt->execute();

    $insertSuspensionStmt = $conn->prepare("INSERT INTO UserSuspensions (UserId, StartDate, EndDate) VALUES (?, ?, ?)");
    $insertSuspensionStmt->bind_param('iss', $userId, $startDate, $endDate);
    $insertSuspensionStmt->execute();

    if ($updateActiveStmt->affected_rows > 0 && $insertSuspensionStmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

    $updateActiveStmt->close();
    $insertSuspensionStmt->close();
}

$conn->close();
?>