<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "root";
$dbname = "accounting_db";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$client_account_id = isset($_GET['account_id']) ? intval($_GET['account_id']) : 0;
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$accountName = isset($_GET['account_name']) ? $_GET['account_name'] : '';
$debitAmount = isset($_GET['debit_amount']) ? floatval($_GET['debit_amount']) : '';
$creditAmount = isset($_GET['credit_amount']) ? floatval($_GET['credit_amount']) : '';

$sql = "SELECT lt.reference_number, lt.transaction_date, lt.description, lt.debit, lt.credit, ca.account_name
        FROM Ledger_Transactions AS lt
        JOIN Journal_Entries AS je ON lt.journal_entry_id = je.id
        JOIN Client_Accounts AS ca ON lt.client_account_id = ca.id
        WHERE lt.client_account_id = ? AND je.is_approved = 'Approved'";

if (!empty($startDate)) {
    $sql .= " AND lt.transaction_date >= ?";
}
if (!empty($endDate)) {
    $sql .= " AND lt.transaction_date <= ?";
}
if (!empty($accountName)) {
    $sql .= " AND ca.account_name LIKE ?";
}
if (!empty($debitAmount)) {
    $sql .= " AND lt.debit = ?";
}
if (!empty($creditAmount)) {
    $sql .= " AND lt.credit = ?";
}

$sql .= " ORDER BY lt.transaction_date ASC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$paramTypes = "i";
$params = [$client_account_id];

if (!empty($startDate)) {
    $paramTypes .= "s";
    $params[] = $startDate;
}
if (!empty($endDate)) {
    $paramTypes .= "s";
    $params[] = $endDate;
}
if (!empty($accountName)) {
    $paramTypes .= "s";
    $params[] = '%' . $accountName . '%';
}
if (!empty($debitAmount)) {
    $paramTypes .= "d";
    $params[] = $debitAmount;
}
if (!empty($creditAmount)) {
    $paramTypes .= "d";
    $params[] = $creditAmount;
}

$stmt->bind_param($paramTypes, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$runningBalance = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css">
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <title>Ledger for Account ID: <?php echo $accountId; ?></title>
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
    <h1>Ledger Transactions for Account ID: <?php echo htmlspecialchars($client_account_id); ?></h1>
    <div class="filter-form">
        <form method="get" action="">
            <input type="hidden" name="account_id" value="<?php echo $client_account_id; ?>">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
            <label for="account_name">Account Name:</label>
            <input type="text" name="account_name" value="<?php echo htmlspecialchars($accountName); ?>">
            <label for="debit_amount">Debit Amount:</label>
            <input type="number" name="debit_amount" step="0.01" value="<?php echo htmlspecialchars($debitAmount); ?>">
            <label for="credit_amount">Credit Amount:</label>
            <input type="number" name="credit_amount" step="0.01" value="<?php echo htmlspecialchars($creditAmount); ?>">
            <button type="submit">Filter</button>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Date</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $debit = floatval($row['debit']);
                $credit = floatval($row['credit']);
                
                $runningBalance += $debit - $credit;

                echo "<tr>
                        <td><a href='Post_Refrence_Manager.php?reference_number=" . urlencode($row['reference_number']) . "&account_id=" . $client_account_id . "'>" . htmlspecialchars($row['reference_number']) . "</a></td>
                        <td>" . htmlspecialchars($row['transaction_date']) . "</td>
                        <td>" . htmlspecialchars($row['description']) . "</td>
                        <td>" . number_format($debit, 2) . "</td>
                        <td>" . number_format($credit, 2) . "</td>
                        <td>" . number_format($runningBalance, 2) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No transactions found for this account.</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <br/>
    <div class="dropdown">
            <a href="view_all_client_accounts.php?account_id=<?php echo $client_account_id; ?>" class="nav-link">Go to Chart of Accounts</a>
            <a href="View_some_accounts.php?account_id=<?php echo $client_account_id; ?>" class="nav-link">Go to Accounts</a>
        </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>