<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];

// Initialize a variable to hold the message
$message = "";

// Handle form submission to send email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL);
    $userMessage = trim($_POST['userMessage']);

    if (!$userEmail || empty($userMessage)) {
        $message = "Please fill out all fields.";
    } else {
        $subject = "Message from Ledger Legend Accountant";

        // SendGrid API key
        $apiKey = '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';

        // Prepare the email data
        $data = [
            "personalizations" => [[
                "to" => [[
                    "email" => $userEmail,
                ]],
                "subject" => $subject,
            ]],
            "from" => [
                "email" => "bportie1@students.kennesaw.edu", // Your verified email
                "name" => "Ledger Legends" // Your name
            ],
            "content" => [[
                "type" => "text/plain",
                "value" => $userMessage
            ]],
        ];

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Send the email
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Check for success or failure and set the message
        if ($httpCode == 202) {
            $message = "Email sent successfully!";
        } else {
            $message = "Failed to send email.";
        }
    }
}

// Create connection to the database
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users from the database
$sql = "SELECT Username, FirstName, LastName, EmailAddress, DateOfBirth FROM Table1 WHERE IsActive = 1"; // Ensure to fetch only active users
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./administrator_stylesheet.css"> 
    <link rel="stylesheet" href="./Email.css">
    <link rel="stylesheet" href="./modals_calculator_calendar.css">
    <link rel="icon" type="image/png" href="profile.png">
    <title>Email</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .directEmail {
            margin-top: 20px;
        }
        .message {
            color: green;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1 style="color: white;">Ledger Legend Accountant</h1>
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
        <h2 align="center">Recent Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Date of Birth</th>
                </tr>
            </thead>
            <tbody id="userList">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='user-item' data-email='" . htmlspecialchars($row['EmailAddress']) . "'>";
                        echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['FirstName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['LastName']) . "</td>";
                        echo "<td><a href='mailto:" . htmlspecialchars($row['EmailAddress']) . "'>" . htmlspecialchars($row['EmailAddress']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['DateOfBirth']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>

        <div class="directEmail">
            <h3>Enter a user's email</h3>
            <form id="emailForm" method="POST" action="">
                <label for="userEmail">User Email:</label><br>
                <input type="email" id="userEmail" name="userEmail" required><br><br>
                <label for="userMessage">Message:</label><br>
                <textarea id="userMessage" name="userMessage" rows="4" cols="50" required></textarea><br><br>
                <input type="submit" value="Send Email">
            </form>

            <!-- Display the success/failure message here -->
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Inserts the selected user into the email field
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', function() {
                const userEmail = this.getAttribute('data-email');
                document.getElementById('userEmail').value = userEmail;
            });
        });
    </script>
</body>
</html>
