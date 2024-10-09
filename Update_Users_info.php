<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Information</title>
    <link rel="stylesheet" href="./user_roaster_stylesheet.css">
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1>Ledger Ledgend Administrator</h1>
        </div>
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username">Jtrejo0924</span>
            <a href="./logout.html" class="logout-btn">Logout</a>
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

    <div class="main-content">
        <div class="form-container">
            <h2>Update User Information</h2>
            <form action="./submit_update.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>"> <!-- Hidden field for user ID -->
                <p>User ID: <?php echo $user['id']; ?></p> <!-- Debugging line to see user ID -->

                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($user['first_name']); ?>"><br>

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($user['last_name']); ?>"><br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"><br>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>"><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"><br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"><br>

                <label for="date-created">Date Created:</label>
                <input type="date" id="date-created" name="date-created" value="<?php echo htmlspecialchars($user['date_created']); ?>" readonly><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>"><br>

                <label for="position">Position:</label>
                <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($user['position']); ?>"><br>

                <label for="expiry-duration">Password Expiry Duration (Days):</label>
                <input type="number" id="expiry-duration" name="expiry-duration" value="<?php echo htmlspecialchars($user['expiry_duration']); ?>"><br>

                <button type="submit" class="submit-button">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>