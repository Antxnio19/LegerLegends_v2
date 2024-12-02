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

$totalRevenue = 0;
$totalExpenses = 0;
$netIncome = 0;

$revenueQuery = "
    SELECT 
        ca.account_name,
        COALESCE(SUM(lt.credit - lt.debit), 0) AS balance
    FROM 
        Client_Accounts ca
    LEFT JOIN 
        Ledger_Transactions lt ON ca.id = lt.client_account_id
    WHERE 
        ca.account_category = 'Revenue'
    GROUP BY 
        ca.account_name
";
$revenueResult = $conn->query($revenueQuery);

$expenseQuery = "
    SELECT 
        ca.account_name,
        COALESCE(SUM(lt.debit - lt.credit), 0) AS balance
    FROM 
        Client_Accounts ca
    LEFT JOIN 
        Ledger_Transactions lt ON ca.id = lt.client_account_id
    WHERE 
        ca.account_category = 'Expense'
    GROUP BY 
        ca.account_name
";
$expenseResult = $conn->query($expenseQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <link rel="icon" type="image/png" href="profile.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    
    <title>Income Statement</title>
</head>
<body>

<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1 class="title">Ledger Legend Accountant</h1>
        </div>
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span> <!-- Display the dynamic username here -->
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
    <script>
    async function downloadScreen() {
        const { jsPDF } = window.jspdf;

        const pdf = new jsPDF();
        const pageContent = document.body;

        await html2canvas(pageContent).then((canvas) => {
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = 190; 
            const pageHeight = pdf.internal.pageSize.height;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;

            pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight); 
        });

        pdf.save('Income_Statement.pdf');
    }

    async function emailScreen() {
        const { jsPDF } = window.jspdf;

        const pdf = new jsPDF();
        const pageContent = document.body;

        await html2canvas(pageContent).then((canvas) => {
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = 190;
            const pageHeight = pdf.internal.pageSize.height;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;

            pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
        });

        const pdfBlob = pdf.output('blob');
        const reader = new FileReader();

        reader.onload = function () {
            const base64Data = reader.result.split(',')[1];

            const mailtoLink = `mailto:?subject=Income Statement&body=Attached is the Income Statement.&attachment=${base64Data}`;
            window.location.href = mailtoLink;
        };

        reader.readAsDataURL(pdfBlob);
    }

    function printScreen() {
        window.print();
    }
</script>


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
            <h2>Income Statement</h2>
            <h3>For the Year Ended <?php echo date('F d, Y'); ?></h3>
        </div>

        <div class="actions">
        <button onclick="downloadScreen()">Download</button>
        <button onclick="emailScreen()">Email</button>
        <button onclick="printScreen()">Print</button>
        </div>

        <table class="table-values">
            <tr>
                <th>Revenues</th>
                <td></td>
            </tr>
            <?php while ($row = $revenueResult->fetch_assoc()) : ?>
                <?php if ($row['balance'] != 0) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                        <td class="numeric">$<?php echo number_format(abs($row['balance']), 2); ?></td>
                    </tr>
                    <?php $totalRevenue += $row['balance']; ?>
                <?php endif; ?>
            <?php endwhile; ?>
            <tr>
                <th>Total Revenues</th>
                <td class="numeric underline">$<?php echo number_format(abs($totalRevenue), 2); ?></td>
            </tr>

            <tr>
                <th>Expenses</th>
                <td></td>
            </tr>
            <?php while ($row = $expenseResult->fetch_assoc()) : ?>
                <?php if ($row['balance'] != 0) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                        <td class="numeric">$<?php echo number_format(abs($row['balance']), 2); ?></td>
                    </tr>
                    <?php $totalExpenses += $row['balance']; ?>
                <?php endif; ?>
            <?php endwhile; ?>
            <tr>
                <th>Total Expenses</th>
                <td class="numeric underline">$<?php echo number_format(abs($totalExpenses), 2); ?></td>
            </tr>

            <tr>
                <th>Net Income (Loss)</th>
                <td class="numeric double-underline">$<?php echo number_format(abs($totalRevenue - $totalExpenses), 2); ?></td>
            </tr>
        </table>
</div>


</body>
</html>

<?php
$conn->close();
?>