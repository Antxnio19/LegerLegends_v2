<?php
session_start();

$username = $_SESSION['username'];
$userId = $_SESSION['Id'];

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "root";
$dbname = "accounting_db";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$totalCurrentAssets = 0;
$totalNonCurrentAssets = 0;
$totalAssets = 0;

$totalLiabilities = 0;
$totalEquity = 0;
$totalLiabilitiesAndEquity = 0;

$assetsQuery = "
    SELECT 
        account_name,
        balance,
        LOWER(account_subcategory) AS subcategory
    FROM 
        Client_Accounts
    WHERE 
        account_category = 'Asset' AND IsActive = 1
    ORDER BY account_order
";
$assetsResult = $conn->query($assetsQuery);

$liabilitiesQuery = "
    SELECT 
        account_name,
        balance
    FROM 
        Client_Accounts
    WHERE 
        account_category = 'Liability' AND IsActive = 1
    ORDER BY account_order
";
$liabilitiesResult = $conn->query($liabilitiesQuery);

$equityQuery = "
    SELECT 
        account_name,
        balance
    FROM 
        Client_Accounts
    WHERE 
        account_category = 'Equity' AND IsActive = 1
    ORDER BY account_order
";
$equityResult = $conn->query($equityQuery);

function formatBalance($balance) {
    return $balance < 0 ? '($' . number_format(abs($balance), 2) . ')' : '$' . number_format($balance, 2);
}

function adjustTotal($total, $balance) {
    return $total + $balance;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="BalanceSheet.css">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
	<link rel="stylesheet" href="stylesA.css">
    <link rel="icon" type="image/png" href="profile.png">
    <title>Ledger Legend Administrator Page</title>
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

    <style>
        .centered-info {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }
        .table-values {
            width: 100%;
            border-collapse: collapse;
        }
        .table-values th, .table-values td {
            text-align: center; 
            padding: 10px;
        }
        .table-values td.numeric {
            text-align: right; 
            font-family: monospace; 
        }
        .underline {
            text-decoration: underline;
        }
        .double-underline {
            text-decoration: underline;
            text-decoration-style: double;
        }
        .actions {
            text-align: center;
            margin-bottom: 20px;
        }
        .actions button {
            margin: 5px;
            padding: 10px 15px;
            font-size: 14px;
        }
    </style>

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
                <a href="./Trial_balance_accountant.php" title="Generates Trial Balance">Trial Balance</a>
                <a href="./client_income_accountant.php" title="Generates Client Income Statement">Income Statement</a>
                <a href="./BalanceSheet_accountant.php" title="Generates Balance sheet">Balance sheet</a>
                <a href="./client_retained_earnings_accountant.php" title="Generates Retained Earnings">Retained Earnings</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Adjust system settings">Settings</button>
            <div class="dropdown-content">
                <a href="#" title="Configure system settings">System Settings</a>
            </div>
        </div>

        <button id="calculatorBtn" class="nav-link" title="Open the calculator">
            <img src="calc.png" alt="Calculator Icon" width="24" height="24">
        </button>
        <button id="calendarBtn" class="nav-link" title="Open the calendar">
            <img src="calendar.png" alt="Calendar Icon" width="24" height="24">
        </button>
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

	<div class="container">
        <div class="centered-info">
            <h1>Addams & Fammily Inc.</h1>
            <h2>Balance Sheet</h2>
            <h3>As of <?php echo date('F d, Y'); ?></h3>
        </div>

        <div class="actions">
        <button onclick="downloadScreen()">Download</button>
        <button onclick="emailScreen()">Email</button>
        <button onclick="printScreen()">Print</button>
        </div>

        <table class="table-values">
            <tr>
                <th colspan="2">Assets</th>
            </tr>
            <tr>
                <td><strong>Current Assets</strong></td>
                <td></td>
            </tr>
            <?php while ($row = $assetsResult->fetch_assoc()) : ?>
                <?php if ($row['subcategory'] === 'current asset') : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                        <td class="numeric"><?php echo formatBalance($row['balance']); ?></td>
                    </tr>
                    <?php $totalCurrentAssets = adjustTotal($totalCurrentAssets, $row['balance']); ?>
                <?php endif; ?>
            <?php endwhile; ?>
            <tr>
                <td><strong>Total Current Assets</strong></td>
                <td class="numeric underline"><?php echo formatBalance($totalCurrentAssets); ?></td>
            </tr>
            <tr>
                <td><strong>Non-Current Assets</strong></td>
                <td></td>
            </tr>
            <?php foreach ($assetsResult as $row) : ?>
                <?php if ($row['subcategory'] === 'non-current assets') : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                        <td class="numeric"><?php echo formatBalance($row['balance']); ?></td>
                    </tr>
                    <?php $totalNonCurrentAssets = adjustTotal($totalNonCurrentAssets, $row['balance']); ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <tr>
                <td><strong>Total Non-Current Assets</strong></td>
                <td class="numeric underline"><?php echo formatBalance($totalNonCurrentAssets); ?></td>
            </tr>
            <tr>
                <td><strong>Total Assets</strong></td>
                <td class="numeric double-underline"><?php echo formatBalance($totalCurrentAssets + $totalNonCurrentAssets); ?></td>
            </tr>

            <tr>
                <th colspan="2">Liabilities</th>
            </tr>
            <?php while ($row = $liabilitiesResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                    <td class="numeric"><?php echo formatBalance($row['balance']); ?></td>
                </tr>
                <?php $totalLiabilities = adjustTotal($totalLiabilities, $row['balance']); ?>
            <?php endwhile; ?>
            <tr>
                <td><strong>Total Liabilities</strong></td>
                <td class="numeric underline"><?php echo formatBalance($totalLiabilities); ?></td>
            </tr>

            <tr>
                <th colspan="2">Equity</th>
            </tr>
            <?php while ($row = $equityResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                    <td class="numeric"><?php echo formatBalance($row['balance']); ?></td>
                </tr>
                <?php $totalEquity = adjustTotal($totalEquity, $row['balance']); ?>
            <?php endwhile; ?>
            <tr>
                <td><strong>Total Equity</strong></td>
                <td class="numeric underline"><?php echo formatBalance($totalEquity); ?></td>
            </tr>
            <tr>
                <td><strong>Total Liabilities & Equity</strong></td>
                <td class="numeric double-underline"><?php echo formatBalance($totalLiabilities + $totalEquity); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
