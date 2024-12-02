<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$conn = mysqli_connect("localhost", "root", "root", "accounting_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$accounts = [];
$result = $conn->query("SELECT id, account_name FROM Client_Accounts WHERE IsActive = 1");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $accounts[] = $row;
    }
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entry_type = $_POST['entry_type'];
    $debit_accounts = $_POST['debit_accounts'];
    $debit_values = $_POST['debit_values'];
    $credit_accounts = $_POST['credit_accounts'];
    $credit_values = $_POST['credit_values'];
    $comment = $_POST['comment'];

    function sanitizeCurrency($value) {
        return floatval(preg_replace('/[^\d.]/', '', $value));
    }

    $debit_values = array_map('sanitizeCurrency', $debit_values);
    $credit_values = array_map('sanitizeCurrency', $credit_values);

    $total_debit = array_sum($debit_values);
    $total_credit = array_sum($credit_values);

    if ($total_debit != $total_credit) {
        $error_message = "Total debits must equal total credits.";
    } else {
        $stmt = $conn->prepare("INSERT INTO Journal_Entries (created_by, entry_type, is_approved, comment) VALUES (?, ?, 'Pending', ?)");
        if (!$stmt) {
            $error_message = "Failed to prepare statement: " . $conn->error;
        } else {
            $stmt->bind_param("sss", $username, $entry_type, $comment);
            if ($stmt->execute()) {
                $new_journal_entry_id = $stmt->insert_id;
                $stmt->close();

                function getAccountType($conn, $account_id) {
                    $result = $conn->query("SELECT account_category FROM Client_Accounts WHERE id = $account_id");
                    $row = $result->fetch_assoc();
                    return $row['account_category'] == 'Asset' ? 'Asset' : 'Liability';
                }

                foreach ($debit_accounts as $index => $account_id) {
                    $debit_value = $debit_values[$index];
                    $account_type = getAccountType($conn, $account_id);

                    $stmt = $conn->prepare("INSERT INTO Journal_Entry_Lines (journal_entry_id, account, account_type, debit, credit) VALUES (?, ?, ?, ?, 0)");
                    if ($stmt) {
                        $account_name_query = $conn->query("SELECT account_name FROM Client_Accounts WHERE id = $account_id");
                        $account_name = $account_name_query->fetch_assoc()['account_name'];
                        $stmt->bind_param("issd", $new_journal_entry_id, $account_name, $account_type, $debit_value);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        $error_message = "Failed to insert debit entries: " . $conn->error;
                        break;
                    }
                }

                foreach ($credit_accounts as $index => $account_id) {
                    $credit_value = $credit_values[$index];
                    $account_type = getAccountType($conn, $account_id);

                    $stmt = $conn->prepare("INSERT INTO Journal_Entry_Lines (journal_entry_id, account, account_type, debit, credit) VALUES (?, ?, ?, 0, ?)");
                    if ($stmt) {
                        $account_name_query = $conn->query("SELECT account_name FROM Client_Accounts WHERE id = $account_id");
                        $account_name = $account_name_query->fetch_assoc()['account_name'];
                        $stmt->bind_param("issd", $new_journal_entry_id, $account_name, $account_type, $credit_value);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        $error_message = "Failed to insert credit entries: " . $conn->error;
                        break;
                    }
                }

                if (empty($error_message)) {
                    echo "<script>window.location.href = 'view_all_journal_entries.php';</script>";
                    exit();
                }
            } else {
                $error_message = "Failed to insert journal entry: " . $stmt->error;
                $stmt->close();
            }
        }
    }

    if (!empty($error_message)) {
        echo "<div class='error-message'>$error_message</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Journal Entry</title>
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <script>
        function clearInputs() {
            document.getElementsByName('account_type')[0].selectedIndex = 0;
            document.getElementsByName('account_debit')[0].selectedIndex = 0;
            document.getElementsByName('account_credit')[0].selectedIndex = 0;
            document.getElementsByName('debit')[0].value = ''; 
            document.getElementsByName('credit')[0].value = ''; 
            document.getElementsByName('comment')[0].value = ''; 
        }
    </script>
</head>
<body>


<nav>
    <div class="welcome">
        <img src="profile.png" alt="Picture" class="picture">
        <h1 class="title">Ledger Legend Accounatant</h1> 
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
    <h2 style="color: white;">Add Journal Entry</h2>
<div class="form-container">
    <form id="journalForm" action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="entry_type">Entry Type:</label>
            <select name="entry_type" id="entry_type">
                <option value="Normal">Normal</option>
                <option value="Adjusted">Adjusted</option>
            </select>
        </div>

        <h3>Add Entries</h3>
        <div id="debitEntries">
            <div class="entryRow">
                <select name="debit_accounts[]" class="account-select" required>
                    <option value="">Choose an account</option>
                    <?php foreach ($accounts as $account): ?>
                        <option value="<?= $account['id'] ?>"><?= $account['account_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="debit_values[]" placeholder="Debit Amount" class="formatted-input" required>
                <button type="button" class="remove-btn" onclick="removeRow(this)">-</button>
                <button type="button" class="add-btn" onclick="addDebitRow()">+</button>
            </div>
        </div>

        <h4></h4>
        <div id="creditEntries">
            <div class="entryRow" style="margin-left: 30px;">
                <select name="credit_accounts[]" class="account-select" required>
                    <option value="">Choose an account</option>
                    <?php foreach ($accounts as $account): ?>
                        <option value="<?= $account['id'] ?>"><?= $account['account_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="credit_values[]" placeholder="Credit Amount" class="formatted-input" required>
                <button type="button" class="remove-btn" onclick="removeRow(this)">-</button>
                <button type="button" class="add-btn" onclick="addCreditRow()">+</button>
            </div>
        </div>

        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" rows="3" placeholder="Add any relevant comments"></textarea>
        </div>

        <div class="form-group">
            <label for="file">Attach Files:</label>
            <input type="file" name="file[]" id="file" multiple onchange="updateFileList()">
            <div id="fileList"></div>
        </div>

        <div class="form-buttons">
            <button type="button" onclick="window.location.href='view_all_journal_entries.php'" class="cancel-btn">Cancel</button>
            <button type="reset" class="clear-btn">Clear</button>
            <button type="submit" class="submit-btn">Submit</button>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
    </form>
</div>


<script>
    function updateAccountOptions() {
        const allSelects = document.querySelectorAll('.account-select');
        
        const selectedAccounts = Array.from(allSelects)
            .map(select => select.value)
            .filter(value => value !== "");

        allSelects.forEach(select => {
            const options = select.querySelectorAll('option');
            options.forEach(option => {
                option.disabled = selectedAccounts.includes(option.value) && option.value !== select.value;
            });
        });
    }

    function formatCurrency(input) {
        const value = parseFloat(input.value.replace(/[^0-9.]/g, ''));
        if (!isNaN(value)) {
            input.value = `$${value.toFixed(2)}`;
        } else {
            input.value = '';
        }
    }

    function allowRawInput(input) {
        input.value = input.value.replace(/[^0-9.]/g, '');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const inputs = document.querySelectorAll('.formatted-input');
        inputs.forEach(input => {
            input.addEventListener('blur', () => formatCurrency(input));
            input.addEventListener('focus', () => allowRawInput(input));
        });
    });

function addDebitRow() {
    const row = document.createElement('div');
    row.className = 'entryRow';
    row.innerHTML = `
        <select name="debit_accounts[]" class="account-select" onchange="updateAccountOptions()" required>
            <option value="">Choose an account</option>
            <?php foreach ($accounts as $account): ?>
                <option value="<?= $account['id'] ?>"><?= $account['account_name'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="debit_values[]" placeholder="Debit Amount" class="formatted-input" min="0" step="0.01" required>
        <button type="button" onclick="removeRow(this)">-</button>`;
    document.getElementById('debitEntries').appendChild(row);

    const input = row.querySelector('.formatted-input');
    input.addEventListener('blur', () => formatCurrency(input));
    input.addEventListener('focus', () => allowRawInput(input));
}

function addCreditRow() {
    const row = document.createElement('div');
    row.className = 'entryRow';
    row.style.marginLeft = '30px';
    row.innerHTML = `
        <select name="credit_accounts[]" class="account-select" onchange="updateAccountOptions()" required>
            <option value="">Choose an account</option>
            <?php foreach ($accounts as $account): ?>
                <option value="<?= $account['id'] ?>"><?= $account['account_name'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="credit_values[]" placeholder="Credit Amount" class="formatted-input" min="0" step="0.01" required>
        <button type="button" onclick="removeRow(this)">-</button>`;
    document.getElementById('creditEntries').appendChild(row);

    const input = row.querySelector('.formatted-input');
    input.addEventListener('blur', () => formatCurrency(input));
    input.addEventListener('focus', () => allowRawInput(input));
}

    function removeRow(button) {
        button.parentElement.remove();
        updateAccountOptions();
    }
</script>

<script>
    function updateFileList() {
        const fileList = document.getElementById('fileList');
        
        const files = document.getElementById('file').files;

        fileList.innerHTML = ''; 

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const listItem = document.createElement('div');
            
            const fileLink = document.createElement('a');
            fileLink.textContent = file.name;
            fileLink.style.color = 'green';  
            fileLink.href = URL.createObjectURL(file);  
            fileLink.target = '_blank';  
            
            listItem.appendChild(fileLink);
            fileList.appendChild(listItem);
        }
    }
</script>
<style>
.form-container {
    width: 80%;
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f7fa;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 20px;
}

h3 {
    font-size: 20px;
    margin-top: 20px;
    margin-bottom: 10px;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

input[type="text"],
select,
textarea {
    width: 100%;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 10px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

button {
    padding: 8px 16px;
    font-size: 16px;
    cursor: pointer;
    border: none;
    border-radius: 4px;
    margin-top: 5px;
}

.add-btn {
    background-color: #28a745;
    color: white;
}

.add-btn:hover {
    background-color: #218838;
}

.remove-btn {
    background-color: #dc3545;
    color: white;
}

.remove-btn:hover {
    background-color: #c82333;
}

.submit-btn {
    background-color: #007bff;
    color: white;
    font-size: 16px;
}

.submit-btn:hover {
    background-color: #0056b3;
}

.clear-btn {
    background-color: #f0ad4e;
    color: white;
}

.clear-btn:hover {
    background-color: #ec971f;
}

.cancel-btn {
    background-color: #6c757d;
    color: white;
}

.cancel-btn:hover {
    background-color: #5a6268;
}

.error-message {
    color: red;
    font-size: 14px;
    margin-top: 15px;
    text-align: center;
}

#fileList {
    margin-top: 10px;
}

#fileList div {
    color: green;
    font-size: 14px;
}

.form-buttons {
    text-align: center;
    margin-top: 20px;
}

.entryRow {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.entryRow select,
.entryRow input {
    margin-right: 10px;
}

.entryRow button {
    padding: 4px 8px;
    margin-right: 10px;
}
</style>
</body>
</html>
