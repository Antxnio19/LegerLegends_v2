<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "root";
$dbname = "accounting_db";  // Update with your database name

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the client_account_id from the URL
$client_account_id = isset($_GET['account_id']) ? intval($_GET['account_id']) : 0;

// Query to fetch transactions with reference number
$sql = "SELECT reference_number, transaction_date, description, debit, credit, balance_after 
        FROM Ledger_Transactions 
        WHERE client_account_id = ? 
        ORDER BY transaction_date ASC";

// Prepare the query and check for errors
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $client_account_id);
$stmt->execute();
$result = $stmt->get_result();
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
        <h1 class="title">Ledger Legend Accountant</h1>
    </div>
    <div class="user-profile">
        <img src="pfp.png" alt="User Picture" class="profile-pic">
        <span class="username"><?php echo htmlspecialchars($username); ?></span>
        <a href="./logout.php" class="logout-btn">Logout</a>
    </div>
</nav>
<!-- Navigation Bar -->
<div class="main-bar">
        <a href="./Accountant_home.php" class="nav-link" title="Takes you to the home page">Home</a>
        <a href="./iAccountant_home.php" class="nav-link" title="Submit an IT ticket">IT Ticket</a>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage client accounts">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./accountant_view_all_client_accounts.php" title="View all client accounts">Chart of Accounts</a>
                <a href="./accountant_View_some_accounts.php" title="View specific client accounts">Accounts</a>
                <a href="./add_journal_entry_accountant.php" title="Create a new journal entry">Add Journal Entries</a>
                <a href="./view_all_journal_entries_accountant.php" title="Create a new journal entry">View Jounral Entries</a>
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

<div class="main">
<h1 style="color: white;">Ledger Transactions for Account ID: <?php echo $accountId; ?></h1>
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
                echo "<tr>
                        <td><a href='Post_Refrence_Accountant.php?reference_number=" . urlencode($row['reference_number']) . "&account_id=" . $client_account_id . "'>" . htmlspecialchars($row['reference_number']) . "</a></td>
                        <td>" . htmlspecialchars($row['transaction_date']) . "</td>
                        <td>" . htmlspecialchars($row['description']) . "</td>
                        <td>" . number_format($row['debit'], 2) . "</td>
                        <td>" . number_format($row['credit'], 2) . "</td>
                        <td>" . number_format($row['balance_after'], 2) . "</td>
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
            <a href="accountant_view_all_client_accounts.php?account_id=<?php echo $client_account_id; ?>" class="nav-link">Go to Chart of Accounts</a>
            <a href="accountant_View_some_accounts.php?account_id=<?php echo $client_account_id; ?>" class="nav-link">Go to Accounts</a>
        </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>