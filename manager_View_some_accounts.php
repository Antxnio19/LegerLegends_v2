<?php
session_start(); // Start the session

// Store the username and user ID from the session
$username = $_SESSION['username'];
$userId = $_SESSION['Id'];

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

// Initialize filter variables
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$accountName = isset($_GET['account_name']) ? $_GET['account_name'] : '';
$accountNumber = isset($_GET['account_number']) ? $_GET['account_number'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';
$minBalance = isset($_GET['min_balance']) ? $_GET['min_balance'] : '';
$maxBalance = isset($_GET['max_balance']) ? $_GET['max_balance'] : '';

// Build the query with dynamic filtering and search functionality
$sql = "SELECT * FROM Client_Accounts WHERE 1=1";

if (!empty($searchQuery)) {
    $sql .= " AND (account_name LIKE '%" . $conn->real_escape_string($searchQuery) . "%' OR account_number LIKE '%" . $conn->real_escape_string($searchQuery) . "%')";
}
if (!empty($accountName)) {
    $sql .= " AND account_name LIKE '%" . $conn->real_escape_string($accountName) . "%'";
}
if (!empty($accountNumber)) {
    $sql .= " AND account_number LIKE '%" . $conn->real_escape_string($accountNumber) . "%'";
}
if (!empty($category)) {
    $sql .= " AND account_category = '" . $conn->real_escape_string($category) . "'";
}
if (!empty($subcategory)) {
    $sql .= " AND account_subcategory = '" . $conn->real_escape_string($subcategory) . "'";
}
if (!empty($minBalance)) {
    $sql .= " AND balance >= " . floatval($minBalance);
}
if (!empty($maxBalance)) {
    $sql .= " AND balance <= " . floatval($maxBalance);
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css">
    <link rel="stylesheet" href="./filter_search_styles.css">
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <title>Accounts</title>
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1 class="title">Ledger Legend Manager</h1> 
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
        <a href="./manager_home.php" class="nav-link" title="Takes you to the home page">Home</a>
        <a href="./manager_home.php" class="nav-link" title="Submit an IT ticket">IT Ticket</a>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage client accounts">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./manager_view_all_client_accounts.php" title="View all client accounts">Chart of Accounts</a>
                <a href="./manager_View_some_accounts.php" title="View specific client accounts">Accounts</a>
                <a href="./add_journal_entry.php" title="Create a new journal entry">Add Journal Entries</a>
                <a href="./view_all_journal_entries.php" title="Create a new journal entry">View Jounral Entries</a>
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
    <h1 style="color: white;">Accounts</h1>

    <!-- Search Bar -->
    <div class="search-bar-container">
        <form method="GET" action="View_some_accounts.php">
            <input type="text" name="search" class="search-bar" placeholder="Search by Account Name or Number" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="search-button">Search</button>
            <button type="reset" class="reset-button" onclick="window.location.href='View_some_accounts.php'">Reset</button>
        </form>
    </div>

    <!-- Filter Form -->
    <div class="filter-container">
        <h2>Filters</h2>
        <form method="GET" action="View_some_accounts.php">
            <div class="filter-row">
                <input type="text" name="account_name" placeholder="Account Name" value="<?php echo htmlspecialchars($accountName); ?>">
                <input type="text" name="account_number" placeholder="Account Number" value="<?php echo htmlspecialchars($accountNumber); ?>">
            </div>
            <div class="filter-row">
                <input type="text" name="category" placeholder="Category" value="<?php echo htmlspecialchars($category); ?>">
                <input type="text" name="subcategory" placeholder="Subcategory" value="<?php echo htmlspecialchars($subcategory); ?>">
            </div>
            <div class="filter-row">
                <input type="number" step="0.01" name="min_balance" placeholder="Min Balance" value="<?php echo htmlspecialchars($minBalance); ?>">
                <input type="number" step="0.01" name="max_balance" placeholder="Max Balance" value="<?php echo htmlspecialchars($maxBalance); ?>">
            </div>
            <button type="submit">Filter</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
            <th>ID</th>
                <th>Account Name</th>
                <th>Account Number</th>
                <th>Description</th>
                <th>Normal Side</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Initial Balance</th>
                <th>Balance</th>
                <th>User ID</th>
                <th>Order</th>
                <th>Statement</th>
                <th>Comment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['id'] . '</td>
                    <td>
                        <a href="Ledger_Manger.php?account_id=' . $row['id'] . '" class="clickable-link">' . 
                            htmlspecialchars($row['account_name']) . 
                        '</a>
                    </td>
                    <td>
                        <a href="Ledger_Manger.php?account_id=' . $row['id'] . '" class="clickable-link">' . 
                            htmlspecialchars($row['account_number']) . 
                        '</a>
                    </td>
                    <td>' . htmlspecialchars($row['account_description']) . '</td>
                    <td>' . htmlspecialchars($row['normal_side']) . '</td>
                    <td>' . htmlspecialchars($row['account_category']) . '</td>
                    <td>' . htmlspecialchars($row['account_subcategory']) . '</td>
                    <td>' . number_format($row['initial_balance'], 2) . '</td>
                    <td>' . number_format($row['balance'], 2) . '</td>
                    <td>' . $row['user_id'] . '</td>
                    <td>' . htmlspecialchars($row['account_order']) . '</td>
                    <td>' . htmlspecialchars($row['statement']) . '</td>
                    <td>' . htmlspecialchars($row['comment']) . '</td>
                    <td>
                        <button class="update-button" onclick="window.location.href=\'edit_client_account.php?id=' . $row['id'] . '\'">Edit</button><br><br>
                        <button class="view-button" onclick="window.location.href=\'Ledger_Manger.php?account_id=' . $row['id'] . '\'">Ledger</button>
                    </td>
                </tr>';
        }
    } else {
        echo '<tr><td colspan="13">No accounts found</td></tr>';
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