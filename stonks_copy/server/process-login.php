<?php
include_once("server-utility.php");
include_once("db-conn.php");
// start session on every new page you dingus..

if (isset($_POST["email"], $_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (emailExists($email, $dbConn)) {
        
        // email exists, attempt the login
        if (login($email, $password, $dbConn)) {
            if (isset($_POST["remember"])) {
                // Issue session cookies etc.
            }

            
            
            // Echoing plaintext here was being buggy..
            header("Content-Type: application/json");
            echo(json_encode(array("result" => "success", "userId" => $_SESSION["userId"])));

        } else {
            //header('Location: ../login.php?error=password');
            header("Content-Type: application/json");
            echo(json_encode(
                array(
                    "result" => "failed",
                    "error" => "password"
                )
            ));
        }

    } else {
        header("Content-Type: application/json");
        echo(json_encode(
            array(
                "result" => "failed",
                "error" => "email"
            )
        ));
    }
}
?>


