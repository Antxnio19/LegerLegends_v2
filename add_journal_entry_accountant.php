<?php
session_start(); // Start the session at the very beginning
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Create connection
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Store the username from the session
$username = $_SESSION['username'];

// Search for account by ID
$account_id = isset($_GET['account_id']) ? intval($_GET['account_id']) : 0;
$account = null;

if ($account_id) {
    $account_query = "SELECT * FROM Client_Accounts WHERE id = $account_id";
    $result = $conn->query($account_query);
    $account = $result->fetch_assoc();
}

// Handle form submission for journal entry
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $account_type = mysqli_real_escape_string($conn, $_POST['account_type']);
    $account_debit = mysqli_real_escape_string($conn, $_POST['account_debit']);
    $account_credit = mysqli_real_escape_string($conn, $_POST['account_credit']);
    $debit = intval($_POST['debit']); // Use intval to ensure it's an integer
    $credit = intval($_POST['credit']); // Use intval to ensure it's an integer
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Check if debit and credit are positive integers and zero out
    if ($debit <= 0 || $credit <= 0) {
        echo "<p style='color: red;'>Error: Debit and Credit must be positive integers.</p>";
    } elseif ($debit !== $credit) {
        echo "<p style='color: red;'>Error: Debit and Credit must be equal (Debit = Credit).</p>";
    } else {
        // Prepare SQL query to insert new journal entry
        $sql = "INSERT INTO Journal_Entries (
            client_account_id,
            account_type,
            account_debit,
            account_credit,
            debit,
            credit,
            created_at,
            ModifiedBy,
            IsApproved,
            comment
        ) VALUES (
            '$account_id',      -- Properly closed single quotes here
            '$account_type', 
            '$account_debit', 
            '$account_credit', 
            $debit,             -- No quotes around numeric fields
            $credit,            -- No quotes around numeric fields
            NOW(), 
            '$username', 
            0,                  -- No quotes for boolean or numeric fields
            '$comment'
        )";

        if ($conn->query($sql) === TRUE) {
            // Redirect to the view all journal entries page after successful insertion
            header('Location: ./view_all_journal_entries.php');
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
            document.getElementsByName('account_type')[0].selectedIndex = 0; // Reset select
            document.getElementsByName('account_debit')[0].selectedIndex = 0; // Reset select
            document.getElementsByName('account_credit')[0].selectedIndex = 0; // Reset select
            document.getElementsByName('debit')[0].value = ''; // Clear input
            document.getElementsByName('credit')[0].value = ''; // Clear input
            document.getElementsByName('comment')[0].value = ''; // Clear textarea
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

 <!-- Navigation Bar -->
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

        <!-- Buttons to open calculator and calendar -->
        <button id="calculatorBtn" class="nav-link" title="Open the calculator">Calculator</button>
        <button id="calendarBtn" class="nav-link" title="Open the calendar">Calendar</button>
    </div>

    <!-- Calculator Modal -->
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

    <!-- Calendar Modal -->
    <div id="calendarModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeCalendar">&times;</span>
            <h2>Calendar</h2>
            <div id="calendar"></div>
        </div>
    </div>
    <script src="modals_calculator_calendar.js"></script>

<div class="main">
    <div class="container">
        <h2>Search for Account</h2>
        <form method="GET" action="">
            <input type="number" name="account_id" placeholder="Enter Account ID" required>
            <button type="submit">Search</button>
        </form>

        <?php if ($account): ?>
            <h2>Create New Journal Entry for Account ID: <?php echo htmlspecialchars($account_id); ?></h2>
            <form method="POST" action="" class="journal-entry-form">
                
                <div class="form-group">
                    <label for="account_type">Account Type:</label>
                    <select name="account_type" id="account_type" required>
                        <option value="">Select Account Type</option>
                        <option value="Expense">Expense</option>
                        <option value="Revenue">Revenue</option>
                        <option value="Asset">Asset</option>
                        <option value="Asset">Liability</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="account_debit">Account:</label>
                    <select name="account_debit" id="account_debit" required>
                        <option value="">Select Account</option>
                        <option value="Cash">Cash</option>
                        <option value="Accounts Receivable">Accounts Receivable</option>
                        <option value="Supplies (Specialty Items, I.E. Medical, Bicycle, Tailoring, etc.)">Supplies (Specialty Items)</option>
                        <option value="Prepaid Insurance">Prepaid Insurance</option>
                        <option value="Prepaid Rent">Prepaid Rent</option>
                        <option value="Office Equipment">Office Equipment</option>
                        <option value="Store Equipment">Store Equipment</option>
                        <option value="Accumulated Depreciation">Accumulated Depreciation</option>
                        <option value="Accounts Payable">Accounts Payable</option>
                        <option value="Wages Payable">Wages Payable</option>
                        <option value="Unearned Subscription Revenue">Unearned Subscription Revenue</option>
                        <option value="Unearned Service/Ticket Revenue">Unearned Service/Ticket Revenue</option>
                        <option value="Unearned Repair Fees">Unearned Repair Fees</option>
                        <option value="Retained Earnings">Retained Earnings</option>
                        <option value="Service Fees">Service Fees</option>
                        <option value="Wages Expense">Wages Expense</option>
                        <option value="Salaries Expense">Salaries Expense</option>
                        <option value="Advertising Expense">Advertising Expense</option>
                        <option value="Store Supplies Expense">Store Supplies Expense</option>
                        <option value="Rent Expense">Rent Expense</option>
                        <option value="Telephone Expense">Telephone Expense</option>
                        <option value="Electricity Expense">Electricity Expense</option>
                        <option value="Utilities Expense">Utilities Expense</option>
                        <option value="Insurance Expense">Insurance Expense</option>
                        <option value="Depreciation Expense">Depreciation Expense</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="account_credit">Account:</label>
                    <select name="account_credit" id="account_credit" required>
                        <option value="">Select Account</option>
                        <option value="Cash">Cash</option>
                        <option value="Accounts Receivable">Accounts Receivable</option>
                        <option value="Supplies (Specialty Items, I.E. Medical, Bicycle, Tailoring, etc.)">Supplies (Specialty Items)</option>
                        <option value="Prepaid Insurance">Prepaid Insurance</option>
                        <option value="Prepaid Rent">Prepaid Rent</option>
                        <option value="Office Equipment">Office Equipment</option>
                        <option value="Store Equipment">Store Equipment</option>
                        <option value="Accumulated Depreciation">Accumulated Depreciation</option>
                        <option value="Accounts Payable">Accounts Payable</option>
                        <option value="Wages Payable">Wages Payable</option>
                        <option value="Unearned Subscription Revenue">Unearned Subscription Revenue</option>
                        <option value="Unearned Service/Ticket Revenue">Unearned Service/Ticket Revenue</option>
                        <option value="Unearned Repair Fees">Unearned Repair Fees</option>
                        <option value="Retained Earnings">Retained Earnings</option>
                        <option value="Service Fees">Service Fees</option>
                        <option value="Wages Expense">Wages Expense</option>
                        <option value="Salaries Expense">Salaries Expense</option>
                        <option value="Advertising Expense">Advertising Expense</option>
                        <option value="Store Supplies Expense">Store Supplies Expense</option>
                        <option value="Rent Expense">Rent Expense</option>
                        <option value="Telephone Expense">Telephone Expense</option>
                        <option value="Electricity Expense">Electricity Expense</option>
                        <option value="Utilities Expense">Utilities Expense</option>
                        <option value="Insurance Expense">Insurance Expense</option>
                        <option value="Depreciation Expense">Depreciation Expense</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="debit">Debit:</label>
                    <input type="number" step="0.01" name="debit" id="debit" placeholder="Debit" required>
                </div>
                
                <div class="form-group">
                    <label for="credit">Credit:</label>
                    <input type="number" step="0.01" name="credit" id="credit" placeholder="Credit" required>
                </div>

                <div class="form-group">
                    <label for="comment">Comment:</label>
                    <textarea name="comment" id="comment" placeholder="Comment"></textarea>
                </div>
                
                <div class="form-buttons">
                    <button type="button" class="Source_Documents" style="width: 200px; height: 30px;">Source Documents</button>
                    <button type="button" onclick="clearInputs()" style="width: 200px; height: 30px;">Clear Inputs</button>
                    <button type="submit" style="width: 200px; height: 30px;">Create Journal Entry</button>
                    <button type="button" onclick="window.location.href='./view_all_journal_entries.php'" style="width: 200px; height: 30px;">Cancel</button>
                </div>
            </form>
        <?php elseif ($account_id): ?>
            <p>No account found with ID: <?php echo htmlspecialchars($account_id); ?></p>
        <?php endif; ?>
    </div>
</div>
<style>
    .main {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .container {
        background-color: white;
        color: black;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 100%;
        max-width: 600px;
    }

    h2 {
        color: black;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="number"], select, textarea {
        width: calc(100% - 10px);
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    textarea {
        height: 100px;
    }

    .form-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    button {
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        padding: 10px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>
</body>
</html>
