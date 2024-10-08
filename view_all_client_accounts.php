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
$dbUsername = "root"; // Replace with your database username
$dbPassword = "root"; // Replace with your database password
$dbname = "accounting_db"; // Replace with your database name

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select all accounts
$sql = "SELECT * FROM Client_Accounts";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./it_ticket_stylesheet.css">


    <title>All Accounts</title>
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

    <div class="main">
        <h1>All Accounts</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Description</th>
                    <th>Normal Side</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Initial Balance</th>
                    <th>User ID</th>
                    <th>Order</th>
                    <th>Statement</th>
                    <th>Comment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . $row['id'] . '</td>
                                <td>' . htmlspecialchars($row['account_name']) . '</td>
                                <td>' . htmlspecialchars($row['account_number']) . '</td>
                                <td>' . htmlspecialchars($row['account_description']) . '</td>
                                <td>' . htmlspecialchars($row['normal_side']) . '</td>
                                <td>' . htmlspecialchars($row['account_category']) . '</td>
                                <td>' . htmlspecialchars($row['account_subcategory']) . '</td>
                                <td>' . number_format($row['initial_balance'], 2) . '</td>
                                <td>' . $row['user_id'] . '</td>
                                <td>' . htmlspecialchars($row['account_order']) . '</td>
                                <td>' . htmlspecialchars($row['statement']) . '</td>
                                <td>' . htmlspecialchars($row['comment']) . '</td>
                                <td><button class="update-button" onclick="window.location.href=\'edit_client_account.php?id=' . $row['id'] . '\'">Edit</button><br><br>
                                <button class="view-button" onclick="window.location.href=\'view_client_account.php?id=' . $row['id'] . '\'">View</button></td>
                            </tr>';
                    }
                } else {
                    echo '<tr><td colspan="13">No accounts found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
