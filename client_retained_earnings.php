<?php
session_start();

// Store the username from the session
$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "root";
$dbname = "accounting_db";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize values
$totalRevenue = 0;
$totalExpenses = 0;
$netIncome = 0;
$beginningRetainedEarnings = 0; // Assuming zero for the beginning
$dividends = 0; // Assuming dividends are zero
$endRetainedEarnings = 0;
$clientName = "Addams & Family Inc.";

// Fetch retained earnings data for the specific client
$query = "
    SELECT 
        SUM(CASE WHEN jel.account_type = 'Revenue' THEN COALESCE(jel.credit, 0) ELSE 0 END) AS total_revenue,
        SUM(CASE WHEN jel.account_type = 'Expense' THEN COALESCE(jel.debit, 0) ELSE 0 END) AS total_expenses
    FROM 
        Ledger_Transactions lt
    JOIN 
        Journal_Entry_Lines jel ON lt.journal_entry_id = jel.journal_entry_id
    JOIN 
        Client_Accounts ca ON lt.client_account_id = ca.id
    WHERE 
        ca.account_name = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $clientName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalRevenue = $row['total_revenue'];
    $totalExpenses = $row['total_expenses'];
    $netIncome = $totalRevenue - $totalExpenses;
    $endRetainedEarnings = $beginningRetainedEarnings + $netIncome - $dividends;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <link rel="icon" type="image/png" href="profile.png">
    
    <title>Statement of Retained Earnings</title>
    <style>
        .centered {
            text-align: center;
        }
    </style>
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

    <div class="main-bar">
        <a href="./administrator_home.php" class="nav-link">Home</a>
        <a href="./it_ticket.php" class="nav-link">IT Ticket</a>
        <div class="dropdown">
            <button class="dropbtn nav-link">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php">Create User</a>
                <a href="./user_roster.php">View Users</a>
                <a href="./Manage_Users.html">Account Approval</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn nav-link">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./create_client_account_admin.php">Create Account</a>
                <a href="./view_all_client_accounts.php">View All Accounts</a>
                <a href="./view_all_journal_entries.php">View Journal Entries</a>
                <a href="./client_income.php">Income (Statement)</a>
                <a href="./client_retained_earnings.php">Retained Earnings (Statement)</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn nav-link">Reports</button>
            <div class="dropdown-content">
                <a href="#">User Report</a>
                <a href="./Expired_Passwords_Log.php">Expired Passwords Report</a>
                <a href="#">Login Attempts Report</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn nav-link">Notifications</button>
            <div class="dropdown-content">
                <a href="">Password Expiration Alerts</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn nav-link">Email Management</button>
            <div class="dropdown-content">
                <a href="">Send Email</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn nav-link">Settings</button>
            <div class="dropdown-content">
                <a href="#">System Settings</a>
            </div>
        </div>
    </div>

<div class="container centered">
    <h1 class="title"><?php echo $clientName; ?></h1>
    <h2 class="title">Statement of Retained Earnings</h2>
    <h3 class="title">For the Year Ended April 30, 2010</h3>
        
    <table>
        <tr>
            <td>Beg Retained Earnings, 4/1/10</td>
            <td>$<?php echo number_format($beginningRetainedEarnings, 2); ?></td>
        </tr>
        <tr>
            <td>Add: Net Income</td>
            <td>$<?php echo number_format($netIncome, 2); ?></td>
        </tr>
        <tr>
            <td>Less: Dividends</td>
            <td>$<?php echo number_format($dividends, 2); ?></td>
        </tr>
        <tr>
            <th>End Retained Earnings, 4/30/10</th>
            <th class="underline">$<?php echo number_format($endRetainedEarnings, 2); ?></th>
        </tr>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
