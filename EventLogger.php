<?php
function eventLogger($userId, $userAcctType, $acctAffected, $beforeAffected, $afterAffected, $status) {
    // Establish connection
    $conn = new mysqli('localhost', 'root', 'root', 'accounting_db', 8889);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
 
 
 //Param Binding
    $in = $conn->prepare("INSERT INTO user_eventlog (AutoID, UserID, UserAcctType, AcctAffected, BeforeAffected, AfterAffected, Status, DateANDTime) 
                            VALUES (UUID(), ?, ?, ?, ?, ?, ?, NOW())");
							
    // Bind parameters
    $in->bind_param('ssssss', $userId,  $userAcctType, $acctAffected, $beforeAffected, $afterAffected, $status);


    // Execute the statement
    if ($in->execute()) {
        echo "Event logged successfully.";
    } else {
        die("Error inserting data.".$conn->error);
    }

    // Close the connection
	$in->close();
    $conn->close();
}
?>