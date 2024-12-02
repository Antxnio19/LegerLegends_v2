<?php
function eventLogger($userId, $userAcctType, $acctAffected, $beforeAffected, $afterAffected, $status) {
    $conn = new mysqli('localhost', 'root', 'root', 'accounting_db', 8889);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $in = $conn->prepare("INSERT INTO user_eventlog (AutoID, UserID, UserAcctType, AcctAffected, BeforeAffected, AfterAffected, Status, DateANDTime) 
                            VALUES (UUID(), ?, ?, ?, ?, ?, ?, NOW())");
							

    $in->bind_param('ssssss', $userId,  $userAcctType, $acctAffected, $beforeAffected, $afterAffected, $status);

    if ($in->execute()) {
        echo "Event logged successfully.";
    } else {
        die("Error inserting data.".$conn->error);
    }

	$in->close();
    $conn->close();
}
?>