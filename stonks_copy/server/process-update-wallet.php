<?php
include_once("db-conn.php");
include_once("utility-wallets.php");
include_once("secure-session.php");

if (!isset($_SESSION)) {
    startSecureSession();
}

header("Content-Type: application/json");
if (isset($_POST["update"])) {
    
    echo(updateWallet($_POST["update"], $_SESSION["userId"], $dbConn));
} else {
    echo(json_encode(array(
        "result" => "error",
        "msg" => "Bad request"
    )));
}

?>