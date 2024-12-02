<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
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
    <style>
        /* Fix for resizing issue */
        .content {
            display: none;
            padding: 15px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            height: 300px; 
            width: 100%; 
            box-sizing: border-box; 
            overflow-y: auto; 
        }

        .content.show {
            display: block;
        }

        .container {
            background-color: white;
            color: black;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 20px auto;
        }

        .tab-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .tab {
            cursor: pointer;
            padding: 10px 20px;
            margin: 5px 0;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .tab:hover {
            background-color: #e0f7fa;
        }

        .tab.active {
            background-color: #b3e5fc; 
            border-color: #0288d1;
            color: #01579b; 
            font-weight: bold;
        }
    </style>
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
        <a href="./administrator_home.php" class="nav-link" title="Takes you to the home page">Home</a>
        <a href="./it_ticket.php" class="nav-link" title="Submit an IT ticket">IT Ticket</a>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage user accounts">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php" title="Create a new user">Create User</a>
                <a href="./user_roster.php" title="View existing users">View Users</a>
                <a href="./Manage_Users.php" title="Approve user accounts">Account Approval</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage client accounts">Client Account Management</button>
            <div class="dropdown-content">
                <a href="./create_client_account_admin.php" title="Create a new client account">Create Account</a>
                <a href="./view_all_client_accounts.php" title="View all client accounts">Chart of Accounts</a>
                <a href="./View_some_accounts.php" title="View specific client accounts">Accounts</a>
                <a href="#" title="Deactivate client accounts">Deactivate Accounts</a>
                <a href="./tbd.php" title="Create a new journal entry">Journalize</a>
                <a href="./tbd.php" title="Create a new journal entry">View Jounral Entries</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Generate reports">Reports</button>
            <div class="dropdown-content">
                <a href="./Expired_Passwords_Log.php" title="View expired passwords report">Expired Passwords Report</a>
                <a href="./eventlogs.php" title="View event logs">Event logs</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage notifications">Notifications</button>
            <div class="dropdown-content">
                <a href="#" title="View password expiration alerts">Password Expiration Alerts</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn nav-link" title="Manage email communications">Email Management</button>
            <div class="dropdown-content">
                <a href="./Email.php" title="Send emails to users">Email Users</a>
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

    <div class="container">
        <h1>Help & Documentation</h1>
        <p>Select a topic below to learn more:</p>
		
		<div class="tab" onclick="toggleContent(this)">Calculator</div>
            <div class="content">
                <p>The calculator widget allows all users to access a convenient accounting tool.</p>
				<p>It can be selected by clicking the calculator icon located on the right side of the screen and takes users to a pop up, fully functional calculator.</p>
				</div>
				
				<div class="tab" onclick="toggleContent(this)">Calendar</div>
            <div class="content">
                <p>The calender widget allows all users to access a convenient accounting tool.</p>
				<p>It can be selected by clicking the calender icon located on the right side of the screen and takes users to a pop up, fully functional calender set to the current date.</p>
				<p>Users can move forward or backward in time by selecting a specific date or changing the month,year,day fields within the widget.</p>
				</div>

        <div class="tab-container">
		<div class="tab" onclick="toggleContent(this)">Create User: <strong>Admin</strong></div>
            <div class="content">
                <p>The 'Create User' function is unique to Administrator within LLA.</p>
				<p>It is identical to the Create User form found on the login page of the application and allows A</p>
				<p>This page allows for the creation of new users within the application.</p>
				<p>Additionally, Users may use the filters provided to organize or isolate information they may need to see.</p>
				<p><strong> Note </strong>: Administrators must still approve these accounts before they are active and allowed to login.</p>
				</div>
				
				<div class="tab" onclick="toggleContent(this)">View Users: <strong>Admin</strong></div>
				<div class="content">
                <p>Allows Administrators to see a list of all Users within the LLA system..</p>
				<p>Users are ordered according to account creation date newest, with various bits of information displayed about their accounts.</p>
				<p>Admins have the option update a user's account status through this page. This includes links to the 'Update User' page detailed below.</p>
				<p>For all users, any promotions or account changes must be done through update users. This includes deactivating accounts.
				</div>
		
		<div class="tab" onclick="toggleContent(this)">Account Approval</div>
            <div class="content">
                <p>The Account Approval page is an Admin exclusive feature.</p>
				<p>This page stores a list of recently registered users along with details about their current activity status, and pending approvals.</p>
				<p>Account Name and Number are clickable, both leading to the ledger transactions for a given account, along with a list of recent transactions.</p>
				<p>Administrators can approve or revoke approval by clicking the check box under the "Approved" Tab.</p>
				<p><strong> Suspension </strong></p>
				<p>Accounts can be suspended using the button under the "Action" column.</p>
				<p>Suspension can be done for a set date range using the dropdown calender. Please follow your organization's practices regarding documentation for suspended users.</p>
				</div>

            <div class="tab" onclick="toggleContent(this)">Create Account: Admin</div>
            <div class="content">
                <p>The Create Account page allows users to add new Client accounts to the existing ledger.</p><p> This page can only be used with authorization and requires information about the account name
				number, description, type, category/subcategory, initial balance, account order, and the type of statements to be generated along with any commnents.</p>
				<p>It is imperative that any changes here are made with care in order to maintain the integrity of the existing journal.
				<p><strong>Select users have access to account creation</strong></p>
				</div>
	
		
            <div class="tab" onclick="toggleContent(this)">Chart of Accounts</div>
            <div class="content">
                <p>The Chart of Accounts provides a structured list of all financial accounts. The list is ordered  Use this page to create, edit, or view accounts.</p>
				<p>Accounts are formatted according to their name, identification number, account number, descriptions and categories/balances at the time of viewing.</p>
				<p>Account Name and Number are clickable, both leading to the ledger transactions for a given account, along with a list of recent transactions.</p>
				<p>Additionally, Users may use the filters provided to organize or isolate information they may need to see.</p>
				<p><strong> Administrators </strong>: Capable of sending emails to managers regarding changes in the chart of accounts.</p>
				<p><strong> Managers </strong>: Capable of viewing the states and balances of accounts within the ledger.</p>
				<p><strong> Accountants </strong>: Capable of viewing the states and balances of accounts within the ledger.</p>
				</div>

            <div class="tab" onclick="toggleContent(this)">Event Log</div>
            <div class="content">
                <p>The Event Log records system events, such as user actions and system changes, for tracking and troubleshooting. It aims to provide a comprehensive list of all changes made throughout the system.</p>
				<p>Events are structured according to their unique event id, the information of the user who triggered the event, its time of occurrence, any pertinent information about accounts affected or changes made.</p> 
				<p>Each column can be filtered according to the specific information a user wishes to see.</p>
				<p><strong>All users have access to identical event log functionality.</strong></p>
				</div>

			<div class="tab" onclick="toggleContent(this)">Deactivate Accounts</div>
            <div class="content">
                <p>Account Deactivation is an Admin exclusive feature.</p>
				<p>This page is identical to the chart of accounts but also a list of various other accounts such as accounts payable/recievable.</p>
				<p>Admins can utilize the rightmost "Action" column to make changes to a particular account, including deletion</p>
				<p>Additionally, they may view the ledger and take stock of any necessary items.
				
				<p><strong> Admins are advised to take extreme caution when deleting accounts from the Chart of Accounts.</strong></p>
				</div>

            <div class="tab" onclick="toggleContent(this)">Journalize</div>
            <div class="content">
                <p>The Journalize page allows users to add new Journal Entries for approval.</p><p> This page is intended to record all new entries for the existing accounts and must recieve manager approval before any changes are reflected.</p>
				<p><strong>All users (excl. Admin) may journalize but all entries must be approved by a manager.</strong></p>
            </div>
			
			<div class="tab" onclick="toggleContent(this)">View Journal Entries</div>
            <div class="content">
                <p>The View Journal Entries page is intended for managers.</p>
				<p>All pending journal entries and previously approved or denied entries can be viewed from this page.</p>
				<p>Additionally, managers can take action and approve or deny pending entries by using associated action buttons.</p>
				</div>

            <div class="tab" onclick="toggleContent(this)">Expired Passwords Report</div>
            <div class="content">
                <p>Shows a list of expired passwords and the users associated with them. Additionally, the page includes the date of expiration and the user's recorded login information.</p>
				<p><strong>Passwords are hashed for security purposes and can only be unhashed using the provided tools within the installation</strong></p>
				</div>
	
		
            <div class="tab" onclick="toggleContent(this)">Balance Sheet</div>
            <div class="content">
                    <p> Contains a snapshot of the company's financial position at the selected point in time.</p>
					<p> This sheet shows all available assets, as well as owner's equity and liability, with totals displayed for each amount.</p>
                    <p><strong>This sheet can be stored or printed as needed using the available buttons below</strong></p>
            </div>
			
			 <div class="tab" onclick="toggleContent(this)">Income Statement</div>
            <div class="content">
                    <p> Also known as a Proft and Loss Statement, this sheet reports the company’s financial performance over a specific period.</p> 
					<p>It shows revenues, expenses, and the resulting net income or loss. </p>
                    <p><strong>This sheet can be stored or printed as needed using the available buttons below</strong></p>
            </div>
			
			<div class="tab" onclick="toggleContent(this)">Email Users</div>
            <div class="content">
                <p>The page shows a list of users and all registered email addresses.</p>
				<p>Users can be emailed by either entering their credentials in the 'User Email' field or by clicking the hyperlinked email address stored to the account.</p>
				<p>Once a comment is written and the 'Send Email' button is clicked, the designated address is forwarded a company email.</p>
				</div>
				
	
			 <div class="tab" onclick="toggleContent(this)">FAQ</div>
            <div class="content">
                <p>
                    <strong><p>How do I create a new user account?</strong></p> Go to "User Management" -> "Create User" and fill out the form.
                    <p><strong>How do I approve a pending account?</p></strong> Navigate to "Account Approval" under "User Management."
                </p>
            </div>
        </div>
    </div>

    <script>
        function toggleContent(tab) {
            const tabs = document.querySelectorAll('.tab');
            const contents = document.querySelectorAll('.content');

            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('show'));

            tab.classList.add('active');
            tab.nextElementSibling.classList.add('show');
        }
    </script>
</body>
</html>
