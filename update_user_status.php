<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}

$username = $_SESSION['username'];

$host = 'localhost'; 
$user = 'root'; 
$pass = 'root'; 
$db = 'accounting_db'; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id']) && isset($_GET['approved']) && isset($_GET['isActive'])) {
    $id = (int) $_GET['id'];
    $approved = (int) $_GET['approved'];
    $isActive = (int) $_GET['isActive']; 

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
