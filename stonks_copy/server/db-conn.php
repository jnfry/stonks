<?php
include_once "db-cridentials.php";
$dbConn = new mysqli(HOST, USER, PW, DATABASE);

if ($dbConn->connect_error) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/stonks/error.php?error=failed to access database at this time");
}
?>