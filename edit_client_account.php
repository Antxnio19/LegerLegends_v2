<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}

$username = $_SESSION['username'];
$userId = $_SESSION['Id'];

$servername = "localhost";
$username = "root"; 
$password = "root"; 
$dbname = "accounting_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_account'])) {
    $id = $_POST['id'];
    $account_name = $_POST['account_name'];
    $account_number = $_POST['account_number'];
    $account_description = $_POST['account_description'];
    $normal_side = $_POST['normal_side'];
    $account_category = $_POST['account_category'];
    $account_subcategory = $_POST['account_subcategory'];
    $initial_balance = $_POST['initial_balance'];
    $user_id = $_POST['user_id'];
    $account_order = $_POST['account_order'];
    $statement = $_POST['statement'];   
    $isActive = $_POST['isActive'];
    $comment = $_POST['comment'];

    $sql = "UPDATE Client_Accounts SET 
                account_name='$account_name', 
                account_number='$account_number', 
                account_description='$account_description', 
                normal_side='$normal_side', 
                account_category='$account_category', 
                account_subcategory='$account_subcategory', 
                initial_balance='$initial_balance', 
                user_id='$user_id', 
                account_order='$account_order', 
                statement='$statement',
                IsActive = '$isActive',
                comment='$comment',
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Account updated successfully!";
    } else {
        echo "Error updating account: " . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM Client_Accounts WHERE id='$id'";
    $result = $conn->query($sql);
    $account = $result->fetch_assoc();
} else {
    die("No account ID specified.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./account_creation.css">

    <title>Edit Account</title>
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
                <a href="./create_new_user_admin.php" >Create User</a>
                <a href="./user_roster.php" >View Users</a>
                <a href="./Manage_Users.html" >Account Approval</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./create_client_account_admin.php" >Create Account</a>
                <a href="./view_all_client_accounts.php" >View All Accounts</a>
                <a href="./edit_client_account.php" > Edit Client Account</a>
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
            <h1 style="color: white;">Edit Account</h1>

            <form method="POST" action="./edit_client_account_submit.php" >
                <input type="hidden" name="id" value="<?php echo $account['id']; ?>">
                
                <label style="color: white;">Account Name:</label><br>
                <input type="text" name="account_name" value="<?php echo $account['account_name']; ?>" required><br>
                
                <label style="color: white;">Account Number:</label><br>
                <input type="text" name="account_number" value="<?php echo $account['account_number']; ?>" required><br>
                
                <label style="color: white;">Account Description:</label><br>
                <textarea name="account_description"><?php echo $account['account_description']; ?></textarea><br>
                
                <label style="color: white;">Normal Side:</label><br>
                <select name="normal_side" required>
                    <option value="debit" <?php echo ($account['normal_side'] == 'debit') ? 'selected' : ''; ?>>Debit</option>
                    <option value="credit" <?php echo ($account['normal_side'] == 'credit') ? 'selected' : ''; ?>>Credit</option>
                </select><br>

                <label style="color: white;">Account Category:</label><br>
                <select name="account_category" required>
                    <option value="" disabled>Select Account Category</option>
                    <option value="Asset" <?php if ($account['account_category'] == 'Asset') echo 'selected'; ?>>Asset</option>
                    <option value="Equity" <?php if ($account['account_category'] == 'Equity') echo 'selected'; ?>>Equity</option>
                    <option value="Expense" <?php if ($account['account_category'] == 'Expense') echo 'selected'; ?>>Expense</option>
                    <option value="Revenue" <?php if ($account['account_category'] == 'Revenue') echo 'selected'; ?>>Revenue</option>
                    <option value="Liability" <?php if ($account['account_category'] == 'Liability') echo 'selected'; ?>>Liability</option>
                </select><br>

                <label style="color: white;">Account Subcategory:</label><br>
                <select name="account_subcategory" required>
                    <option value="" disabled>Select Account Subcategory</option>

                    <option value="Current Assets" <?php if ($account['account_subcategory'] == 'Current Assets') echo 'selected'; ?>>Current Assets</option>
                    <option value="Fixed Assets" <?php if ($account['account_subcategory'] == 'Fixed Assets') echo 'selected'; ?>>Fixed Assets</option>
                    <option value="Intangible Assets" <?php if ($account['account_subcategory'] == 'Intangible Assets') echo 'selected'; ?>>Intangible Assets</option>
                    <option value="Current Liabilities" <?php if ($account['account_subcategory'] == 'Current Liabilities') echo 'selected'; ?>>Current Liabilities</option>
                    <option value="Long-Term Liabilities" <?php if ($account['account_subcategory'] == 'Long-Term Liabilities') echo 'selected'; ?>>Long-Term Liabilities</option>
                    <option value="Owner's Equity" <?php if ($account['account_subcategory'] == "Owner's Equity") echo 'selected'; ?>>Owner's Equity</option>
                    <option value="Shareholder Equity" <?php if ($account['account_subcategory'] == 'Shareholder Equity') echo 'selected'; ?>>Shareholder Equity</option>
                    <option value="Sales Revenue" <?php if ($account['account_subcategory'] == 'Sales Revenue') echo 'selected'; ?>>Sales Revenue</option>
                    <option value="Service Revenue" <?php if ($account['account_subcategory'] == 'Service Revenue') echo 'selected'; ?>>Service Revenue</option>
                    <option value="Operating Expense" <?php if ($account['account_subcategory'] == 'Operating Expense') echo 'selected'; ?>>Operating Expense</option>
                    <option value="Cost of Goods Sold" <?php if ($account['account_subcategory'] == 'Cost of Goods Sold') echo 'selected'; ?>>Cost of Goods Sold</option>
                    <option value="Depreciation Expense" <?php if ($account['account_subcategory'] == 'Depreciation Expense') echo 'selected'; ?>>Depreciation Expense</option>
                </select><br>
                

                
                <label style="color: white;">Initial Balance:</label><br>
                <input type="number" step="0.01" name="initial_balance" value="<?php echo $account['initial_balance']; ?>" required><br>
                
                <label style="color: white;">User ID:</label><br>
                <input type="number" name="user_id" value="<?php echo $account['user_id']; ?>" required><br>
                
                <label style="color: white;">Order:</label><br>
                <input type="text" name="account_order" value="<?php echo $account['account_order']; ?>"><br>
                
                <label style="color: white;">Statement:</label><br>
                <select name="statement" required>
                    <option value="IS" <?php echo ($account['statement'] == 'IS') ? 'selected' : ''; ?>>Income Statement</option>
                    <option value="BS" <?php echo ($account['statement'] == 'BS') ? 'selected' : ''; ?>>Balance Sheet</option>
                    <option value="RE" <?php echo ($account['statement'] == 'RE') ? 'selected' : ''; ?>>Retained Earnings</option>
                </select><br>
                <label style="color: white;">Account Status:</label><br>
                <select id="isActive" name="isActive" required>
                    <option value="1" <?php echo ($account['IsActive']  ? 'selected' : ''); ?>>Active</option>
                    <option value="0" <?php echo ($account['IsActive']  ? 'selected' : ''); ?>>Deactivate</option>
                </select><br>
                
                
                <label style="color: white;">Comment:</label><br>
                <textarea name="comment"><?php echo $account['comment']; ?></textarea><br>
                
                <button type="submit" name="update_account">Update Account</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>