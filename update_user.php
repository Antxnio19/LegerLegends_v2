<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$host = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'accounting_db';
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $stmt = $conn->prepare("SELECT * FROM Table1 WHERE Id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("No user found with the specified ID.");
    }
} else {
    die("No user ID specified.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Information</title>
    <link rel="stylesheet" href="./user_roaster_stylesheet.css">
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1 style="color: white;">Ledger Legends Administrator</h1>
        </div>
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span>
            <a href="./logout.php" class="logout-btn">Logout</a>
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
                <a href="./tbd.php" title="Create a new journal entry">Journalize</a>
                <a href="./tbd.php" title="Create a new journal entry">View Jounral Entries</a>
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
        <a href="./help.php" class="nav-link help-btn" title="Get help and support">&#x2753;</a>
    </div>

    <!-- Calculator Modal -->
    <div id="calculatorModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeCalculator">&times;</span>
            <h2>Calculator</h2>
            <div class="calculator">
                <input type="text" id="calcDisplay" disabled>
                <div class="calculator-buttons">
                    <button onclick="appendToDisplay('7')">7</button>
                    <button onclick="appendToDisplay('8')">8</button>
                    <button onclick="appendToDisplay('9')">9</button>
                    <button onclick="appendToDisplay('/')">/</button>

                    <button onclick="appendToDisplay('4')">4</button>
                    <button onclick="appendToDisplay('5')">5</button>
                    <button onclick="appendToDisplay('6')">6</button>
                    <button onclick="appendToDisplay('*')">*</button>

                    <button onclick="appendToDisplay('1')">1</button>
                    <button onclick="appendToDisplay('2')">2</button>
                    <button onclick="appendToDisplay('3')">3</button>
                    <button onclick="appendToDisplay('-')">-</button>

                    <button onclick="appendToDisplay('0')">0</button>
                    <button onclick="calculateResult()">=</button>
                    <button onclick="clearDisplay()">C</button>
                    <button onclick="appendToDisplay('+')">+</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Modal -->
    <div id="calendarModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeCalendar">&times;</span>
            <h2>Calendar</h2>
            <div id="calendar"></div>
        </div>
    </div>
    <script src="modals_calculator_calendar.js"></script>

    <div class="main-content">
        <div class="form-container">
            <h2 style="color: white;">Update User Information</h2>
            <form action="./submit_update.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['Id']); ?>">
                <p style="color: white;">User ID: <?php echo htmlspecialchars($user['Id']); ?></p>

                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($user['FirstName'] ?? ''); ?>" required><br>

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($user['LastName'] ?? ''); ?>" required><br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['Address'] ?? ''); ?>" required><br>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['DateOfBirth'] ?? ''); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['EmailAddress'] ?? ''); ?>" required><br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['Username'] ?? ''); ?>" required><br>

                <label for="password">Password:</label>
                <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($user['Password'] ?? ''); ?>" required><br>

                <label for="user-type-id">Position:</label>
                <input type="text" id="user-type-id" name="user-type-id" value="<?php echo htmlspecialchars($user['UserTypeId'] ?? ''); ?>" required><br>

                <label for="expiry-duration">Password Expiry Duration (Days):</label>
                <input type="number" id="expiry-duration" name="expiry-duration" value="<?php echo htmlspecialchars($user['ExpiryDuration'] ?? ''); ?>" required><br>

                <button type="submit" class="submit-button">Submit</button>
            </form>
        </div>
    </div>

    <script>
        // Add JavaScript here if needed
    </script>
</body>
</html>

<?php
$conn->close();
?>
