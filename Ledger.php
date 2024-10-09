<?php
session_start(); // Start the session

/* Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}*/

// Store the username from the session
$username = $_SESSION['username'];

// Get the account ID from the query parameters
if (isset($_GET['account_id'])) {
    $accountId = intval($_GET['account_id']);
} else {
    // Redirect if no account ID is provided
    header('Location: view_all_client_accounts.php');
    exit();
}

// Database connection
$servername = "localhost";
$dbUsername = "root"; // Replace with your database username
$dbPassword = "root"; // Replace with your database password
$dbname = "accounting_db"; // Replace with your database name

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select transactions for the specified account
$sql = "SELECT * FROM Ledger_Transactions WHERE account_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $accountId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css">
    <title>Ledger for Account ID: <?php echo $accountId; ?></title>
</head>
<body>
<nav>
    <div class="welcome">
        <img src="profile.png" alt="Picture" class="picture">
        <h1 class="title">Ledger Legend Administrator</h1>
    </div>
    <div class="user-profile">
        <img src="pfp.png" alt="User Picture" class="profile-pic">
        <span class="username"><?php echo htmlspecialchars($username); ?></span>
        <a href="./logout.php" class="logout-btn">Logout</a>
    </div>
</nav>
<!-- Navigation Bar -->
<div class="main-bar">
        <!-- Home and IT Ticket as separate clickable links -->
        <a href="./administrator_home.php" class="nav-link">Home</a>
        <a href="./it_ticket.php" class="nav-link">IT Ticket</a>

        <!-- User Management dropdown -->
        <div class="dropdown">
            <button class="dropbtn nav-link">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php">Create User</a>
                <a href="./user_roster.php">View Users</a>
                <a href="./Manage_Users.php">Account Approval</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./create_client_account_admin.php" >Create Account</a>
                <a href="./view_all_client_accounts.php" >View All Accounts</a>
            </div>
        </div>

        <!-- Reports dropdown -->
        <div class="dropdown">
            <button class="dropbtn nav-link">Reports</button>
            <div class="dropdown-content">
                <a href="#">User Report</a>
                <a href="./Expired_Passwords_Log.php">Expired Passwords Report</a>
                <a href="#">Login Attempts Report</a>
            </div>
        </div>

        <!-- Notifications dropdown -->
        <div class="dropdown">
            <button class="dropbtn nav-link">Notifications</button>
            <div class="dropdown-content">
                <a href="">Password Expiration Alerts</a>
            </div>
        </div>

        <!-- Email Management dropdown -->
        <div class="dropdown">
            <button class="dropbtn nav-link">Email Management</button>
            <div class="dropdown-content">
            <a href="./Email.php">Email Users</a>
            </div>
        </div>

        <!-- Settings dropdown -->
        <div class="dropdown">
            <button class="dropbtn nav-link">Settings</button>
            <div class="dropdown-content">
                <a href="#">System Settings</a>
            </div>
        </div>
    </div>

<div class="main">
<h1 style="color: white;">Ledger Transactions for Account ID: <?php echo $accountId; ?></h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference Number</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Running Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<tr>
                            <td>' . htmlspecialchars($row['transaction_date']) . '</td>
                            <td>' . htmlspecialchars($row['reference_number']) . '</td>
                            <td>' . htmlspecialchars($row['description']) . '</td>
                            <td>' . '$' . number_format($row['debit'], 2) . '</td>
                            <td>' . '$' . number_format($row['credit'], 2) . '</td>
                            <td>' . '$' . number_format($row['balance'], 2) . '</td>
                        </tr>';
                }
            } else {
                echo '<tr><td colspan="6">No transactions found for this account.</td></tr>';
            }
            ?>
        </tbody>
    </table>
    <button onclick="window.location.href='view_all_client_accounts.php'">Back to Accounts</button>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
