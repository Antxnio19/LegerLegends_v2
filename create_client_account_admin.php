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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
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
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Prepare SQL query to insert new client account data
    $sql = "INSERT INTO Client_Accounts (
                account_name,
                account_number,
                account_description,
                normal_side,
                account_category,
                account_subcategory,
                initial_balance,
                debit,
                credit,
                balance,
                created_at,
                modified_at,
                ModifiedBy,
                user_id,
                account_order,
                statement,
                IsActive

                comment,
              
            ) VALUES (
                '$account_name',
                '$account_number',
                '$account_description',
                '$normal_side',
                '$account_category',
                '$account_subcategory',
                $initial_balance,
                0, -- Default debit
                0, -- Default credit
                $initial_balance, -- Initial balance
                NOW(), -- created_at
                NOW(), -- modified_at
                '$username', -- modified_by
                $user_id,
                '$account_order',
                '$statement',
                TRUE, -- new account set to active
                '$comment'
            )";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the view all accounts page after successful insertion
        header('Location: ./view_all_client_accounts.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Client Account</title>
    <link rel="stylesheet" href="./styles.css"> 
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

<div class="main">
    <div class="container">
        <h2>Create New Client Account</h2>
        <form method="POST" action="">
            <input type="text" name="account_name" placeholder="Account Name" required>
            <input type="text" name="account_number" placeholder="Account Number" required>
            <textarea name="account_description" placeholder="Account Description"></textarea>
            <select name="normal_side" required>
                <option value="debit">Debit</option>
                <option value="credit">Credit</option>
            </select>

            <select name="account_category" required>
                <option value="" disabled selected>Select Account Category</option>
                <option value="Asset">Asset</option>
                <option value="Equity">Equity</option>
                <option value="Expense">Expense</option>
                <option value="Revenue">Revenue</option>
                <option value="Liability">Liability</option>
            </select>

            <select name="account_subcategory" required>
                <option value="" disabled selected>Select Account Subcategory</option>
                <option value="Current Assets">Current Assets</option>
                <option value="Fixed Assets">Fixed Assets</option>
                <option value="Intangible Assets">Intangible Assets</option>
                <option value="Current Liabilities">Current Liabilities</option>
                <option value="Long-Term Liabilities">Long-Term Liabilities</option>
                <option value="Owner's Equity">Owner's Equity</option>
                <option value="Shareholder Equity">Shareholder Equity</option>
                <option value="Sales Revenue">Sales Revenue</option>
                <option value="Service Revenue">Service Revenue</option>
                <option value="Operating Expense">Operating Expense</option>
                <option value="Cost of Goods Sold">Cost of Goods Sold</option>
                <option value="Depreciation Exprense">Depreciation Expense</option>
            </select>

            <input type="number" step="0.01" name="initial_balance" placeholder="Initial Balance" required>
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>"> <!-- Assuming user_id is set -->
            <input type="text" name="account_order" placeholder="Account Order"><br><br>
            <select name="statement" required>
                <option value="" disabled selected>Select Statement Type</option>
                <option value="IS">Income Statement</option>
                <option value="BS">Balance Sheet</option>
                <option value="RE">Retained Earnings</option>
            </select><br><br>
            <textarea name="comment" placeholder="Comment"></textarea><br><br>
            <button type="submit">Create Account</button>
        </form>
    </div>
</div>

</body>
</html>

