<?php
include 'db_connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['isActive'])) {
    $userId = $_GET['id'];
    $isActive = $_GET['isActive'];

    // Update the IsActive status
    $query = "UPDATE Table1 SET IsActive = $isActive WHERE Id = $userId";
    if ($conn->query($query) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
}

$conn->close();
?>


