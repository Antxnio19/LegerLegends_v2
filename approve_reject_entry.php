<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
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
    $entryId = intval($_POST['entry_id']);
    $action = $_POST['action'];
    $comment = $_POST['comment'] ?? '';

    // Update `is_approved` field based on action
    if ($action == 'approve') {
        $status = 'Approved';
        $updateComment = "Approved"; // Setting the comment as 'Approved'
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

    // Prepare SQL query with sanitized inputs
    $stmt = $conn->prepare("UPDATE Journal_Entries SET is_approved = ?, comment = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $updateComment, $entryId);

    if ($stmt->execute()) {
        // Redirect back to the journal entries page after update
        header('Location: view_all_journal_entries.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
