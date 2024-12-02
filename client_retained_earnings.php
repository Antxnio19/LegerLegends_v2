<?php
session_start();

$username = $_SESSION['username'];

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
$beginningRetainedEarnings = 0; 
$dividends = 0; 
$endRetainedEarnings = 0;
$clientName = "Addams & Family Inc.";

// Query for Revenues
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

// Query for Expenses
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

// Calculate total revenue
while ($row = $revenueResult->fetch_assoc()) {
    $totalRevenue += $row['balance'];
}

// Calculate total expenses
while ($row = $expenseResult->fetch_assoc()) {
    $totalExpenses += $row['balance'];
}

// Calculate net income
$netIncome = $totalRevenue - $totalExpenses;

// Calculate ending retained earnings
$endRetainedEarnings = $beginningRetainedEarnings + $netIncome - $dividends;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <link rel="icon" type="image/png" href="profile.png">
    
    <title>Statement of Retained Earnings</title>
    <style>
        .centered {
            text-align: center;
        }
        .right-align {
            text-align: right;
        }
        .double-underline {
            text-decoration: underline double;
        }
        .single-underline {
            text-decoration: underline;
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }

        function downloadPDF() {
            const element = document.getElementById('printableArea');
            html2pdf()
                .from(element)
                .save('retained_earnings_statement.pdf');
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
<nav>
    <div class="welcome">
        <img src="profile.png" alt="Picture" class="picture">
        <h1 class="title">Ledger Legend Manager</h1> 
    </div>
    <div class="user-profile">
        <img src="pfp.png" alt="User Picture" class="profile-pic">
        <span class="username"><?php echo htmlspecialchars($username); ?></span>
        <a href="./logout.php" class="logout-btn">Logout</a>
    </div>
</nav>

<div class="main-bar">
        <a href="./manager_home.php" class="nav-link" title="Takes you to the home page">Home</a>
        <a href="./manager_home.php" class="nav-link" title="Submit an IT ticket">IT Ticket</a>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage client accounts">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./manager_view_all_client_accounts.php" title="View all client accounts">Chart of Accounts</a>
                <a href="./manager_View_some_accounts.php" title="View specific client accounts">Accounts</a>
                <a href="./add_journal_entry.php" title="Create a new journal entry">Add Journal Entries</a>
                <a href="./view_all_journal_entries.php" title="Views a new journal entry">View Jounral Entries</a>
                <a href="./Trial_balance.php" title="Generates Trial Balance">Trial Balance</a>
                <a href="./client_income.php" title="Generates Client Income Statement">Income Statement</a>
                <a href="./BalanceSheet.php" title="Generates Balance sheet">Balance sheet</a>
                <a href="./client_retained_earnings.php" title="Generates Retained Earnings">Retained Earnings</a>
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

<div class="container centered">
        <div class="centered-info" style="color: white;">
            <h1>Addams & Fammily Inc.</h1>
            <h2>Income Statement</h2>
            <h3>For the Year Ended <?php echo date('F d, Y'); ?></h3>
        </div>

        <div class="actions">
        <button onclick="downloadScreen()">Download</button>
        <button onclick="emailScreen()">Email</button>
        <button onclick="printScreen()">Print</button>
        </div>
        
    <div id="printableArea">
        <table>
            <tr>
                <td>Beg Retained Earnings, 4/1/10</td>
                <td class="right-align">$<?php echo number_format($beginningRetainedEarnings, 2); ?></td>
            </tr>
            <tr>
                <td>Add: Net Income</td>
                <td class="right-align">$<?php echo number_format($netIncome, 2); ?></td>
            </tr>
            <tr>
                <td>Total:</td>
                <td class="right-align single-underline">$<?php echo number_format($netIncome, 2); ?></td>
            </tr>
            <tr>
                <td>Less: Dividends</td>
                <td class="right-align">$<?php echo number_format($dividends, 2); ?></td>
            </tr>
            <tr>
                <td>Total:</td>
                <td class="right-align single-underline">$<?php echo number_format($dividends, 2); ?></td>
            </tr>
            <tr>
                <th>End Retained Earnings, 4/30/10</th>
                <th class="right-align double-underline">$<?php echo number_format($endRetainedEarnings, 2); ?></th>
            </tr>
        </table>
    </div>
</div>

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

        pdf.save('Balance_Sheet.pdf');
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

<Style>
    .actions {
            text-align: center;
            margin-bottom: 20px;
    }
    .actions button {
        margin: 5px;
        padding: 10px 15px;
        font-size: 14px;
    }
</Style>

</body>
</html>

<?php
$conn->close();
?>