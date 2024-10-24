<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];

// Database connection
$host = 'localhost'; // Database host
$user = 'root'; // Database username
$pass = 'root'; // Database password
$db = 'accounting_db'; // Database name

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch users from table1
$query = "SELECT Id, FirstName, LastName, Username, Password, IsActive, Approved FROM table1";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <link rel="stylesheet" href="./user_roaster_stylesheet.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .date-input {
            width: 120px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1 style="color: white;">Ledger Legend Administrator</h1>
        </div>
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span>
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

    <div class="main-content">
        <h2 style="color: white;">Manage Users</h2>

        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Active</th>
                    <th>Approved</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['FirstName']) ?></td>
                            <td><?= htmlspecialchars($row['LastName']) ?></td>
                            <td><?= htmlspecialchars($row['Username']) ?></td>
                            <td><?= htmlspecialchars($row['Password']) ?></td>
                            <td><?= $row['IsActive'] ? 'Yes' : 'No' ?></td> <!-- Updated to show Yes only if IsActive is 1 -->
                            <td>
                                <input type="checkbox" class="approve-check" data-id="<?= $row['Id'] ?>" <?= $row['Approved'] ? 'checked' : '' ?> >
                            </td>
                            <td>
                                <button class="suspend-btn" data-id="<?= $row['Id'] ?>">Suspend</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No users found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Suspend User Modal -->
    <div id="suspend-modal" class="modal">
        <h3>Suspend User</h3>
        <label for="start-date">Start Date:</label>
        <input type="date" id="start-date" class="date-input">
        <label for="end-date">End Date:</label>
        <input type="date" id="end-date" class="date-input">
        <button id="confirm-suspend-btn">Confirm Suspension</button>
        <button id="cancel-suspend-btn">Cancel</button>
    </div>

    <script>
        let currentUserId;

        document.querySelectorAll('.approve-check').forEach(check => {
            check.addEventListener('change', function () {
                const userId = this.getAttribute('data-id');
                const isChecked = this.checked ? 1 : 0;
                fetch(`update_user_status.php?id=${userId}&approved=${isChecked}&isActive=${isChecked}`) // Pass isActive as well
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const statusCell = this.closest('tr').querySelector('.active-status');
                            statusCell.textContent = isChecked ? 'Yes' : 'No';
                        }
                    });
            });
        });

        document.querySelectorAll('.suspend-btn').forEach(button => {
            button.addEventListener('click', function () {
                currentUserId = this.getAttribute('data-id');
                document.getElementById('suspend-modal').style.display = 'block';
            });
        });

        document.getElementById('confirm-suspend-btn').addEventListener('click', function () {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            if (startDate && endDate) {
                fetch(`suspend_user.php?id=${currentUserId}&startDate=${startDate}&endDate=${endDate}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("User suspended successfully.");
                            const statusCell = document.querySelector(`.suspend-btn[data-id='${currentUserId}']`).closest('tr').querySelector('.active-status');
                            statusCell.textContent = 'Suspended';
                            const checkBox = document.querySelector(`.suspend-btn[data-id='${currentUserId}']`).closest('tr').querySelector('.approve-check');
                            checkBox.checked = false;
                            checkBox.disabled = true;
                            document.getElementById('suspend-modal').style.display = 'none';
                        }
                    });
            } else {
                alert("Please select both start and end dates.");
            }
        });

        document.getElementById('cancel-suspend-btn').addEventListener('click', function () {
            document.getElementById('suspend-modal').style.display = 'none';
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
