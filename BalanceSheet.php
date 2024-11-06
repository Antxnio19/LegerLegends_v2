<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="BalanceSheet.css"> <!-- Link to Balance Sheet CSS file -->
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
            <h1 class="title">Ledger Legend Administrator</h1> 
        </div>
        <!-- Profile and logout section -->
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span> <!-- Display the dynamic username here -->
            <a href="./logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

<!-- Navigation Bar -->
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

        <!-- Buttons to open calculator and calendar -->
        <button id="calculatorBtn" class="nav-link" title="Open the calculator">Calculator</button>
        <button id="calendarBtn" class="nav-link" title="Open the calendar">Calendar</button>
        <a href="./help.php" class="nav-link help-btn" title="Get help and support">&#x2753;</a>
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

<div class="mainA">

    <!-- Balance Sheet Table -->
    <div class="balance-sheet">
 
 <h1 style="color: black; text-align: center;">Balance Sheet</h1>
	<h2 style="color: black; text-align: center;">As of 10/31/2024</h2>
        <table>
                <tr>
                    <th colspan = "7">Assets</th>
                </tr>
            
            <tbody>
                <tr>
                    <td text-align="left" colspan=7><strong>Current Assets<strong></td>
                </tr>
				<tr>
					<td></td>
					<td>Cash</td>
					<td></td>
					<td><strong>$</strong></td>
					<td>8,875</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>Accounts Recievable</td>
					<td></td>
					<td></td>
					<td>3,450</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>Prepaid Rent</td>
					<td></td>
					<td></td>
					<td>3,000</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>Prepaid Insurance</td>
					<td></td>
					<td></td>
					<td>1,650</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>Supplies</td>
					<td></td>
					<td></td>
					<td>1,020</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					
					<td><strong>Total Current Assets<strong></td>
				<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><strong>$</strong></td>
					<td><strong>17,995</strong></td>
				</tr>
				<tr>
					<td></td>
					<td>Property Plant & Equipment</td>
					
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>Office Equipment</td>
					<td></td>
					<td></td>
					<td>9,300</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>Accumulated Depreciation</td>
					<td></td>
					<td></td>
					<td>(500)</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>Property Plant & Equipment, Net</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>8,800</td>
				</tr>
				<tr>
					<td><strong>Total Assets<strong></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><strong>26,795</strong></td>
				</tr>
				
              
				<tr>
                    <th colspan="7">Liabilities & Stockholders' Equity</th>
                </tr>
				
				<tr>
					<td colspan = "7">Current Liabilities</td>
				
				</tr>
				<tr>
					<td></td>
					
					<td>Accounts Payable</td>
					<td></td>
					<td></td>
					<td>1,000</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					
					<td>Salaries Payable</td>
					<td></td>
					<td><strong>$</strong></td>
					<td>20</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>Total Current Liabilities</td>
					
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>1,020</td>
				</tr>
				<tr>
				<td></td>
					<td>Unearned Revenue</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					
					<td>1,000</td>
				</tr>
				<tr>
					<td><strong>Total Liabilities<strong></td>
				
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><strong>2,020</strong></td>
				</tr>
				<tr></tr>
				<tr>
					<th colspan="7">Stockholders' Equity</th>
				</tr>
               <tr>
				<td></td>
				<td>Contributed Capital</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>20,250</td>
				</tr>
				 <tr>
				<td></td>
				<td>Retained Earnings</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>4,525</td>
				</tr>
				<tr>
					<td><strong>Total Stockholders' Equity<strong></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					
					<td>24,775</td>
				</tr>
				<tr></tr>
				<tr><th colspan ="6">Total Liabilities & Stockholder's Equity</th>
				<td><strong>$ 26,795</strong></td>
				
            </tbody>
        </table>
    </div>

    <script src="modals_calculator_calendar.js"></script>
	
	

</body>
</html>