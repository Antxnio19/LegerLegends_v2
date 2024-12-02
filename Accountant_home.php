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

$sql = "
    SELECT 
        SUM(CASE WHEN account_category = 'asset' THEN balance END) AS total_assets,
        SUM(CASE WHEN account_category = 'liability' THEN balance END) AS total_liabilities,
        SUM(CASE WHEN account_category = 'equity' THEN balance END) AS total_equity,
        SUM(CASE WHEN account_category = 'Revenue' THEN balance END) AS total_revenue,
        SUM(CASE WHEN account_category = 'Expense' THEN balance END) AS total_expenses,
        SUM(CASE WHEN account_subcategory = 'current asset' THEN balance END) AS total_current_assets,
        SUM(CASE WHEN account_category = 'inventory' THEN balance END) AS total_inventory
    FROM Client_Accounts;
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    
    /*
    echo "<pre>";
    echo "Total Assets: " . $data['total_assets'] . "\n";
    echo "Total Liabilities: " . $data['total_liabilities'] . "\n";
    echo "Total Equity: " . $data['total_equity'] . "\n";
    echo "Total Revenue: " . $data['total_revenue'] . "\n";
    echo "Total Expenses: " . $data['total_expenses'] . "\n";
    echo "Total Current Assets: " . $data['total_current_assets'] . "\n";
    echo "Total Inventory: " . $data['total_inventory'] . "\n";
    echo "</pre>";
    */

    $totalAssets = $data['total_assets'] ?? 0;
    $totalLiabilities = $data['total_liabilities'] ?? 0;
    $totalEquity = $data['total_equity'] ?? 0;
    $totalRevenue = $data['total_revenue'] ?? 0;
    $totalExpenses = $data['total_expenses'] ?? 0;
    $totalCurrentAssets = $data['total_current_assets'] ?? 0;
    $totalInventory = $data['total_inventory'] ?? 0;

    // Current Ratio
    $currentRatio = ($totalLiabilities != 0) ? $totalCurrentAssets / $totalLiabilities : 'N/A';
    //echo "Current Ratio Calculation: ($totalCurrentAssets / $totalLiabilities) = $currentRatio\n";

    // Return on Assets
    $returnOnAssets = ($totalAssets != 0) ? ($totalRevenue - $totalExpenses) / $totalAssets : 'N/A';
    //echo "Return on Assets Calculation: (($totalRevenue - $totalExpenses) / $totalAssets) = $returnOnAssets\n";

    // Return on Equity
    $returnOnEquity = ($totalEquity != 0) ? ($totalRevenue - $totalExpenses) / $totalEquity : 'N/A';
    //echo "Return on Equity Calculation: (($totalRevenue - $totalExpenses) / $totalEquity) = $returnOnEquity\n";

    // Net Profit Margin
    $netProfitMargin = ($totalRevenue != 0) ? ($totalRevenue - $totalExpenses) / $totalRevenue : 'N/A';
    //echo "Net Profit Margin Calculation: (($totalRevenue - $totalExpenses) / $totalRevenue) = $netProfitMargin\n";

    // Asset Turnover
    $assetTurnover = ($totalAssets != 0) ? $totalRevenue / $totalAssets : 'N/A';
    //echo "Asset Turnover Calculation: ($totalRevenue / $totalAssets) = $assetTurnover\n";

    // Asset Ratio
    $acidRatio = ($totalLiabilities != 0) ? ($totalCurrentAssets - $totalInventory) / $totalLiabilities : 'N/A';
    //echo "Asset Ratio Calculation: (($totalCurrentAssets + $totalInventory) / $totalLiabilities) = $acidRatio\n";
} else {
    echo "No data available";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <title>Welcome Accountant</title>
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
        <a href="./help_accounting.php" class="nav-link" title="Get help and support">&#x2753;</a>
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

    <div class="dashboard-container">
    <h1 class="dashboard-title">Dashboard</h1>
    <div class="button-container">
        <div class="ratio-box <?php echo getColorClass($currentRatio, 1.5, 1.0); ?>">
            <h2>Current Ratio</h2>
            <div class="ratio-content">
                <div class="circle <?php echo getColorClass($currentRatio, 1.5, 1.0); ?>"></div>
                <p><?php echo number_format($currentRatio * 100, 2); ?>%</p>
            </div>
        </div>

        <div class="ratio-box <?php echo getColorClass($returnOnAssets, 0.10, 0.05); ?>">
            <h2>Return on Assets</h2>
            <div class="ratio-content">
                <div class="circle <?php echo getColorClass($returnOnAssets, 0.10, 0.05); ?>"></div>
                <p><?php echo number_format($returnOnAssets * 100, 2); ?>%</p>
            </div>
        </div>

        <div class="ratio-box <?php echo getColorClass($returnOnEquity, 0.15, 0.10); ?>">
            <h2>Return on Equity</h2>
            <div class="ratio-content">
                <div class="circle <?php echo getColorClass($returnOnEquity, 0.15, 0.10); ?>"></div>
                <p><?php echo number_format($returnOnEquity * 100, 2); ?>%</p>
            </div>
        </div>

        <div class="ratio-box <?php echo getColorClass($netProfitMargin, 0.20, 0.10); ?>">
            <h2>Net Profit Margin</h2>
            <div class="ratio-content">
                <div class="circle <?php echo getColorClass($netProfitMargin, 0.20, 0.10); ?>"></div>
                <p><?php echo number_format($netProfitMargin * 100, 2); ?>%</p>
            </div>
        </div>

        <div class="ratio-box <?php echo getColorClass($assetTurnover, 1.0, 0.5); ?>">
            <h2>Asset Turnover</h2>
            <div class="ratio-content">
                <div class="circle <?php echo getColorClass($assetTurnover, 1.0, 0.5); ?>"></div>
                <p><?php echo number_format($assetTurnover * 100, 2); ?>%</p>
            </div>
        </div>

        <div class="ratio-box <?php echo getColorClass($acidRatio, 1.5, 1.0); ?>">
            <h2>Acid Ratio</h2>
            <div class="ratio-content">
                <div class="circle <?php echo getColorClass($acidRatio, 1.5, 1.0); ?>"></div>
                <p><?php echo number_format($acidRatio * 100, 2); ?>%</p>
            </div>
        </div>
    </div>
</div>


    <?php
    function getColorClass($ratio, $goodThreshold, $warningThreshold) {
        if ($ratio >= $goodThreshold) {
            return "green";
        } elseif ($ratio >= $warningThreshold) {
            return "yellow";
        } else {
            return "red";
        }
    }
    ?>

<style>
.dashboard-container {
    background-color: white;
    padding: 30px;
    border-radius: 12px;
    width: 80%;
    max-width: 800px;
    margin: 0 auto;
    margin-top: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.dashboard-title {
    text-align: center;
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

.button-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.ratio-box {
    background-color: #4f4f4f;
    border-radius: 8px;
    padding: 20px;
    color: white;
    text-align: center;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.ratio-box h2 {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

.ratio-content {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
}

.circle {
    width: 25px;
    height: 25px;
    border-radius: 50%;
}

.green .circle {
    background-color: green;
}

.yellow .circle {
    background-color: yellow;
}

.red .circle {
    background-color: red;
}

    </style>

</body>
</html>

