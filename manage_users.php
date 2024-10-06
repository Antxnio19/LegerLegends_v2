<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Store the username from the session
$username = $_SESSION['username'];

// Database connection
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

// Fetch users from table1
$query = "SELECT Id, FirstName, LastName, Username, Password, IsActive, Approved FROM table1";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="./user_roaster_stylesheet.css">
    <style>
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
        .modal {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav>
        <div class="welcome">
            <img src="profile.png" alt="Picture" class="picture">
            <h1>Ledger Legend Administrator</h1>
        </div>
        <div class="user-profile">
            <img src="pfp.png" alt="User Picture" class="profile-pic">
            <span class="username"><?php echo htmlspecialchars($username); ?></span>
            <a href="./logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="main-bar">
        <!-- Home and IT Ticket as separate clickable links -->
        <a href="./administrator_home.php" class="nav-link">Home</a>
        <a href="./it_ticket.php" class="nav-link">IT Ticket</a>

        <!-- User Management dropdown -->
        <div class="dropdown">
            <button class="dropbtn">User Management</button>
            <div class="dropdown-content">
                <a href="./create_new_user_admin.php">Create User</a>
                <a href="./user_roster.php">View Users</a>
                <a href="./Manage_Users.php">Account Approval</a>
            </div>
        </div>

        <!-- Reports dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Reports</button>
            <div class="dropdown-content">
                <a href="#">User Report</a>
                <a href="./Expired_Passwords_Log.php">Expired Passwords Report</a>
                <a href="#">Login Attempts Report</a>
            </div>
        </div>

        <!-- Notifications dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Notifications</button>
            <div class="dropdown-content">
                <a href="">Password Expiration Alerts</a>
            </div>
        </div>

        <!-- Email Management dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Email Management</button>
            <div class="dropdown-content">
            <a href="./Email.php">Email Users</a>
            </div>
        </div>

        <!-- Settings dropdown -->
        <div class="dropdown">
            <button class="dropbtn">Settings</button>
            <div class="dropdown-content">
                <a href="#">System Settings</a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <h2>Manage Users</h2>

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
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['FirstName']) ?></td>
                            <td><?= htmlspecialchars($row['LastName']) ?></td>
                            <td><?= htmlspecialchars($row['Username']) ?></td>
                            <td><?= htmlspecialchars($row['Password']) ?></td>
                            <td><?= $row['IsActive'] ? 'Yes' : 'No' ?></td> <!-- Updated to show Yes only if IsActive is 1 -->
                            <td>
                                <input type="checkbox" class="approve-check" data-id="<?= $row['Id'] ?>" <?= $row['Approved'] ? 'checked' : '' ?> >
                            </td>
                            <td>
                                <button class="suspend-btn" data-id="<?= $row['Id'] ?>">Suspend</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No users found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Suspend User Modal -->
    <div id="suspend-modal" class="modal">
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
                fetch(`update_user_status.php?id=${userId}&approved=${isChecked}&isActive=${isChecked}`) // Pass isActive as well
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
                document.getElementById('suspend-modal').style.display = 'block';
            });
        });

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
                            statusCell.textContent = 'Suspended';
                            const checkBox = document.querySelector(`.suspend-btn[data-id='${currentUserId}']`).closest('tr').querySelector('.approve-check');
                            checkBox.checked = false;
                            checkBox.disabled = true;
                            document.getElementById('suspend-modal').style.display = 'none';
                        }
                    });
            } else {
                alert("Please select both start and end dates.");
            }
        });

        document.getElementById('cancel-suspend-btn').addEventListener('click', function () {
            document.getElementById('suspend-modal').style.display = 'none';
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
