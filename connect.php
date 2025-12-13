<?php
$DB_HOST = getenv("MYSQLHOST");
$DB_USER = getenv("MYSQLUSER");
$DB_PASS = getenv("MYSQLPASSWORD");
$DB_NAME = getenv("MYSQLDATABASE");
$DB_PORT = getenv("MYSQLPORT");

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($conn->connect_errno) {
    die(
        "<b>Database Connection Failed</b><br>" .
        "Error: " . $conn->connect_error . "<br>" .
        "Code: " . $conn->connect_errno
    );
}
?>
