<?php
// Include the database connection file
include 'db_connection.php';

// Create a new mysqli connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user ID is set in the URL parameters
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // Make sure to convert user_id to an integer

    // Query to fetch the updated user information from the correct table
    $sql = "SELECT * FROM Table1 WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user was found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "No user found.";
        exit();
    }

    // Close the statement
    $stmt->close();
} else {
    echo "User ID not specified.";
    exit();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Update Successful</title>
    <link rel="stylesheet" href="./user_roaster_stylesheet.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-link:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Information Updated Successfully!</h2>
        <p>Your changes have been saved. Here are the updated details:</p>

        <table>
            <tr>
                <th>User ID</th>
                <td><?php echo htmlspecialchars($user['Id']); ?></td>
            </tr>
            <tr>
                <th>First Name</th>
                <td><?php echo htmlspecialchars($user['FirstName']); ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo htmlspecialchars($user['LastName']); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($user['Address']); ?></td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td><?php echo htmlspecialchars($user['DateOfBirth']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['EmailAddress']); ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?php echo htmlspecialchars($user['Username']); ?></td>
            </tr>
            <tr>
                <th>Date Created</th>
                <td><?php echo htmlspecialchars($user['CreatedDate']); ?></td>
            </tr>
            <tr>
                <th>Position</th>
                <td><?php echo htmlspecialchars($user['Position']); ?></td>
            </tr>
            <tr>
                <th>Password Expiry Duration (Days)</th>
                <td><?php echo htmlspecialchars($user['ExpiryDuration']); ?></td>
            </tr>
        </table>

        <a href="./user_roster.php" class="back-link">Go Back to User Roster</a>
    </div>
</body>
</html>

