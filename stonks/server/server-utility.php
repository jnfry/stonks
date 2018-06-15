<?php
include_once("secure-session.php");

// Check if email is already registered
function emailExists($email, $dbConn) {
    $sqlStmnt = $dbConn->prepare("SELECT email FROM user WHERE email = ? LIMIT 1");

    $emailLower = strtolower($email);

    $sqlStmnt->bind_param("s", $emailLower);
    $sqlStmnt->execute();
    $sqlStmnt->store_result();

    if ($sqlStmnt->num_rows == 1) {
        return true;
    }
    else {
        return false;
    }
    return false;
}

// Check if this username is already registered
function usernameExists($username, $dbConn) {
    $sqlStmnt = $dbConn->prepare("SELECT username FROM user WHERE username = ? LIMIT 1");
    $sqlStmnt->bind_param("s", $username);
    $sqlStmnt->execute();
    $sqlStmnt->store_result();

    if ($sqlStmnt->num_rows == 1) {
        return true;
    }
    else {
        return false;
    }
}

// Check if a user is logged in
function isLoggedIn() {
    if (!isset($_SESSION)) {
        startSecureSession();
    }

    if (isset($_SESSION["userId"])) {
        return true;

    } else {
        return false;

    }
}

// Log a user in
function login($email, $password, $dbConn) {
    if (!isset($_SESSION)) {
        startSecureSession();
    }
    
    if (!($sqlStmnt = $dbConn->prepare("SELECT id, username, password FROM user WHERE email = ? LIMIT 1"))) {
        die("Bad statement");
    }

    $emailLower = strtolower($email);

    $sqlStmnt->bind_param("s", $emailLower);
    $sqlStmnt->execute();
    $sqlStmnt->store_result();
    $sqlStmnt->bind_result($userId, $username, $hashedRealPassword);
    $sqlStmnt->fetch();

    if ($sqlStmnt->num_rows == 1) {
        if (password_verify($password, $hashedRealPassword)) {

            $_SESSION["userId"] = $userId;
            $_SESSION["username"] = $username;            


            return true;

        } else {
            return false;

        }
    }
}

?>

