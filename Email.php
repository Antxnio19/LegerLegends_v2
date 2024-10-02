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

// Handle form submission to send email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL);
    $userMessage = trim($_POST['userMessage']);

    if (!$userEmail || empty($userMessage)) {
        echo "<script>alert('Please fill out all fields.');</script>";
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

        // Check for success
        if ($httpCode == 202) {
            echo "<script>alert('Email sent successfully!');</script>";
        } else {
            echo "<script>alert('Failed to send email.');</script>";
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
    <link rel="stylesheet" href="./Email.css">
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
        <a href="./administrator_home.html" class="nav-link">Home</a>
        <a href="./it_ticket.html" class="nav-link">IT Ticket</a>

        <div class="dropdown">
            <button class="dropbtn">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.html">Create User</a>
                <a href="./user_roaster.html">View Users</a>
                <a href="./Manage_Users.html">Account Approval</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Reports</button>
            <div class="dropdown-content">
                <a href="#">User Report</a>
                <a href="#">Expired Passwords Report</a>
                <a href="#">Login Attempts Report</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Notifications</button>
            <div class="dropdown-content">
                <a href="#">Password Expiration Alerts</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Email Management</button>
            <div class="dropdown-content">
                <a href="#">Send Email</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Settings</button>
            <div class="dropdown-content">
                <a href="#">System Settings</a>
            </div>
        </div>
    </div>

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
                // Check if there are results
                if ($result->num_rows > 0) {
                    // Output data of each row
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

                // Close the connection
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
