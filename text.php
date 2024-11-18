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
// Handle form submission for journal entry
// Handle form submission for journal entry
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $account_type = mysqli_real_escape_string($conn, $_POST['account_type']);
    $account_description = mysqli_real_escape_string($conn, $_POST['account']);
    $debits = $_POST['debit']; // Array of debit values
    $credit = intval($_POST['credit']); // Use intval to ensure it's an integer
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    foreach ($debits as $debit) {
        if (intval($debit) <= 0) {
            echo "<p style='color: red;'>Error: Debit must be a positive integer.</p>";
            $valid = false;
            break;
        }
    }
    foreach ($debits as $debit) {
        if (intval($debit) <= 0) {
            echo "<p style='color: red;'>Error: Debit must be a positive integer.</p>";
            $valid = false;
            break;
        }
    }
    

    // Check if debit and credit are positive integers and zero out
    if ($debit <= 0 || $credit <= 0) {
        echo "<p style='color: red;'>Error: Debit and Credit must be positive integers.</p>";
    } elseif ($debit !== $credit) {
        echo "<p style='color: red;'>Error: Debit and Credit must be equal (Debit = Credit).</p>";
    } else {
        // Prepare SQL query to insert new journal entry
        $sql = "INSERT INTO Journal_Entries (
                    account_type,
                    account_description,
                    debit,
                    credit,
                    created_at,
                    ModifiedBy,
                    IsApproved,
                    comment
                ) VALUES (
                    '$account_type',
                    '$account_description',
                    $debit,
                    $credit,
                    NOW(),
                    '$username',
                    0,
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
    <link rel="stylesheet" href="./styles.css"> 
    <script>


let selectedAccounts = [];

function addCreditField() {
    const container = document.getElementById('credit-container');

    // Create a new div for the credit field and select dropdown
    const newCreditDiv = document.createElement('div');
    
    // Create the credit input
    const newInput = document.createElement('input');
    newInput.type = 'number';
    newInput.name = 'credit[]'; // Use array notation for multiple inputs
    newInput.placeholder = 'Credit';
    newInput.step = '0.01';
    newInput.required = true;

    // Create the select dropdown for accounts
    const accountSelect = document.createElement('select');
    accountSelect.name = 'account_select[]'; // Use array notation for multiple selects
    accountSelect.required = true;

    // Populate the dropdown with options, excluding previously selected accounts
    const accounts = [
        "Cash",
        "Accounts Receivable",
        "Supplies (Specialty Items)",
        "Prepaid Insurance",
        "Prepaid Rent",
        "Office Equipment",
        "Store Equipment",
        "Accumulated Depreciation",
        "Accounts Payable",
        "Wages Payable",
        "Unearned Subscription Revenue",
        "Unearned Service/Ticket Revenue",
        "Unearned Repair Fees",
        "Retained Earnings",
        "Service Fees",
        "Wages Expense",
        "Salaries Expense",
        "Advertising Expense",
        "Store Supplies Expense",
        "Rent Expense",
        "Telephone Expense",
        "Electricity Expense",
        "Utilities Expense",
        "Insurance Expense",
        "Depreciation Expense"
    ];

    // Add default option
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Select Account';
    accountSelect.appendChild(defaultOption);

    // Populate the select dropdown
    accounts.forEach(account => {
        if (!selectedAccounts.includes(account)) {
            const option = document.createElement('option');
            option.value = account;
            option.textContent = account;
            accountSelect.appendChild(option);
        }
    });

    // Add event listener to track selected accounts
    accountSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        if (selectedValue) {
            selectedAccounts.push(selectedValue);
        }
    });

    // Append the input and select to the new div
    newCreditDiv.appendChild(newInput);
    newCreditDiv.appendChild(accountSelect);
    
    // Append the new div to the container
    container.appendChild(newCreditDiv);
}

    
//     function addDebitField() {
//     const container = document.getElementById('debit-container');
//     const newInput = document.createElement('input');
//     newInput.type = 'number';
//     newInput.name = 'debit[]'; // Use array notation for multiple inputs
//     newInput.placeholder = 'Debit';
//     newInput.step = '0.01';
//     newInput.required = true;
//     container.appendChild(newInput);
// }

// function addCreditField() {
//     const container = document.getElementById('credit-container');
//     const newInput = document.createElement('input');
//     newInput.type = 'number';
//     newInput.name = 'credit[]'; // Use array notation for multiple inputs
//     newInput.placeholder = 'Credit';
//     newInput.step = '0.01';
//     newInput.required = true;
//     container.appendChild(newInput);
// }


