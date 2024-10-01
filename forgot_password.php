<?php
// Start session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$error_message = '';
$success_message = '';

// Create connection
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input from POST request
    $username = $_POST['username'];
    $email = $_POST['email'];
    $security_question = $_POST['securityQuestion'];
    $security_answer = $_POST['securityAnswer'];

    // Prepare the SQL statement to fetch user data
    $sql = "SELECT Password, EmailAddress FROM Table1 WHERE Username = ? AND EmailAddress = ? AND SecurityQuestions = ? AND SecurityAnswers = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param('ssss', $username, $email, $security_question, $security_answer);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the password (note: passwords should not be unhashed, this is for example purposes)
        $row = $result->fetch_assoc();
        $password = $row['Password'];

        // Send email
        $to = $email;
        $subject = "Your Password Recovery";
        $message = "Your password is: " . $password;
        $headers = "From: noreply@example.com";

        if (mail($to, $subject, $message, $headers)) {
            $success_message = "Password has been sent to your email.";
        } else {
            $error_message = "Error sending email.";
        }
    } else {
        $error_message = "No matching records found. Please check your details and try again.";
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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="profile.png" alt="Company Logo">
        </div>
        <h2>Forgot Password</h2>

        <!-- Display error message if there is one -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message" style="color: red; text-align: center;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Display success message if there is one -->
        <?php if (!empty($success_message)): ?>
            <div class="success-message" style="color: green; text-align: center;">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form id="forgotPasswordForm" action="forgot_password.php" method="POST">
            <input type="text" id="username" name="username" placeholder="Enter Username" required>
            <input type="email" id="email" name="email" placeholder="Enter Email" required>

            <select id="securityQuestion" name="securityQuestion" required>
                <option value="" disabled selected>Select a Security Question</option>
                <option value="q1">What was your first pet's name?</option>
                <option value="q2">What is your mother's maiden name?</option>
                <option value="q3">What was the make of your first car?</option>
                <option value="q4">What is your favorite book?</option>
                <option value="q5">What city were you born in?</option>
            </select>
            <input type="text" id="securityAnswer" name="securityAnswer" placeholder="Answer" required>

            <button type="submit">Submit</button>
        </form>
        <div class="login-links">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</body>
</html>
