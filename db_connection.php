<?php
$servername = "localhost"; // should be fine
$username = "root"; // whatever your username is for your myPHPadmin login/ mySQL
$password = ""; // whatever your password is for your myPHPadmin login/ mySQL
// all of these are default
$dbname = "ledgerledgends"; // This can be found at http://localhost/phpmyadmin/index.php?route=/database/structure&db=ledgerledgends so whatever the db you're using is = to goes here
// you may need to change this

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
