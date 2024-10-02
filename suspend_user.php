<?php
include 'db_connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $userId = $_GET['id'];
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    // Set the account to inactive during suspension
    $updateActive = "UPDATE Table1 SET IsActive = 0 WHERE Id = $userId";
    $conn->query($updateActive);

    // Insert suspension dates into a new table (e.g., UserSuspensions)
    $insertSuspension = "INSERT INTO UserSuspensions (UserId, StartDate, EndDate) VALUES ($userId, '$startDate', '$endDate')";
    $conn->query($insertSuspension);

    if ($conn->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
}

$conn->close();
?>
