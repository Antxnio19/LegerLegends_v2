<?php
include 'db_connection.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users
$query = "SELECT Id, FirstName, LastName, Username, Password, IsActive FROM Table1";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        /* Add some styles here for better presentation */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .date-input {
            width: 120px;
        }
    </style>
</head>
<body>

<h1>Manage Users</h1>

<table>
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Password</th>
            <th>Active</th>
            <th>Approved</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['FirstName']) ?></td>
                <td><?= htmlspecialchars($row['LastName']) ?></td>
                <td><?= htmlspecialchars($row['Username']) ?></td>
                <td><?= htmlspecialchars($row['Password']) ?></td>
                <td class="active-status"><?= $row['IsActive'] ? 'Yes' : 'No' ?></td>
                <td>
                    <input type="checkbox" class="approve-check" data-id="<?= $row['Id'] ?>" <?= $row['IsActive'] ? 'checked' : '' ?>>
                </td>
                <td>
                    <button class="suspend-btn" data-id="<?= $row['Id'] ?>">Suspend</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Modal for date picker -->
<div id="suspend-modal" style="display:none;">
    <h3>Suspend User</h3>
    <label for="start-date">Start Date:</label>
    <input type="date" id="start-date" class="date-input">
    <label for="end-date">End Date:</label>
    <input type="date" id="end-date" class="date-input">
    <button id="confirm-suspend-btn">Confirm Suspension</button>
    <button id="cancel-suspend-btn">Cancel</button>
</div>

<script>
let currentUserId;

document.querySelectorAll('.approve-check').forEach(check => {
    check.addEventListener('change', function () {
        const userId = this.getAttribute('data-id');
        const isChecked = this.checked ? 1 : 0;
        fetch(`update_user_status.php?id=${userId}&isActive=${isChecked}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusCell = this.closest('tr').querySelector('.active-status');
                    statusCell.textContent = isChecked ? 'Yes' : 'No';
                }
            });
    });
});

document.querySelectorAll('.suspend-btn').forEach(button => {
    button.addEventListener('click', function () {
        currentUserId = this.getAttribute('data-id');
        document.getElementById('suspend-modal').style.display = 'block'; // Show modal
    });
});

// Confirm suspension
document.getElementById('confirm-suspend-btn').addEventListener('click', function () {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
    if (startDate && endDate) {
        fetch(`suspend_user.php?id=${currentUserId}&startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("User suspended successfully.");
                    const statusCell = document.querySelector(`.suspend-btn[data-id='${currentUserId}']`).closest('tr').querySelector('.active-status');
                    statusCell.textContent = 'Suspended'; // Update UI to show suspended status
                    const checkBox = document.querySelector(`.suspend-btn[data-id='${currentUserId}']`).closest('tr').querySelector('.approve-check');
                    checkBox.checked = false; // Uncheck approved checkbox
                    checkBox.disabled = true; // Disable the checkbox
                    document.getElementById('suspend-modal').style.display = 'none'; // Hide modal
                }
            });
    } else {
        alert("Please select both start and end dates.");
    }
});

// Cancel suspension
document.getElementById('cancel-suspend-btn').addEventListener('click', function () {
    document.getElementById('suspend-modal').style.display = 'none'; // Hide modal
});
</script>

</body>
</html>

<?php
$conn->close();
?>








