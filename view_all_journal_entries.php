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
$sql = "SELECT * FROM Journal_Entries";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./it_ticket_stylesheet.css">
    <link rel="stylesheet" href ="styles.css">


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
                <a href="./view_all_journal_entries.php" >View Journal Entries</a>
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
        <h1>All Journal Entries</h1><br><br>
        <button> <a href="./add_journal_entry.php"> Add Journal Entry </a></button>

        <div class="button-container" style="float: right;">
        <button onclick="filterEntries('all')">All</button>
        <button onclick="filterEntries('approved')">Approved</button>
        <button onclick="filterEntries('pending')">Pending</button>
        <button onclick="filterEntries('rejected')">Rejected</button>
    </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account Type</th>
                    <th>Account Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Created On</th>
                    <!-- CREATED BY = MODIFEDBY IN DB SCHEMA -->
                    <th>Created By</th>  
                    <th>Status</th>
                    <th>Comment</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . $row['id'] . '</td>
                                <td>' . htmlspecialchars($row['account_type']) . '</td>
                                <td>' . htmlspecialchars($row['account_description']) . '</td>
                                <td class="right-align">$ ' . number_format((float) $row['debit'], 2, '.', '') . '</td> <!-- Add dollar sign and format -->
                                <td class="right-align">$ ' . number_format((float) $row['credit'], 2, '.', '') . '</td> <!-- Add dollar sign and format -->

                                <td>' . htmlspecialchars($row['created_at']) . '</td>
                                <td>' . htmlspecialchars($row['ModifiedBy']) . '</td>
                                <td>' . htmlspecialchars($row['IsApproved']) . '</td>
                                <td>' . htmlspecialchars($row['comment']) . '</td>
                                
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
