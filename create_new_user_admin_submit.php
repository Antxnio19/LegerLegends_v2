<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = mysqli_connect("localhost", "root", "root", "accounting_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the selected user role
    $userTypeId = $_POST['UserTypeId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $securityQuestion = $_POST['securityQuestion'];
    $securityAnswer = $_POST['securityAnswer'];

    // Prepare the SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO EmployeeAccounts (UserTypeId, Username, Password, EmailAddress, DateOfBirth, FirstName, LastName, Address, SecurityQuestions, SecurityAnswers) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param('ssssssssss', $userTypeId, $username, $password, $email, $dob, $firstName, $lastName, $address, $securityQuestion, $securityAnswer);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "New user created successfully. User ID: " . $stmt->insert_id; // Retrieve the last inserted ID
    } else {
        $message = "Error: " . $stmt->error;
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
    <title>Redirecting...</title>
    <script>
        console.log("<?php echo addslashes($message); ?>"); // Log the message to the console
        window.location.href = './administrator_home.php'; // Redirect to home page
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>