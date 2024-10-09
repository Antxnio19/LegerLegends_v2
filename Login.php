<?php
// Start session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the event logger function
include 'eventLogger.php';

// Create connection
$conn = new mysqli("localhost", "root", "root", "accounting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input from POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to fetch user data
    $stmt = $conn->prepare("SELECT UserID, Username, Password, UserTypeId FROM Table1 WHERE Username = ? AND IsActive = 1");
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param('s', $username);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $hashedPassword = $row['Password'];
        $userId = $row['UserID'];
        $userTypeId = $row['UserTypeId']; // Assuming this stores user role

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Successful login
            $_SESSION['username'] = $username;
            $_SESSION['userId'] = $userId;

            // Log successful login
            eventLogger($userId, $userTypeId, "Login Success");

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password
            $error_message = "Invalid username or password.";

            // Log failed login attempt
            eventLogger(null, $userTypeId, "Login Failed - Incorrect Password");
        }
    } else {
        // No user found with the given username
        $error_message = "Invalid username or password.";

        // Log failed login attempt
        eventLogger(null, "Unknown", "Login Failed - Username Not Found");
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Display error message if there is one -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message" style="color: red; text-align: center;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
