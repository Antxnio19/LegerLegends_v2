<?php
// Simulate that the user is a manager without requiring a login
$isManager = true; // Bypass login for testing purposes

// Database connection
$servername = "localhost";
$dbUsername = "root"; 
$dbPassword = ""; 
$dbname = "ledgerledgends";
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Filter and search parameters
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Query to fetch journal entries based on the selected filter and search
$sql = "SELECT * FROM Journal_Entries WHERE 1=1";

// Status filter
if ($statusFilter !== 'all') {
    $sql .= " AND IsApproved = '$statusFilter'";
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
    <link rel="stylesheet" href="./it_ticket_stylesheet.css">
    <title>View Journal Entries</title>
    <style>
        /* Style the Approve and Reject buttons */
        .approve-btn, .reject-btn {
            padding: 5px 10px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .approve-btn {
            background-color: green;
        }
        .reject-btn {
            background-color: red;
        }
        /* Indentation for journal entries */
        .journal-entry {
            margin-left: 20px;
        }
    </style>
</head>
<body>

<div class="main">
    <h1>All Journal Entries</h1>
    <br><br>
    <button><a href="./add_journal_entry.php">Add Journal Entry</a></button>

    <div class="filters">
        <form method="GET">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status">
                <option value="all">All</option>
                <option value="approved" <?php if ($statusFilter === 'approved') echo 'selected'; ?>>Approved</option>
                <option value="pending" <?php if ($statusFilter === 'pending') echo 'selected'; ?>>Pending</option>
                <option value="rejected" <?php if ($statusFilter === 'rejected') echo 'selected'; ?>>Rejected</option>
            </select>
            
            <label for="date_from">From:</label>
            <input type="date" name="date_from" value="<?php echo $dateFrom; ?>">
            <label for="date_to">To:</label>
            <input type="date" name="date_to" value="<?php echo $dateTo; ?>">
            
            <input type="text" name="search" placeholder="Search by account name, amount, or date" value="<?php echo $searchTerm; ?>">

            <button type="submit">Apply Filters</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Account Type</th>
                <th>Account Description</th>
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
                            <td class="journal-entry">' . htmlspecialchars($row['account_type']) . '</td>
                            <td class="journal-entry">' . htmlspecialchars($row['account_description']) . '</td>
                            <td>' . htmlspecialchars($row['debit']) . '</td>
                            <td>' . htmlspecialchars($row['credit']) . '</td>
                            <td>' . htmlspecialchars($row['created_at']) . '</td>
                            <td>' . htmlspecialchars($row['ModifiedBy']) . '</td>
                            <td>' . htmlspecialchars($row['IsApproved']) . '</td>
                            <td>' . htmlspecialchars($row['comment']) . '</td>';

                    // Show action buttons for managers (approve/reject with comment)
                    if ($isManager && $row['IsApproved'] == 'pending') {
                        echo '<td>
                                <form method="POST" action="approve_reject_entry.php">
                                    <input type="hidden" name="entry_id" value="' . $row['id'] . '">
                                    <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                                    <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                                    <input type="text" name="comment" placeholder="Reason for rejection">
                                </form>
                              </td>';
                    } else {
                        echo '<td>N/A</td>';
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
