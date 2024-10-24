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

// Get the reference_number and client_account_id from the URL
$reference_number = isset($_GET['reference_number']) ? $_GET['reference_number'] : '';
$client_account_id = isset($_GET['account_id']) ? intval($_GET['account_id']) : 0;

// Query to fetch journal entries for the given reference number
$sql = "SELECT account_type, debit, credit, comment, created_at, ModifiedBy, IsApproved 
        FROM Journal_Entries 
        WHERE client_account_id = ? AND id = (
            SELECT id FROM Ledger_Transactions WHERE reference_number = ?
        )";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("is", $client_account_id, $reference_number);
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
    <link rel="stylesheet" href="./Post_Refrence.css">
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
    <h2 class="page-title">Post Refrence Details</h2>

    <div class="journal-entry-container">
        <?php
        if ($result->num_rows > 0) {
            // Display the journal entry details in a table-like format
            while ($row = $result->fetch_assoc()) {
                echo "<div class='entry-details'>";
                echo "<div class='entry-row'><strong>Account Type:</strong> <span>" . htmlspecialchars($row['account_type']) . "</span></div>";
                echo "<div class='entry-row'><strong>Debit:</strong> <span>" . number_format($row['debit'], 2) . "</span></div>";
                echo "<div class='entry-row'><strong>Credit:</strong> <span>" . number_format($row['credit'], 2) . "</span></div>";
                echo "<div class='entry-row'><strong>Comment:</strong> <span>" . htmlspecialchars($row['comment']) . "</span></div>";
                echo "<div class='entry-row'><strong>Date Created:</strong> <span>" . htmlspecialchars($row['created_at']) . "</span></div>";
                echo "<div class='entry-row'><strong>Modified By:</strong> <span>" . htmlspecialchars($row['ModifiedBy']) . "</span></div>";
                echo "<div class='entry-row'><strong>Is Approved:</strong> <span>" . ($row['IsApproved'] ? 'Yes' : 'No') . "</span></div>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-entry-message'>No journal entries found for this reference number.</p>";
        }
        ?>
        <div class="back-to-ledger">
            <a href="Ledger_Accountant.php?account_id=<?php echo $client_account_id; ?>" class="back-link">Back to Ledger</a>
        </div>
    </div>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
