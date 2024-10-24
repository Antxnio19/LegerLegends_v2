<?php
session_start();
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

// Get form data
$accountId = intval($_POST['id']);
$account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
$account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
$account_description = mysqli_real_escape_string($conn, $_POST['account_description']);
$normal_side = mysqli_real_escape_string($conn, $_POST['normal_side']);
$account_category = mysqli_real_escape_string($conn, $_POST['account_category']);
$account_subcategory = mysqli_real_escape_string($conn, $_POST['account_subcategory']);
$initial_balance = floatval($_POST['initial_balance']);
$user_id = intval($_POST['user_id']);
$account_order = mysqli_real_escape_string($conn, $_POST['account_order']);
$statement = mysqli_real_escape_string($conn, $_POST['statement']);
$isActive = intval($_POST['isActive']);
$comment = mysqli_real_escape_string($conn, $_POST['comment']);


// Get the current user's username for ModifiedBy
$modifiedBy = $_SESSION['username']; // Assuming you set the username in the session

// Prepare SQL query to update client account data
$sql = "UPDATE Client_Accounts SET 
            account_name = '$account_name',
            account_number = '$account_number',
            account_description = '$account_description',
            normal_side = '$normal_side',
            account_category = '$account_category',
            account_subcategory = '$account_subcategory',
            initial_balance = $initial_balance,
            user_id = $user_id,
            account_order = '$account_order',
            statement = '$statement',
            IsActive = '$isActive',
            comment = '$comment',
            ModifiedBy = '$modifiedBy'
        WHERE id = $accountId";

if ($conn->query($sql) === TRUE) {
    // If update is successful, redirect
    echo '<!DOCTYPE html
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redirecting...</title>
        <script>
            window.location.href = "./view_all_client_accounts.php"; // Redirect to view all client accounts
        </script>
    </head>
    <body>
        <p>Redirecting...</p>
    </body>
    </html>';
} else {
    // If there is an error, show the error message
    echo "Error updating account: " . $conn->error;
}

// Close the connection
$conn->close();
?>