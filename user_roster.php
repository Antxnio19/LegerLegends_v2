<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select all users from UserTable
$sql = "SELECT * FROM EmployeeAccounts";
$result = $conn->query($sql);

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];
$userId = $_SESSION['Id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css">
    <title>User Information Table</title>
    
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1>Ledger Legend Administrator</h1>
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
            <button class="dropbtn">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php">Create User</a>
                <a href="./user_roster.php">View Users</a>
                <a href="./Manage_Users.php">Account Approval</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Reports</button>
            <div class="dropdown-content">
                <a href="#">User Report</a>
                <a href="./Expired_Passwords_Log.php">Expired Passwords Report</a>
                <a href="#">Login Attempts Report</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Notifications</button>
            <div class="dropdown-content">
                <a href="#">Password Expiration Alerts</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Email Management</button>
            <div class="dropdown-content">
                <a href="#">Send Email</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Settings</button>
            <div class="dropdown-content">
                <a href="#">System Settings</a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Type ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email Address</th>
                    <th>Date of Birth</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Failed Attempts</th>
                    <th>Lockout Until</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . $row['Id'] . '</td>
                                <td>' . $row['UserTypeId'] . '</td>
                                <td>' . $row['Username'] . '</td>
                                <td>********</td> <!-- Displaying 8 stars for password -->
                                <td>' . $row['EmailAddress'] . '</td>
                                <td>' . $row['DateOfBirth'] . '</td>
                                <td>' . $row['FirstName'] . '</td>
                                <td>' . $row['LastName'] . '</td>
                                <td>' . $row['FailedAttempts'] . '</td>
                                <td>' . $row['LockoutUntil'] . '</td>
                                <td><button class="update-button" onclick="window.location.href=\'update_user.php?id=' . $row['Id'] . '\'">Update</button></td>
                            </tr>';
                    }
                } else {
                    echo '<tr><td colspan="11">No users found</td></tr>';
                }
                // Close the connection
                $conn->close();
                ?>
            </tbody>
        </table>    
    </div>
</body>
</html>

