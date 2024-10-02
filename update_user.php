<?php
// Include your database connection file
include('db_connection.php');

// Check if user_id is provided in the URL
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // Ensure user_id is an integer

    // Prepare a statement to fetch user data
    $stmt = $conn->prepare("SELECT * FROM Table1 WHERE Id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
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
            <h1>Ledger Legend Administrator</h1>
        </div>
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username">Jtrejo0924</span>
            <a href="./logout.html" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="main-bar">
        <a href="./administrator_home.html" class="nav-link">Home</a>
        <a href="./it_ticket.html" class="nav-link">IT Ticket</a>
        <div class="dropdown">
            <button class="dropbtn">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.html">Create User</a>
                <a href="./user_roaster.php">View Users</a>
                <a href="#">Account Approval</a>
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
        <div class="form-container">
            <h2>Update User Information</h2>
            <form action="./submit_update.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['Id']); ?>"> <!-- Hidden field for user ID -->
                <p>User ID: <?php echo htmlspecialchars($user['Id']); ?></p> <!-- Displaying user ID -->

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

                <label for="date-created">Date Created:</label>
                <input type="date" id="date-created" name="date-created" value="<?php echo htmlspecialchars($user['CreatedDate'] ?? ''); ?>" readonly><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['Password'] ?? ''); ?>" required><br>

                <label for="position">Position:</label>
                <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($user['Position'] ?? ''); ?>" required><br>

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





