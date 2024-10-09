<?php
session_start(); // Start the session

/* Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}*/

// Store the username from the session
$username = $_SESSION['username'];
$userId = $_SESSION['Id'];

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "root"; // Replace with your database password
$dbname = "accounting_db"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for updating an account
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
                comment='$comment'
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Account updated successfully!";
    } else {
        echo "Error updating account: " . $conn->error;
    }
}

// Fetch account details for editing
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
        <!-- Profile and logout section -->
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span> <!-- Display the dynamic username here -->
            <a href="./logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <!-- Navigation Bar -->
    <div class="main-bar">
        <a href="./administrator_home.php" class="nav-link" title="Takes you to the home page">Home</a>
        <a href="./it_ticket.php" class="nav-link" title="Submit an IT ticket">IT Ticket</a>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage user accounts">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php" title="Create a new user">Create User</a>
                <a href="./user_roster.php" title="View existing users">View Users</a>
                <a href="./Manage_Users.php" title="Approve user accounts">Account Approval</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage client accounts">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./create_client_account_admin.php" title="Create a new client account">Create Account</a>
                <a href="./view_all_client_accounts.php" title="View all client accounts">Chart of Accounts</a>
                <a href="./View_some_accounts.php" title="View specific client accounts">Accounts</a>
                <a href="#" title="Deactivate client accounts">Deactivate Accounts</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Generate reports">Reports</button>
            <div class="dropdown-content">
                <a href="./Expired_Passwords_Log.php" title="View expired passwords report">Expired Passwords Report</a>
                <a href="./eventlogs.php" title="View event logs">Event logs</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage notifications">Notifications</button>
            <div class="dropdown-content">
                <a href="#" title="View password expiration alerts">Password Expiration Alerts</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage email communications">Email Management</button>
            <div class="dropdown-content">
                <a href="./Email.php" title="Send emails to users">Email Users</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Adjust system settings">Settings</button>
            <div class="dropdown-content">
                <a href="#" title="Configure system settings">System Settings</a>
            </div>
        </div>

        <!-- Buttons to open calculator and calendar -->
        <button id="calculatorBtn" class="nav-link" title="Open the calculator">Calculator</button>
        <button id="calendarBtn" class="nav-link" title="Open the calendar">Calendar</button>
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
        <div class="container">
            <h2 style="color: white;">Edit Account</h2>
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
                <input type="text" name="account_category" value="<?php echo $account['account_category']; ?>"><br>
                
                <label style="color: white;">Account Subcategory:</label><br>
                <input type="text" name="account_subcategory" value="<?php echo $account['account_subcategory']; ?>"><br>
                
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
