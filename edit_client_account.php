<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

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
    <link rel="stylesheet" href="./it_ticket_stylesheet.css">

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
        <!-- Home and IT Ticket as separate clickable links -->
        <a href="./administrator_home.php" class="nav-link">Home</a>
        <a href="./it_ticket.php" class="nav-link">IT Ticket</a>

        <!-- User Management dropdown -->
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
                <a href="">Send Email</a>
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

    <h1>Edit Account</h1>

    <form method="POST" action="http://localhost:8888/LegerLegends_v2/LegerLegends_v2/edit_client_account_submit.php" >
        <input type="hidden" name="id" value="<?php echo $account['id']; ?>">
        
        <label>Account Name:</label><br>
        <input type="text" name="account_name" value="<?php echo $account['account_name']; ?>" required><br>
        
        <label>Account Number:</label><br>
        <input type="text" name="account_number" value="<?php echo $account['account_number']; ?>" required><br>
        
        <label>Account Description:</label><br>
        <textarea name="account_description"><?php echo $account['account_description']; ?></textarea><br>
        
        <label>Normal Side:</label><br>
        <select name="normal_side" required>
            <option value="debit" <?php echo ($account['normal_side'] == 'debit') ? 'selected' : ''; ?>>Debit</option>
            <option value="credit" <?php echo ($account['normal_side'] == 'credit') ? 'selected' : ''; ?>>Credit</option>
        </select><br>
        
        <label>Account Category:</label><br>
        <input type="text" name="account_category" value="<?php echo $account['account_category']; ?>"><br>
        
        <label>Account Subcategory:</label><br>
        <input type="text" name="account_subcategory" value="<?php echo $account['account_subcategory']; ?>"><br>
        
        <label>Initial Balance:</label><br>
        <input type="number" step="0.01" name="initial_balance" value="<?php echo $account['initial_balance']; ?>" required><br>
        
        <label>User ID:</label><br>
        <input type="number" name="user_id" value="<?php echo $account['user_id']; ?>" required><br>
        
        <label>Order:</label><br>
        <input type="text" name="account_order" value="<?php echo $account['account_order']; ?>"><br>
        
        <label>Statement:</label><br>
        <select name="statement" required>
            <option value="IS" <?php echo ($account['statement'] == 'IS') ? 'selected' : ''; ?>>Income Statement</option>
            <option value="BS" <?php echo ($account['statement'] == 'BS') ? 'selected' : ''; ?>>Balance Sheet</option>
            <option value="RE" <?php echo ($account['statement'] == 'RE') ? 'selected' : ''; ?>>Retained Earnings</option>
        </select><br>
        
        <label>Comment:</label><br>
        <textarea name="comment"><?php echo $account['comment']; ?></textarea><br>
        
        <button type="submit" name="update_account">Update Account</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
