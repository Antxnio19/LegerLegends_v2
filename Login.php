<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include './EventLogger.php';

$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT Id, Password, FailedAttempts, LockoutUntil, UserTypeId, IsActive FROM Table1 WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) { 
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $userId = $user['Id']; 
            $hashedPassword = $user['Password'];
            $failedAttempts = $user['FailedAttempts'];
            $lockoutUntil = $user['LockoutUntil'];
            $userTypeId = $user['UserTypeId']; 
            $isActive = $user['IsActive']; 

            if ($isActive == 0) {
                $error_message = "Your account is inactive. Please contact support.";
            } 
            elseif ($failedAttempts >= 3 && strtotime($lockoutUntil) > time()) {
                $error_message = "Account is locked. Try again after " . date('Y-m-d H:i:s', strtotime($lockoutUntil));
            } 
            elseif (password_verify($password, $hashedPassword)) {

                $sql = "UPDATE Table1 SET FailedAttempts = 0, LockoutUntil = NULL WHERE Username = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) { 
                    $stmt->bind_param('s', $username);
                    $stmt->execute();
                }

                $_SESSION['username'] = $username;
                $_SESSION['Id'] = $userId;
                $_SESSION['UserTypeId'] = $userTypeId; 

                switch ($userTypeId) {
                    case 'Admin':
                        header('Location: Administrator_home.php');
                        eventLogger($userId, $userTypeId, null, null, null, "Login Success");
                        break;
                    case 'Accountant':
                        header('Location: Accountant_home.php');
                        eventLogger($userId, $userTypeId, null, null, null, "Login Success");
                        break;
                    case 'Manager':
                        header('Location: Manager_home.php');
                        eventLogger($userId, $userTypeId, null, null, null, "Login Success");
                        break;
                    default:
                        $error_message = "Invalid user type.";
                        break;
                }
            } 
            else {
                $failedAttempts++;
                $lockoutUntil = ($failedAttempts >= 3) ? date('Y-m-d H:i:s', strtotime('+1 day')) : NULL;

                $sql = "UPDATE Table1 SET FailedAttempts = ?, LockoutUntil = ? WHERE Username = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('iss', $failedAttempts, $lockoutUntil, $username);
                    $stmt->execute();
                }

                $error_message = "Sorry, you gave us the wrong information. Try again.";
            }
        } 
        else {
            $error_message = "Sorry, you gave us the wrong information. Try again.";
        }

        $stmt->close();
    } 
    else {
        $error_message = "Database query error.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legible Accounting - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="profile.png" alt="Legible Accounting">
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="error-message" style="color: red; text-align: center;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <div class="login-links">
                <button type="button" onclick="window.location.href='Forgot_password.php'">Forgot Password</button><br><br>
                <button type="button" onclick="window.location.href='Create_new_User.html'">Sign Up</button>
            </div>
        </form>
    </div>
</body>
</html>
