<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entryId = intval($_POST['entry_id']);
    $action = $_POST['action'];
    $comment = $_POST['comment'] ?? '';

    // Update `is_approved` field based on action
    if ($action == 'approve') {
        $status = 'Approved';
        $updateComment = "Approved";
    } elseif ($action == 'reject') {
        if (empty($comment)) {
            echo "Comment is required for rejection.";
            exit();
        }
        $status = 'Rejected';
        $updateComment = $comment;
    } else {
        echo "Invalid action.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE Journal_Entries SET is_approved = ?, comment = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $updateComment, $entryId);

    if ($stmt->execute()) {
        header('Location: view_all_journal_entries.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
