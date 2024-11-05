<?php
session_start(); // Start the session

// Initialize isManager to 0 (false)
$isManager = 0;

// Check if session variables are set before using them
if (isset($_SESSION['username'], $_SESSION['Id'], $_SESSION['UserTypeId'])) {
    // Store the username, user ID, and user type from the session
    $username = $_SESSION['username'];
    $userId = $_SESSION['Id'];
    $UserTypeId = $_SESSION['UserTypeId'];

    // Check if UserTypeId is "Manager" and set isManager accordingly
    if ($UserTypeId === "Manager") {
        $isManager = 1; // or true;
    }
} else {
    // Handle the case where session variables are not set
    echo "User session not found.";
    exit; // Stop script execution if session data is missing
}

// Database connection
$servername = "localhost";
$dbUsername = "root"; // Replace with your database username
$dbPassword = "root"; // Replace with your database password
$dbname = "accounting_db"; // Replace with your database name

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Filter and search parameters
$statusFilter = isset($_GET['status']) ? intval($_GET['status']) : -1; // -1 for "all"
$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Query to fetch journal entries based on the selected filter and search
$sql = "SELECT * FROM Journal_Entries WHERE 1=1";

// Status filter
if ($statusFilter >= 0) { // Only filter if the status is set to a specific value (0, 1, or 2)
    $sql .= " AND IsApproved = $statusFilter"; // Directly use the integer value
}

// Date filter
if (!empty($dateFrom) && !empty($dateTo)) {
    $sql .= " AND created_at BETWEEN '$dateFrom' AND '$dateTo'";
}

// Search by account name, amount, or date
if (!empty($searchTerm)) {
    $sql .= " AND (account_type LIKE '%$searchTerm%' OR debit LIKE '%$searchTerm%' OR credit LIKE '%$searchTerm%' OR created_at LIKE '%$searchTerm%')";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <link rel="stylesheet" href="./filter_search_styles.css">
    <link rel="icon" type="image/png" href="profile.png">
    <title>Ledger Legend Accountant Page</title>
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1 style="color: white;" class="title">Ledger Legend Accountant</h1> 
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
    <h1 style="color: white;">All Journal Entries</h1>
    <br><br>
    <div class="dropdown">
            <a href="add_journal_entry.php?account_id=<?php echo $client_account_id; ?>" class="nav-link">Add Journal Entry</a>
        </div>
        <br><br>
    <div class="filter-container">
    <!-- Filter buttons for status -->
    <form method="GET">
        <label for="status">Filter by Status:</label>
        <select name="status" id="status">
            <option value="-1">All</option>
            <option value="1">Approved</option>
            <option value="0">Pending</option>
            <option value="2">Rejected</option>
        </select>
        
        <!-- Date filters -->
        <label for="date_from">From:</label>
        <input type="date" name="date_from" value="<?php echo $dateFrom; ?>">
        <label for="date_to">To:</label>
        <input type="date" name="date_to" value="<?php echo $dateTo; ?>">
        
        <!-- Search -->
        <input type="text" name="search" placeholder="Search by account name, amount, or date" value="<?php echo $searchTerm; ?>">

        <button type="submit">Apply Filters</button>
    </form>
</div>


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Account Type</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Created On</th>
                <th>Created By</th>
                <th>Status</th>
                <th>Comment</th>
                <?php if ($isManager) { echo "<th>Action</th>"; } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<tr>
                            <td>' . $row['id'] . '</td>
                            <td>' . htmlspecialchars($row['account_type']) . '</td>
                            <td>' . htmlspecialchars($row['debit']) . '</td>
                            <td>' . htmlspecialchars($row['credit']) . '</td>
                            <td>' . htmlspecialchars($row['created_at']) . '</td>
                            <td>' . htmlspecialchars($row['ModifiedBy']) . '</td>
                            <td>' . htmlspecialchars($row['IsApproved']) . '</td>
                            <td>' . htmlspecialchars($row['comment']) . '</td>';

                    // Show action buttons for managers
                    if ($isManager == 1) {
                        echo '<td>
                                <form method="POST" action="approve_reject_entry.php">
                                    <input type="hidden" name="entry_id" value="' . $row['id'] . '">
                                    <button type="submit" name="action" value="approve">Approve</button>
                                    <button type="submit" name="action" value="reject">Reject</button>
                                    <input type="text" name="comment" placeholder="Reason for rejection">
                                </form>
                              </td>';
                    }
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="10">No journal entries found</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$conn->close();
?>