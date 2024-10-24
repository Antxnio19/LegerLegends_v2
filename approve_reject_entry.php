<?php
session_start(); // Start the session at the very beginning
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Create connection
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Store the username from the session
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entryId = $_POST['entry_id'];
    $action = $_POST['action'];
    $comment = $_POST['comment'];

    if ($action == 'approve') {
        $sql = "UPDATE Journal_Entries SET IsApproved = '1', comment = 'Approved' WHERE id = $entryId";
    } elseif ($action == 'reject') {
        if (empty($comment)) {
            echo "Comment is required for rejection.";
            exit();
        }
        $sql = "UPDATE Journal_Entries SET IsApproved = '2', comment = '$comment' WHERE id = $entryId";
    }

    if ($conn->query($sql) === TRUE) {
        header('Location: view_all_journal_entries.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
