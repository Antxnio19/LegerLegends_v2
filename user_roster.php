<?php
// Include the database connection file
include 'db_connection.php';

// Create a new mysqli connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch users from Table1
$sql = "SELECT Id, FirstName, LastName, Address, DateOfBirth, EmailAddress, Username, CreatedDate, Password, UserTypeId, IsActive FROM Table1";
$result = $conn->query($sql);

// Check if any users were found
if ($result->num_rows > 0) {
    // Output data of each row
    echo "<table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Date of Birth</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Date Created</th>
                    <th>Password</th>
                    <th>User Type ID</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['Id']}</td>
                <td>{$row['FirstName']}</td>
                <td>{$row['LastName']}</td>
                <td>{$row['Address']}</td>
                <td>{$row['DateOfBirth']}</td>
                <td>{$row['EmailAddress']}</td>
                <td>{$row['Username']}</td>
                <td>{$row['CreatedDate']}</td>
                <td>{$row['Password']}</td>
                <td>{$row['UserTypeId']}</td>
                <td>" . ($row['IsActive'] ? 'Yes' : 'No') . "</td>
                <td><button class='update-button' onclick='updateUser({$row['Id']})'>Update</button></td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "No users found.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Table</title>
    <link rel="stylesheet" href="./user_roster_stylesheet.css">
</head>
<body>
    <!-- (Navigation bar and other HTML content can be added here) -->

    <script>
        function updateUser(userId) {
            // Redirect to the update_user.php page with the user ID
            window.location.href = `update_user.php?user_id=${userId}`;
        }
    </script>
</body>
</html>

