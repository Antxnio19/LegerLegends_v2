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

$query = "
    SELECT 
        account_number,
        account_name,
        normal_side,
        balance
    FROM 
        Client_Accounts
    ORDER BY 
        CAST(account_number AS UNSIGNED)
";

$result = $conn->query($query);

$totalDebit = 0;
$totalCredit = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <link rel="icon" type="image/png" href="profile.png">
    <title>Ledger Legend Accountant Page</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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

    <div class="titlea">
        <h1>Addams & Fammily Inc.</h1>
        <h2>Trial Balance</h2>
        <h3>For the Year Ended <?php echo date('F d, Y'); ?></h3>
    </div>

    <div class="actions">
        <button onclick="downloadScreen()">Download</button>
        <button onclick="emailScreen()">Email</button>
        <button onclick="printScreen()">Print</button>
    </div>

    <table id="trialBalanceTable">
    <thead>
        <tr>
            <th>Account Number</th>
            <th>Account Name</th>
            <th>Total Debit</th>
            <th>Total Credit</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($account = $result->fetch_assoc()) {

            if ($account['account_name'] == 'Retained earnings') {
                continue; 
            }

            $balance = $account['balance'];

            if ($balance == 0) {
                continue;
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($account['account_number']) . "</td>";
            echo "<td>" . htmlspecialchars($account['account_name']) . "</td>";

            if ($account['normal_side'] === 'debit') {
                if ($balance >= 0) {
                    echo "<td class='debit'>$" . number_format($balance, 2) . "</td>";
                    echo "<td></td>"; 
                    $totalDebit += $balance; 
                } else {
                    echo "<td></td>"; 
                    echo "<td class='credit'>($" . number_format(abs($balance), 2) . ")</td>"; 
                    $totalCredit += abs($balance); 
                }
            } else {
                
                if ($balance >= 0) {
                    echo "<td></td>"; 
                    echo "<td class='credit'>$" . number_format($balance, 2) . "</td>";
                    $totalCredit += $balance; 
                } else {
                    echo "<td class='debit'>($" . number_format(abs($balance), 2) . ")</td>"; 
                    echo "<td></td>"; 
                    $totalDebit += abs($balance); 
                }
            }
            echo "</tr>";
        }
        ?>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td class="debit"><strong>$<?php echo number_format($totalDebit, 2); ?></strong></td>
            <td class="credit"><strong>$<?php echo number_format($totalCredit, 2); ?></strong></td>
        </tr>
    </tbody>
</table>

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

<style>
    /* Add this to your CSS file */
    td.debit, td.credit {
        text-align: right;
        padding-right: 10px; 
    }
    td.totala {
        text-align: right;
    }
    .titlea {
    text-align: center;
    margin: 0;
    color: white;
    }

    h1.titlea, h2.titlea, p.titlea {
        margin: 20px 0; 
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
</body>
</html>
