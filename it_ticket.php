<?php
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
    <link rel="stylesheet" href="./it_ticket_stylesheet.css">
    <link rel="icon" type="image/png" href="profile.png">
    <title>IT Ticket</title>
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1>Ledger Ledgend Administrator</h1>
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
        <a href="./administrator_home.php" class="nav-link">Home</a>
        <a href="./it_ticket.php" class="nav-link">IT Ticket</a>

        <!-- User Management dropdown -->
        <div class="dropdown">
            <button class="dropbtn">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php">Create User</a>
                <a href="./user_roaster.php">View Users</a>
                <a href="./Manage_Users.html">Account Approval</a>
            </div>
        </div>

        <!-- Reports dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Reports</button>
            <div class="dropdown-content">
                <a href="#">User Report</a>
                <a href="./Expired_Passwords_Log.html">Expired Passwords Report</a>
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
        <h2 class="form-title">Submit an IT Ticket</h2>
        <form class="ticket-form" action="http://localhost:8888/LegerLegends_v2/LegerLegends_v2/it_ticket_submit.php" method="POST">
            <input type="hidden" name="userId" value="<?php echo isset($_SESSION['Id']) ? htmlspecialchars($_SESSION['Id']) : ''; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
    
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
    
            <label for="issue">Issue Description:</label>
            <textarea id="issue" name="issue" rows="4" required></textarea>
    
            <label for="priority">Priority:</label>
            <select id="priority" name="priority">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
    
            <button type="submit" class="submit-btn">Submit Ticket</button>
        </form>
    </div>
</body>
</html>