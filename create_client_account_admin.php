<?php
session_start(); 
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit();
}

$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
    $account_description = mysqli_real_escape_string($conn, $_POST['account_description']);
    $normal_side = mysqli_real_escape_string($conn, $_POST['normal_side']);
    $account_category = mysqli_real_escape_string($conn, $_POST['account_category']);
    $account_subcategory = mysqli_real_escape_string($conn, $_POST['account_subcategory']);
    $initial_balance = floatval($_POST['initial_balance']);
    $user_id = intval($_POST['user_id']);
    $account_order = mysqli_real_escape_string($conn, $_POST['account_order']);
    $statement = mysqli_real_escape_string($conn, $_POST['statement']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $sql = "INSERT INTO Client_Accounts (
                account_name,
                account_number,
                account_description,
                normal_side,
                account_category,
                account_subcategory,
                initial_balance,
                balance,
                created_at,
                modified_at,
                ModifiedBy,
                user_id,
                account_order,
                statement,
                comment,
                IsActive
            ) VALUES (
                '$account_name',
                '$account_number',
                '$account_description',
                '$normal_side',
                '$account_category',
                '$account_subcategory',
                $initial_balance,
                $initial_balance,
                NOW(), 
                NOW(), 
                '$username', 
                $user_id,
                '$account_order',
                '$statement',
                '$comment',
                TRUE 
            )";

    if ($conn->query($sql) === TRUE) {
        header('Location: ./view_all_client_accounts.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Client Account</title>
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <link rel="stylesheet" href="./account_creation.css">
</head>
<body>

<nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1 class="title">Ledger Legend Administrator</h1> 
        </div>

        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span> 
            <a href="./logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

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

        <button id="calculatorBtn" class="nav-link" title="Open the calculator">Calculator</button>
        <button id="calendarBtn" class="nav-link" title="Open the calendar">Calendar</button>
        <a href="./help.php" class="nav-link help-btn" title="Get help and support">&#x2753;</a>
    </div>

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

    <div id="calendarModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeCalendar">&times;</span>
            <h2>Calendar</h2>
            <div id="calendar"></div>
        </div>
    </div>
    <script src="modals_calculator_calendar.js"></script>


    <div class="main">
        <div class="container">
            <h2>Create New Client Account</h2>
            <form method="POST" action="">
                <input type="text" name="account_name" placeholder="Account Name" required>
                <input type="text" name="account_number" placeholder="Account Number" required>
                <textarea name="account_description" placeholder="Account Description"></textarea>
                <select name="normal_side" required>
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select>
                <input type="text" name="account_category" placeholder="Account Category">
                <input type="text" name="account_subcategory" placeholder="Account Subcategory">
                <input type="number" step="0.01" name="initial_balance" placeholder="Initial Balance" required>
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>"> 
                <input type="text" name="account_order" placeholder="Account Order">
                <select name="statement" required>
                    <option value="IS">Income Statement</option>
                    <option value="BS">Balance Sheet</option>
                    <option value="RE">Retained Earnings</option>
                </select><br><br>
                <textarea name="comment" placeholder="Comment"></textarea><br><br>
                <button type="submit">Create Account</button>
            </form>
        </div>
    </div>
</body>
</html>
