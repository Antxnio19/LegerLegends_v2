<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="icon" type="image/png" href="profile.png">
    <title>Ledger Legend Administrator Page</title>
    <style>
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 300px; 
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Calculator styles */
        .calculator {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .calculator input {
            margin: 10px;
            width: 100px;
            text-align: right;
            font-size: 24px;
            padding: 5px;
        }
        .calculator-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            width: 200px; /* Set the width for calculator buttons */
        }
        .calculator button {
            width: 100%;
            height: 40px; /* Standard button height */
            font-size: 18px;
        }

        /* Calendar styles */
        #calendar {
            display: flex;
            flex-direction: column; /* Stack month header and table */
            align-items: center; /* Center align */
        }
        table {
            max-width: 100%; /* Prevent overflow */
            border-collapse: collapse; /* Collapse borders */
        }
        th, td {
            width: 30px; /* Set a fixed width for each cell */
            height: 30px; /* Set a fixed height for each cell */
            text-align: center; /* Center text */
            border: 1px solid #ccc; /* Add a border */
        }
        .month-header {
            margin-bottom: 10px; /* Space between header and calendar */
            font-size: 20px; /* Increase font size for month */
        }
        .day-names {
            font-weight: bold; /* Make day names bold */
        }
    </style>
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

    <script>
        // Calculator functionality
        function appendToDisplay(value) {
            document.getElementById('calcDisplay').value += value;
        }

        function calculateResult() {
            const display = document.getElementById('calcDisplay');
            try {
                display.value = eval(display.value);
            } catch (e) {
                display.value = 'Error';
            }
        }

        function clearDisplay() {
            document.getElementById('calcDisplay').value = '';
        }

        // Simple calendar display
        function displaySimpleCalendar() {
            const calendarDiv = document.getElementById('calendar');
            const date = new Date();
            const month = date.getMonth();
            const year = date.getFullYear();

            // Get the number of days in the month
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDay = new Date(year, month, 1).getDay();

            let calendarHtml = `<div class="month-header">${date.toLocaleString('default', { month: 'long' })} ${year}</div>`;
            calendarHtml += `<div class="day-names">
                                <span>Sun</span>
                                <span>Mon</span>
                                <span>Tue</span>
                                <span>Wed</span>
                                <span>Thu</span>
                                <span>Fri</span>
                                <span>Sat</span>
                              </div>`;
            calendarHtml += '<table><tr>';

            // Empty slots for days before the first day
            for (let i = 0; i < firstDay; i++) {
                calendarHtml += '<td></td>';
            }

            // Days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                calendarHtml += `<td>${day}</td>`;
                if ((day + firstDay) % 7 === 0) {
                    calendarHtml += '</tr><tr>'; // New row after every week
                }
            }

            calendarHtml += '</tr></table>';
            calendarDiv.innerHTML = calendarHtml;
        }

        // Modal management
        const calculatorBtn = document.getElementById('calculatorBtn');
        const calendarBtn = document.getElementById('calendarBtn');
        const calculatorModal = document.getElementById('calculatorModal');
        const calendarModal = document.getElementById('calendarModal');
        const closeCalculator = document.getElementById('closeCalculator');
        const closeCalendar = document.getElementById('closeCalendar');

        calculatorBtn.onclick = function() {
            clearDisplay(); // Clear calculator display when opening
            calculatorModal.style.display = "block";
        }

        calendarBtn.onclick = function() {
            displaySimpleCalendar(); // Display the calendar
            calendarModal.style.display = "block";
        }

        closeCalculator.onclick = function() {
            calculatorModal.style.display = "none";
        }

        closeCalendar.onclick = function() {
            calendarModal.style.display = "none";
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == calculatorModal) {
                calculatorModal.style.display = "none";
            } else if (event.target == calendarModal) {
                calendarModal.style.display = "none";
            }
        }
    </script>
</body>
</html>
