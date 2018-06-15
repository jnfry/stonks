<?php
include_once("server-utility.php");
include_once("secure-session.php");
include_once("utility-wallets.php");
include_once("db-conn.php");

if (!isset($_SESSION)) {
    startSecureSession();
}

header("Content-Type: application/json");
echo(json_encode(getWallets($_SESSION["userId"], $dbConn)));

?>