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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);

    $first_name = $_POST['first-name'] ?? '';
    $last_name = $_POST['last-name'] ?? '';
    $address = $_POST['address'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? ''; 
    $position = $_POST['user-type-id'] ?? ''; 
    $expiry_duration = isset($_POST['expiry-duration']) ? intval($_POST['expiry-duration']) : 0;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE Table1 SET FirstName = ?, LastName = ?, Address = ?, DateOfBirth = ?, EmailAddress = ?, Username = ?, Password = ?, UserTypeId = ?, ExpiryDuration = ? WHERE Id = ?");

    $stmt->bind_param("ssssssssii", $first_name, $last_name, $address, $dob, $email, $username, $hashed_password, $position, $expiry_duration, $user_id);
    
    if ($stmt->execute()) {

        header("Location: ./update_success.php?user_id=" . $user_id); 
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
