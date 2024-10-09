<?php
function eventLogger($userId, $userAcctType, $acctAffected, $status, $beforeAffected, $afterAffected) {
    // Establish connection
    $conn = new mysqli('localhost', 'root', 'root', 'accounting_db');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement for inserting event log
    $stmt = $conn->prepare("INSERT INTO user_eventlog (AutoID, UserID, UserAcctType, AcctAffected, BeforeAffected, AfterAffected, STATUS, DateANDTime) 
                            VALUES (UUID(), ?, ?, ?, ?, ?, ?, NOW())");
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param('isssss', $userId, $userAcctType, $acctAffected, $beforeAffected, $afterAffected, $status); 

    // Execute the statement
    if ($stmt->execute()) {
        echo "Event logged successfully.";
    } else {
        echo "Error logging event: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
