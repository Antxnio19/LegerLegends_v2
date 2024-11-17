<?php
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

// Query to get total revenue by account description
$queryRevenue = "SELECT account AS account_description, SUM(credit) AS total_revenue 
                 FROM Journal_Entry_Lines 
                 WHERE account_type = 'Revenue' 
                 GROUP BY account";

// Query to get total expenses by account description
$queryExpenses = "SELECT account AS account_description, SUM(debit) AS total_expenses 
                  FROM Journal_Entry_Lines 
                  WHERE account_type = 'Expense' 
                  GROUP BY account";

$revenueResult = $conn->query($queryRevenue);
$expensesResult = $conn->query($queryExpenses);

// Calculate total revenue and total expenses
$totalRevenue = 0;
$totalExpenses = 0;

if ($revenueResult->num_rows > 0) {
    while ($row = $revenueResult->fetch_assoc()) {
        $totalRevenue += $row['total_revenue'];
    }
}

if ($expensesResult->num_rows > 0) {
    while ($row = $expensesResult->fetch_assoc()) {
        $totalExpenses += $row['total_expenses'];
    }
}

// Calculate net income
$netIncome = $totalRevenue - $totalExpenses;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Statement</title>
    <link rel="stylesheet" href="stylesheet.css"> <!-- Add your stylesheet if needed -->
</head>
<body>

<div class="main">
    <h1>Income Statement</h1>

    <h2>Total Revenue</h2>
    <table>
        <thead>
            <tr>
                <th>Account Description</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $revenueResult->data_seek(0); // Reset pointer to re-loop through results
            $hasRevenue = false; // Track if we have any revenue data
            if ($revenueResult->num_rows > 0) {
                while ($row = $revenueResult->fetch_assoc()) {
                    if ($row['total_revenue'] > 0) {
                        $hasRevenue = true;
                        echo '<tr>
                                <td>' . htmlspecialchars($row['account_description']) . '</td>
                                <td>' . htmlspecialchars($row['total_revenue']) . '</td>
                              </tr>';
                    }
                }
                if ($hasRevenue && $totalRevenue > 0) {
                    // Display total revenue only if greater than 0
                    echo '<tr>
                            <td><u>Total Revenue</u></td>
                            <td><u>' . htmlspecialchars($totalRevenue) . '</u></td>
                          </tr>';
                }
            } else {
                echo '<tr><td colspan="2">No revenue entries found</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <h2>Total Expenses</h2>
    <table>
        <thead>
            <tr>
                <th>Account Description</th>
                <th>Total Expenses</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $expensesResult->data_seek(0); // Reset pointer to re-loop through results
            $hasExpenses = false; // Track if we have any expense data
            if ($expensesResult->num_rows > 0) {
                while ($row = $expensesResult->fetch_assoc()) {
                    if ($row['total_expenses'] > 0) {
                        $hasExpenses = true;
                        echo '<tr>
                                <td>' . htmlspecialchars($row['account_description']) . '</td>
                                <td>' . htmlspecialchars($row['total_expenses']) . '</td>
                              </tr>';
                    }
                }
                if ($hasExpenses && $totalExpenses > 0) {
                    // Display total expenses only if greater than 0
                    echo '<tr>
                            <td><u>Total Expenses</u></td>
                            <td><u>' . htmlspecialchars($totalExpenses) . '</u></td>
                          </tr>';
                }
            } else {
                echo '<tr><td colspan="2">No expense entries found</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <?php if ($netIncome > 0): ?>
    <h2>Net Income</h2>
    <table>
        <thead>
            <tr>
                <th>Net Income</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><u>Net Income</u></td>
                <td><u><?php echo htmlspecialchars($netIncome); ?></u></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
