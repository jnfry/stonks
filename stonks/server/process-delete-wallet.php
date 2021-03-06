<?php
include_once("db-conn.php");
include_once("utility-wallets.php");
include_once("secure-session.php");

if (!isset($_SESSION)) {
    startSecureSession();
}

header("Content-Type: application/json");
if (isset($_POST["delete"])) {
    if (deleteWallet($_POST["delete"], $_SESSION["userId"], $dbConn)) {
        echo(json_encode(array(
            "result" => "success"
        )));
    } else {
        echo(json_encode(array(
            "result" => "failed"
        )));
    }
} else {
    echo(json_encode(array(
        "result" => "error",
        "msg" => "Bad request"
    )));
}

?>