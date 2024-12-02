<?php
session_start();

$isManager = 0;

if (isset($_SESSION['username'], $_SESSION['Id'], $_SESSION['UserTypeId'])) {
    $username = $_SESSION['username'];
    $userId = $_SESSION['Id'];
    $UserTypeId = $_SESSION['UserTypeId'];

    if ($UserTypeId === "Manager") {
        $isManager = 1;
    }
} else {
    echo "User session not found.";
    exit;
}

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "root";
$dbname = "accounting_db";
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['entry_id'])) {
    $entryId = intval($_POST['entry_id']);
    $action = $_POST['action'];
    $comment = $_POST['comment'] ?? '';

    $status = ($action === 'approve') ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE Journal_Entries SET is_approved = ?, comment = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $comment, $entryId);
    $stmt->execute();
    $stmt->close();
}

$statusFilter = $_GET['status'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$searchTerm = $_GET['search'] ?? '';

$sql = "
    SELECT 
        je.id AS journal_id,
        je.entry_date,
        je.created_by,
        je.entry_type,  /* Add this line */
        jel.account,
        jel.debit,
        jel.credit,
        je.is_approved,
        je.comment
    FROM 
        Journal_Entries je
    LEFT JOIN 
        Journal_Entry_Lines jel ON je.id = jel.journal_entry_id
    WHERE 
        1=1";

if ($statusFilter !== '-1' && $statusFilter !== '') {
    $sql .= " AND je.is_approved = ?";
}

if (!empty($dateFrom) && !empty($dateTo)) {
    $sql .= " AND je.entry_date BETWEEN ? AND ?";
}

if (!empty($searchTerm)) {
    $sql .= " AND je.id IN (
        SELECT journal_entry_id 
        FROM Journal_Entry_Lines 
        WHERE account LIKE ? 
        OR journal_entry_id IN (SELECT id FROM Journal_Entries WHERE comment LIKE ?)
    )";
}

$sql .= " ORDER BY je.entry_date DESC";

$stmt = $conn->prepare($sql);

$bindParams = [];
if ($statusFilter !== '-1' && $statusFilter !== '') {
    $bindParams[] = &$statusFilter;
}
if (!empty($dateFrom) && !empty($dateTo)) {
    $bindParams[] = &$dateFrom;
    $bindParams[] = &$dateTo;
}
if (!empty($searchTerm)) {
    $searchTerm = "%$searchTerm%";
    $bindParams[] = &$searchTerm;
    $bindParams[] = &$searchTerm;
}

if ($bindParams) {
    $stmt->bind_param(str_repeat('s', count($bindParams)), ...$bindParams);
}

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

        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span> 
            <a href="./logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

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

        <button id="calculatorBtn" class="nav-link" title="Open the calculator">Calculator</button>
        <button id="calendarBtn" class="nav-link" title="Open the calendar">Calendar</button>
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
    <h1 style="color: white;">All Journal Entries</h1>
    <br><br>
    <div class="dropdown">
            <a href="add_journal_entry_accountant.php?account_id=<?php echo $client_account_id; ?>" class="nav-link">Add Journal Entry</a>
        </div>
        <br><br>
    <div class="filter-container">

    <form method="GET">
    <label for="status">Filter by Status:</label>
    <select name="status" id="status">
        <option value="-1" <?php if ($statusFilter === '-1') echo 'selected'; ?>>All</option>
        <option value="Approved" <?php if ($statusFilter === 'Approved') echo 'selected'; ?>>Approved</option>
        <option value="Pending" <?php if ($statusFilter === 'Pending') echo 'selected'; ?>>Pending</option>
        <option value="Rejected" <?php if ($statusFilter === 'Rejected') echo 'selected'; ?>>Rejected</option>
    </select>
    
    <label for="date_from">From:</label>
    <input type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>">
    <label for="date_to">To:</label>
    <input type="date" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>">
    
    <input type="text" name="search" placeholder="Search by account name or comment" value="<?php echo htmlspecialchars($searchTerm); ?>">

    <button type="submit">Apply Filters</button>
</form>
</div>
    <div class="main">

        <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Created By</th>
                <th>Entry Type</th>
                <th>Account</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Status</th>
                <th>Comment</th>
                <?php if ($isManager) { echo "<th>Action</th>"; } ?>
            </tr>
        </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $currentJournalId = null;
                    while($row = $result->fetch_assoc()) {
                        if ($currentJournalId !== $row['journal_id']) {
                            if ($currentJournalId !== null) {
                                echo '</tr>';
                            }
                            echo '<tr>';
                            echo '<td>' . $row['journal_id'] . '</td>';
                            echo '<td>' . date('m/d/Y', strtotime($row['entry_date'])) . '</td>';
                            echo '<td>' . htmlspecialchars($row['created_by']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['entry_type']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['account']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['debit']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['credit']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['is_approved']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['comment']) . '</td>';
                            $currentJournalId = $row['journal_id'];

                            if ($isManager) {
                                echo '<td>
                                        <form method="POST" action="approve_reject_entry.php">
                                            <input type="hidden" name="entry_id" value="' . $row['journal_id'] . '">
                                            <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                                            <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                                            <input type="text" name="comment" placeholder="Reason for rejection">
                                        </form>
                                      </td>';
                            }
                        } else {
                            echo '<tr>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td>' . htmlspecialchars($row['account']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['debit']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['credit']) . '</td>';
                            echo '<td></td>';
                            echo '<td></td>';
                        }
                    }
                    echo '</tr>';
                } else {
                    echo '<tr><td colspan="9">No journal entries found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <style>
.approve-btn {
    background-color: green;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.reject-btn {
    background-color: red;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}
    </style>
</body>
</html>

<?php
$conn->close();
?>
