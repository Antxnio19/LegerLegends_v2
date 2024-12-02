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


$modifiedBy = $_SESSION['username']; 

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
    echo "Error updating account: " . $conn->error;
}

$conn->close();
?>