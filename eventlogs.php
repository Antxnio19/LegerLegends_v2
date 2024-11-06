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
$conn = mysqli_connect($host, $user, $pass, $db, 8889);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize filter variables
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$eventID = isset($_GET['EventID']) ? $_GET['EventID'] : '';
$UserId = isset($_GET['UserId']) ? $_GET['UserId'] : '';
$username = isset($_GET['Username']) ? $_GET['Username'] : '';
$userAcctType = isset($_GET['UserAcctType']) ? $_GET['UserAcctType'] : '';
$timeOfChange = isset($_GET['DateANDTime']) ? $_GET['DateANDTime'] : '';
$accountsAffected = isset($_GET['AcctAffected']) ? $_GET['AcctAffected'] : '';

// Build the query with dynamic filtering and search functionality
$sql = "SELECT * FROM user_eventlog WHERE 1=1";  // Start with a condition that’s always true

if (!empty($searchQuery)) {
    // Check if search query starts with 'E' for EventID matching
    if (strpos($searchQuery, 'E') === 0) {
        // Exact match for EventID (like 'E005')
        $sql .= " AND EventID = '" . $conn->real_escape_string($searchQuery) . "'";
    } else {
        // For numbers like '5', search for partial match in EventID, UserId, or Username
        $sql .= " AND (EventID LIKE '%" . $conn->real_escape_string($searchQuery) . "%' 
                    OR UserId LIKE '%" . $conn->real_escape_string($searchQuery) . "%' 
                    OR Username LIKE '%" . $conn->real_escape_string($searchQuery) . "%')";
    }
}


if (!empty($eventID)) {
    $sql .= " AND EventID = '" . (int)$eventID . "'";
}

if (!empty($UserId)) {
    $UserId = (int)$UserId; // Ensure UserId is treated as an integer
    $sql .= " AND UserId = '$UserId'";
}

if (!empty($username)) {
    $sql .= " AND Username LIKE '%" . $conn->real_escape_string($username) . "%'";
}

if (!empty($userAcctType)) {
    $sql .= " AND UserAcctType = '" . $conn->real_escape_string($userAcctType) . "'";
}

if (!empty($timeOfChange)) {
    $sql .= " AND DateANDTime = '" . $conn->real_escape_string($timeOfChange) . "'";
}

if (!empty($accountsAffected)) {
    $sql .= " AND AcctAffected LIKE '%" . $conn->real_escape_string($accountsAffected) . "%'";
}

// Execute the query
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css">
    <link rel="stylesheet" href="./filter_search_styles.css">
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <title>Event Logs</title>
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
	<body>
	<div class="main">
        <h1 style="color: white; text-align: center;">Event Logs</h1>

        <!-- Search Bar -->
        <div class="search-bar-container">
            <form method="GET" action="eventlogs.php">
                <input type="text" name="search" class="search-bar" placeholder="Search by Event ID or User Info" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="search-button">Search</button>
                <button type="reset" class="reset-button" onclick="window.location.href='eventlogs.php'">Reset</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>EventID</th>
                    <th>UserId</th>
                    <th>Username</th>
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
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Format EventID as E009
                        $formattedEventID = 'E' . sprintf('%03d', $row['EventID']);
                        echo '<tr>
                            <td>' . htmlspecialchars($formattedEventID) . '</td>
                            <td>' . htmlspecialchars($row['UserId']) . '</td>
                            <td>' . htmlspecialchars($row['Username']) . '</td>
                            <td>' . htmlspecialchars($row['UserAcctType']) . '</td>
                            <td>' . htmlspecialchars($row['DateANDTime']) . '</td>
                            <td>' . htmlspecialchars($row['AcctAffected']) . '</td>
                            <td>' . htmlspecialchars($row['BeforeAffected']) . '</td>
                            <td>' . htmlspecialchars($row['AfterAffected']) . '</td>
                            <td>' . htmlspecialchars($row['Event_Status']) . '</td>
                        </tr>';
                    }
                } else {
                    echo '<tr><td colspan="9">No results found</td></tr>';
                }

                // Close the connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
