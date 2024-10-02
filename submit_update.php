<?php
// Include your database connection file
include('db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST data
    $user_id = intval($_POST['user_id']);

    // Get the other form data, ensuring proper handling of undefined keys
    $first_name = $_POST['first-name'] ?? '';
    $last_name = $_POST['last-name'] ?? '';
    $address = $_POST['address'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $position = $_POST['position'] ?? '';
    $expiry_duration = isset($_POST['expiry-duration']) ? intval($_POST['expiry-duration']) : 0;

    // Prepare a statement to update user data
    $stmt = $conn->prepare("UPDATE Table1 SET FirstName = ?, LastName = ?, Address = ?, DateOfBirth = ?, EmailAddress = ?, Username = ?, Password = ?, Position = ?, ExpiryDuration = ? WHERE Id = ?");
    
    // Ensure that you correctly bind the parameters according to their types
    $stmt->bind_param("ssssssssii", $first_name, $last_name, $address, $dob, $email, $username, $password, $position, $expiry_duration, $user_id);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Redirect or display success message
        header("Location: ./update_success.php?user_id=" . $user_id); 
        exit();
    } else {
        echo "Error updating record: " . $stmt->error; // Display error if the update fails
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>






