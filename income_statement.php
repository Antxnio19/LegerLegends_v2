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
$queryRevenue = "SELECT account_description, SUM(debit) AS total_revenue 
                 FROM Journal_Entries 
                 WHERE account_type = 'revenue' 
                 GROUP BY account_description";

// Query to get total expenses by account description
$queryExpenses = "SELECT account_description, SUM(debit) AS total_expenses 
                  FROM Journal_Entries 
                  WHERE account_type = 'expense' 
                  GROUP BY account_description";

$revenueResult = $conn->query($queryRevenue);
$expensesResult = $conn->query($queryExpenses);
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
            if ($revenueResult->num_rows > 0) {
                while ($row = $revenueResult->fetch_assoc()) {
                    echo '<tr>
                            <td>' . htmlspecialchars($row['account_description']) . '</td>
                            <td>' . htmlspecialchars($row['total_revenue']) . '</td>
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
            if ($expensesResult->num_rows > 0) {
                while ($row = $expensesResult->fetch_assoc()) {
                    echo '<tr>
                            <td>' . htmlspecialchars($row['account_description']) . '</td>
                            <td>' . htmlspecialchars($row['total_expenses']) . '</td>
                          </tr>';
                }
            } else {
                echo '<tr><td colspan="2">No expense entries found</td></tr>';
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


