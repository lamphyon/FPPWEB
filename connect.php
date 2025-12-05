<?php
$DB_HOST = "127.0.0.1";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "rumahjamur";
$DB_PORT = 3306;

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($conn->connect_errno) {
    die("
        <b>Database Connection Failed</b><br>
        Error: " . $conn->connect_error . "<br>
        Code: " . $conn->connect_errno . "
    ");
}
?>