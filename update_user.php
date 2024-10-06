<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$host = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'accounting_db';
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $stmt = $conn->prepare("SELECT * FROM Table1 WHERE Id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("No user found with the specified ID.");
    }
} else {
    die("No user ID specified.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Information</title>
    <link rel="stylesheet" href="./user_roaster_stylesheet.css">
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1>Ledger Legends Administrator</h1>
        </div>
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span>
            <a href="./logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="main-bar">
        <!-- Home and IT Ticket as separate clickable links -->
        <a href="./administrator_home.php" class="nav-link">Home</a>
        <a href="./it_ticket.php" class="nav-link">IT Ticket</a>

        <!-- User Management dropdown -->
        <div class="dropdown">
            <button class="dropbtn">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php">Create User</a>
                <a href="./user_roster.php">View Users</a>
                <a href="./Manage_Users.php">Account Approval</a>
            </div>
        </div>

        <!-- Reports dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Reports</button>
            <div class="dropdown-content">
                <a href="#">User Report</a>
                <a href="./Expired_Passwords_Log.php">Expired Passwords Report</a>
                <a href="#">Login Attempts Report</a>
            </div>
        </div>

        <!-- Notifications dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Notifications</button>
            <div class="dropdown-content">
                <a href="">Password Expiration Alerts</a>
            </div>
        </div>

        <!-- Email Management dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Email Management</button>
            <div class="dropdown-content">
            <a href="./Email.php">Email Users</a>
            </div>
        </div>

        <!-- Settings dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Settings</button>
            <div class="dropdown-content">
                <a href="#">System Settings</a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="form-container">
            <h2>Update User Information</h2>
            <form action="./submit_update.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['Id']); ?>">
                <p>User ID: <?php echo htmlspecialchars($user['Id']); ?></p>

                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($user['FirstName'] ?? ''); ?>" required><br>

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($user['LastName'] ?? ''); ?>" required><br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['Address'] ?? ''); ?>" required><br>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['DateOfBirth'] ?? ''); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['EmailAddress'] ?? ''); ?>" required><br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['Username'] ?? ''); ?>" required><br>

                <label for="password">Password:</label>
                <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($user['Password'] ?? ''); ?>" required><br>

                <label for="user-type-id">Position:</label>
                <input type="text" id="user-type-id" name="user-type-id" value="<?php echo htmlspecialchars($user['UserTypeId'] ?? ''); ?>" required><br>

                <label for="expiry-duration">Password Expiry Duration (Days):</label>
                <input type="number" id="expiry-duration" name="expiry-duration" value="<?php echo htmlspecialchars($user['ExpiryDuration'] ?? ''); ?>" required><br>

                <button type="submit" class="submit-button">Submit</button>
            </form>
        </div>
    </div>

    <script>
        // Add JavaScript here if needed
    </script>
</body>
</html>

<?php
$conn->close();
?>
