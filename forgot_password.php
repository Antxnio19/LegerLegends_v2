<?php
// Start session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the event logger function
include 'eventLogger.php'; // Adjust the path as necessary

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
    $sql = "SELECT Id, Password, EmailAddress FROM Table1 WHERE Username = ? AND EmailAddress = ? AND SecurityQuestions = ? AND SecurityAnswers = ?";
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
        // Fetch the password
        $row = $result->fetch_assoc();
        $password = $row['Password'];
        $userId = $row['Id'];

        // SendGrid API key
        $apiKey = 'YOUR_SENDGRID_API_KEY'; // Ensure this is kept secure

        // Prepare the email data for SendGrid
        $data = [
            "personalizations" => [[
                "to" => [[
                    "email" => $email,
                ]],
                "subject" => "Your Password Recovery",
            ]],
            "from" => [
                "email" => "bportie1@students.kennesaw.edu", // Your verified email
                "name" => "Ledger Legends" // Your name
            ],
            "content" => [[
                "type" => "text/plain",
                "value" => "Your password is: " . $password
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

        // Check if the email was sent successfully
        if ($httpCode == 202) {
            // Email sent successfully
            $success_message = "Password has been sent to your email.";

            // Log the successful forgotten password attempt
            eventLogger($userId, "N/A", "Successful forgotten password attempt");
        } else {
            // Failed to send email
            $error_message = "Failed to send email.";
        }
    } else {
        // No matching records found
        $error_message = "No matching records found. Please check your details and try again.";
        
        // Log the failed password recovery attempt
        eventLogger(null, "N/A", "Failed forgotten password attempt - No matching records found");
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
