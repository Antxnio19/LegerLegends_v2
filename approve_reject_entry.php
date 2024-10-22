<?php
// Simulate manager role for testing purposes
$isManager = 1; // Assuming the user is a manager for testing

$servername = "localhost";
$dbUsername = "root"; 
$dbPassword = ""; 
$dbname = "ledgerledgends";
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entryId = $_POST['entry_id'];
    $action = $_POST['action'];
    $comment = $_POST['comment'];

    if ($action == 'approve') {
        $sql = "UPDATE Journal_Entries SET IsApproved = 'approved', comment = 'Approved' WHERE id = $entryId";
    } elseif ($action == 'reject') {
        if (empty($comment)) {
            echo "Comment is required for rejection.";
            exit();
        }
        $sql = "UPDATE Journal_Entries SET IsApproved = 'rejected', comment = '$comment' WHERE id = $entryId";
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