//         function clearInputs() {
//             document.getElementsByName('account_type')[0].selectedIndex = 0; // Reset select
//             document.getElementsByName('account')[0].selectedIndex = 0; // Reset select
//             document.getElementsByName('account_indented')[0].selectedIndex = 0; // Reset select
//             document.getElementsByName('debit')[0].value = ''; // Clear input
//             document.getElementsByName('credit')[0].value = ''; // Clear input
//             document.getElementsByName('comment')[0].value = ''; // Clear textarea
//         }
    </script>
</head>
<body>


<nav>
    <div class="welcome">
        <img src="profile.png" alt="Picture" class="picture">
        <h1 class="title">Ledger Legend Administrator</h1> 
    </div>
    <div class="user-profile">
        <img src="pfp.png" alt="User Picture" class="profile-pic">
        <span class="username"><?php echo htmlspecialchars($username); ?></span>
        <a href="./logout.php" class="logout-btn">Logout</a>
    </div>
</nav>

<div class="main-bar">
    <a href="./administrator_home.php" class="nav-link">Home</a>
    <a href="./it_ticket.php" class="nav-link">IT Ticket</a>

    <div class="dropdown">
        <button class="dropbtn nav-link">User Management</button>
        <div class="dropdown-content">
            <a href="./create_new_user_admin.php">Create User</a>
            <a href="./user_roster.php">View Users</a>
            <a href="./Manage_Users.html">Account Approval</a>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropbtn nav-link">Client Account Management</button>
        <div class="dropdown-content">
            <a href="./create_client_account_admin.php">Create Account</a>
            <a href="./view_all_client_accounts.php">View All Accounts</a>
            <a href="./view_all_journal_entries.php">View Journal Entries</a>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropbtn nav-link">Reports</button>
        <div class="dropdown-content">
            <a href="#">User Report</a>
            <a href="./Expired_Passwords_Log.php">Expired Passwords Report</a>
            <a href="#">Login Attempts Report</a>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropbtn nav-link">Notifications</button>
        <div class="dropdown-content">
            <a href="">Password Expiration Alerts</a>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropbtn nav-link">Email Management</button>
        <div class="dropdown-content">
            <a href="">Send Email</a>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropbtn nav-link">Settings</button>
        <div class="dropdown-content">
            <a href="#">System Settings</a>
        </div>
    </div>
</div>

<div class="main">
    <div class="container">
        <h2>Search for Account</h2>
        <form method="GET" action="">
            <input type="number" name="account_id" placeholder="Enter Account ID" required>
            <button type="submit">Search</button>
        </form>

        <?php if ($account): ?>
            <h2>Create New Journal Entry for Account ID: <?php echo htmlspecialchars($account_id); ?></h2>
            <form method="POST" action="">
                
            <form method="POST" action="">
                    <select name="account_type" style="width: 200px; height: 30px;" required>
                    <option value="Adjusting">Adjusting</option>
                    <option value="Regular" selected>Regular</option> <!-- Default option -->
            </select>
            </form>


                <label for="account">Account:</label>
                <select name="account" id="account" style="width: 200px; height: 30px;" required>
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

                <div style="margin-left: 20px;"> <!-- Indent for the second label -->
                    <label for="account_indented">Account:</label>
                    <select name="account_indented" id="account_indented" style="width: 200px; height: 30px;" required>
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

                <input type="number" step="0.01" name="debit" placeholder="Debit" required>
                <div id="debit-container">
    <input type="number" step="0.01" name="debit[]" placeholder="Debit" required>
</div>
<button type="button" onclick="addDebitField()" style="margin-top: 10px;">Add More Debit</button>
<!-- <div id="credit-container">
    <input type="number" step="0.01" name="credit[]" placeholder="Credit" required>
</div>

<button type="button" onclick="addCreditField()" style="margin-top: 10px;">Add More Credit</button> -->

<div id="credit-container">
    <div>
        <input type="number" step="0.01" name="credit[]" placeholder="Credit" required>
        <select name="account_select[]" required>
            <option value="">Select Account</option>
            <!-- Populate options dynamically -->
        </select>
    </div>
</div>
<button type="button" onclick="addCreditField()" style="margin-top: 10px;">Add More Credit</button>


                <input type="number" step="0.01" name="credit" placeholder="Credit" required>
                <textarea name="comment" placeholder="Comment"></textarea><br><br>
                <button type ="button" class="Source_Documents" style="width: 200px; height: 30px;">Source Documents</button>
                <button type="button" onclick="clearInputs()" style="width: 200px; height: 30px;">Clear Inputs</button>
                
                <button type="submit" style="width: 200px; height: 30px;">Create Journal Entry</button>
                <button type="button" onclick="window.location.href='./view_all_journal_entries.php'" style="width: 200px; height: 30px;">Cancel</button>
                </form>
              
            </form>
        <?php elseif ($account_id): ?>
            <p>No account found with ID: <?php echo htmlspecialchars($account_id); ?></p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>