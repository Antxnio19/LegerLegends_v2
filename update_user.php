
<?php
// Start session
session_start();
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];
$userId = $_SESSION['Id'];

// Create connection
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from URL
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query to select user data
$sql = "SELECT * FROM EmployeeAccounts WHERE Id = $userId";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./it_ticket_stylesheet.css">

    <title>Update User</title>
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
                <a href="./Manage_Users.php" >Account Approval</a>
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

    <h1>Update User Information</h1>
    <form  action="http://localhost:8888/LegerLegends_v2/LegerLegends_v2/update_user_submit.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $user['Id']; ?>">
        
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" required><br><br>

        <label for="email">Email Address:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['EmailAddress']); ?>" required><br><br>

        <label for="firstName">First Name:</label><br>
        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required><br><br>

        <label for="lastName">Last Name:</label><br>
        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['LastName']); ?>" required><br><br>

        <label for="isActive">Account Status:</label><br>
        <select id="isActive" name="isActive">
            <option value="1" <?php echo ($user['IsActive'] ? 'selected' : ''); ?>>Activate</option>
            <option value="0" <?php echo (!$user['IsActive'] ? 'selected' : ''); ?>>Deactivate</option>
        </select><br><br>

        <label for="lockoutUntil">Lockout Until:</label><br>
        <input type="date" id="lockoutUntil" name="lockoutUntil" value="<?php echo $user['LockoutUntil'] ? date('Y-m-d', strtotime($user['LockoutUntil'])) : ''; ?>"><br><br>

        <input type="submit" value="Update User">
    </form>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
