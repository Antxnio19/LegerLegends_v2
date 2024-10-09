<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];

// Database connection (replace with your actual connection details)
$host = 'localhost'; // Database host
$user = 'root'; // Database username
$pass = 'root'; // Database password
$db = 'accounting_db'; // Database name

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch event logs
$sql = "SELECT * FROM user_eventlog"; // Corrected table name
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css">
    <title>User Event Log</title>
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1 style="color: white;">Ledger Legend Administrator</h1>
        </div>
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
                <a href="./create_new_user_admin.php">Create User</a>
                <a href="./user_roster.php">View Users</a>
                <a href="./Manage_Users.php">Account Approval</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./create_client_account_admin.php" >Create Account</a>
                <a href="./view_all_client_accounts.php" >Chart of Accounts</a>
                <a href="./View_some_accounts.php" >Accounts</a>
                <a href="./" >Deactivate Accounts</a>
            </div>
        </div>

        <!-- Reports dropdown -->
        <div class="dropdown">
            <button class="dropbtn nav-link">Reports</button>
            <div class="dropdown-content">
                <a href="./Expired_Passwords_Log.php">Expired Passwords Report</a>
                <a href="#">Event logs</a>
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

    <div class="main-content">
        <table>
            <thead>
                <tr>
                    <th>UUID</th>
                    <th>UserID</th>
                    <th>UserAcctType</th>
                    <th>Time of Change</th>
                    <th>Acct Affected</th>
                    <th>Before Change</th>
                    <th>After Change</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . htmlspecialchars($row['AutoID']) . '</td>
                                <td>' . htmlspecialchars($row['UserID']) . '</td>
                                <td>' . htmlspecialchars($row['UserAcctType']) . '</td>
                                <td>' . htmlspecialchars($row['DateANDTime']) . '</td>
                                <td>' . htmlspecialchars($row['AcctAffected']) . '</td>
                                <td>' . htmlspecialchars($row['BeforeAffected']) . '</td>
                                <td>' . htmlspecialchars($row['AfterAffected']) . '</td>
                                <td>' . htmlspecialchars($row['STATUS']) . '</td>
                            </tr>';
                    }
                } else {
                    echo '<tr><td colspan="8">No events found</td></tr>';
                }
                // Close the connection
                $conn->close();
                ?>
            </tbody>
        </table>    
    </div>
</body>
</html>

</body>
</html>
