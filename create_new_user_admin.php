<?php
session_start(); // Start the session at the very beginning
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    <title>Create New User</title>

   
    <link rel="stylesheet" href="./styles.css">
   

</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1>Ledger Ledgend Administrator</h1>
        </div>
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span> <!-- Display the dynamic username here -->
            <a href="./logout.html" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="main-bar">
        <a href="./administrator_home.php" class="nav-link">Home</a>
        <a href="./it_ticket.php" class="nav-link">IT Ticket</a>
        <div class="dropdown">
            <button class="dropbtn" class="nav-link">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php">Create User</a>
                <a href="http://localhost:8888/LegerLegends_v2/LegerLegends_v2/user_roster.php">View Users</a>
                <a href="./Manage_Users.html">Account Approval</a>
            </div>
        </div>
        
        <div class="dropdown">
            <button class="dropbtn nav-link">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./create_client_account_admin.php" >Create Account</a>
                <a href="./view_all_client_accounts.php" >View All Accounts</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link">Reports</button>
            <div class="dropdown-content">
                <a href="#">User Report</a>
                <a href="./Expired_Passwords_Log.html">Expired Passwords Report</a>
                <a href="#">Login Attempts Report</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn nav-link">Notifications</button>
            <div class="dropdown-content">
                <a href="">Password Expiration Alerts</a>
            </div>
        </div>
        <div class="dropdown nav-link">
            <button class="dropbtn">Email Management</button>
            <div class="dropdown-content">
                <a href="">Send Email</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Settings</button>
            <div class="dropdown-content">
                <a href="#">System Settings</a>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="container">
            <h2>Create New User</h2>
            <form id="createUserForm" method="POST" action="http://localhost:8888/LegerLegends_v2/LegerLegends_v2/create_new_user_admin_submit.php">
                
                <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
                <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <input type="date" id="dob" name="dob" required min="1920-01-01" max="2024-12-31">
                <input type="text" id="address" name="address" placeholder="Address" required>

                <input type="text" id="username" name="username" placeholder="Create Username" required>
                <input type="password" id="password" name="password" placeholder="Create Password" required>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>

                <div id="passwordRequirements">
                    <p id="lengthRequirement" class="invalid">Minimum 8 characters</p>
                    <p id="letterRequirement" class="invalid">Starts with a letter</p>
                    <p id="numberRequirement" class="invalid">Contains a number</p>
                    <p id="specialRequirement" class="invalid">Contains a special character</p>
                </div>
                <select id="UserTypeId" name="UserTypeId" required>
                    //numberical values from UserTypeTable 1. Administrator 2. Manager 3. Accountant 4. User(Client)
                    <option value="" disabled selected>Select User Role</option> 
                    <option value="1">Administrator</option>
                    <option value="2">Manager</option> 
                    <option value="3">Accountant</option>
                    <option value="4">User</option>
                </select>

                <select id="securityQuestion" name="securityQuestion" required>
                    <option value="" disabled selected>Select a Security Question</option>
                    <option value="q1">What was your first pet's name?</option>
                    <option value="q2">What is your mother's maiden name?</option>
                    <option value="q3">What was the make of your first car?</option>
                    <option value="q4">What is your favorite book?</option>
                    <option value="q5">What city were you born in?</option>
                </select>
                <input type="text" id="securityAnswer" name="securityAnswer" placeholder="Answer" required>

                <button type="submit">Submit Request</button>
            </form>
        </div>
    </div>

    <script src="New_Password.js"></script>
</body>
