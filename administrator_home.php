<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="icon" type="image/png" href="profile.png">
    <title>Ledger Legend Administrator Page</title>
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
            <button class="dropbtn">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php">Create User</a>
                <a href="./user_roaster.php">View Users</a>
                <a href="./Manage_Users.php">Account Approval</a>
            </div>
        </div>

        <!-- Reports dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Reports</button>
            <div class="dropdown-content">
                <a href="#">User Report</a>
                <a href="./Expired_Passwords_Log.php">Expired Passwords Report</a>
                <a href="#">Login Attempts Report</a>
            </div>
        </div>

        <!-- Notifications dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Notifications</button>
            <div class="dropdown-content">
                <a href="">Password Expiration Alerts</a>
            </div>
        </div>

        <!-- Email Management dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Email Management</button>
            <div class="dropdown-content">
                <a href="">Send Email</a>
            </div>
        </div>

        <!-- Settings dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Settings</button>
            <div class="dropdown-content">
                <a href="#">System Settings</a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <!-- Content area -->
    </div>
</body>
</html>