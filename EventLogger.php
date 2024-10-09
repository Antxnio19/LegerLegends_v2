<?php
function eventLogger($userId, $eventType, $message) {
    // Establish connection
    $conn = new mysqli('localhost', 'root', 'root', 'accounting_db');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement for inserting event log
    $stmt = $conn->prepare("INSERT INTO user_eventlog (Auto_ID, UserID, UserAcctType, AcctAffected, STATUS, CreatedDate) 
                            VALUES (UUID(), ?, ?, ?, ?, NOW())");
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param('isss', $userId, $eventType, $userId, $message); 

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
