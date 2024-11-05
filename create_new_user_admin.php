<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];

// Database connection (replace with your actual connection details)
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $username = $_POST['generatedUsername'];  // Use the generated username
    $password = $_POST['password'];
    $security_question = $_POST['security_question'];
    $security_answer = $_POST['security_answer'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement for insertion, including IsActive set to FALSE
    $in = $conn->prepare("INSERT INTO Table1 (UserTypeId, Username, Password, EmailAddress, DateOfBirth, FirstName, LastName, Address, SecurityQuestions, SecurityAnswers, IsActive) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $userTypeId = "Accountant"; // Placeholder for now
    $isActive = 0; // 0 represents FALSE (inactive)
    $in->bind_param('ssssssssssi', $userTypeId, $username, $hashedPassword, $email, $dob, $first_name, $last_name, $address, $security_question, $security_answer, $isActive);

    // Execute the statement
    if ($in->execute()) {
        header('Location: administrator_home.php');  // Redirect to login page after successful insertion
        exit(); // Add exit to stop script execution after redirection
    } else {
        die("Error inserting data: " . $conn->error);
    }

    // Close the connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User</title>
    <link rel="stylesheet" href="styles.css">
    <script src="New_Password.js" defer></script>
</head>
    <div class="login-container">
        <div class="login-logo">
            <img src="profile.png" alt="Company Logo">
        </div>
        <h2>Sign Up</h2>
        <form id="createUserForm" action="" method="POST">
            <input type="text" id="first_name" name="first_name" placeholder="First Name" required>
            <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>

            <!-- Username field is readonly, populated automatically -->
            <input type="text" id="username" name="username" placeholder="Username" readonly required>

            <!-- Hidden field to pass generated username -->
            <input type="hidden" id="generatedUsername" name="generatedUsername">

            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="date" id="dob" name="dob" required min="1920-01-01" max="2024-12-31">
            <input type="text" id="address" name="address" placeholder="Address" required>

            <input type="password" id="password" name="password" placeholder="Create Password" required>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>

            <!-- Password requirements display -->
            <div id="passwordRequirements">
                <p id="lengthRequirement" class="invalid">Minimum 8 characters</p>
                <p id="letterRequirement" class="invalid">Starts with a letter</p>
                <p id="numberRequirement" class="invalid">Contains a number</p>
                <p id="specialRequirement" class="invalid">Contains a special character</p>
            </div>

            <!-- Security question dropdown -->
            <select id="security_question" name="security_question" required>
                <option value="" disabled selected>Select a Security Question</option>
                <option value="q1">What was your first pet's name?</option>
                <option value="q2">What is your mother's maiden name?</option>
                <option value="q3">What was the make of your first car?</option>
                <option value="q4">What is your favorite book?</option>
                <option value="q5">What city were you born in?</option>
            </select>
            <input type="text" id="security_answer" name="security_answer" placeholder="Answer" required>

            <button type="submit">Submit Request</button>
        </form>
        <div class="login-links">
            <a href="administrator_home.php">Back to Home</a>
        </div>

        <!-- Feedback message for username generation -->
        <p id="usernameFeedback" class="username-generated" style="display:none;">Your username has been generated!</p>
    </div>
</body>
</html>
