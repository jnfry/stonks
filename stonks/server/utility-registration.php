<?php
include_once("server-utility.php");

function validateUsername($username, $dbConn) {
    if (strlen($username) < 3 || strlen($username) > 32) {
        // username was too short
        return json_encode(array(
            "result" => "invalid",
            "field" => "username",
            "msg" => "Username must be between 3 and 32 characters."

        ));
        
    } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $username)) {
        // username has bad chars
        return json_encode(array(
            "result" => "invalid",
            "field" => "username",
            "msg" => "Username can only contain letters, numbers, and spaces."
        ));

    } else if (usernameExists($username, $dbConn)) {
        // username already exists
        return json_encode(array(
            "result" => "invalid",
            "field" => "username",
            "msg" => "Username is already taken."
        ));

    } else {
        // valid username
        return json_encode(array(
            "result" => "valid",
            "field" => "username",
            "msg" => "Valid username!"
        ));

    }
}

function validateEmail($email, $dbConn) {
    if (emailExists($email, $dbConn)) {
        return json_encode(array(
            "result" => "invalid",
            "field" => "email",
            "msg" => "Email address is already registered."
        ));

    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return json_encode(array(
            "result" => "invalid",
            "field" => "email",
            "msg" => "Invalid email address."
        ));

    } else {
        return json_encode(array(
            "result" => "valid",
            "field" => "email",
            "msg" => "Valid email address!"
        ));
    }
}

function validatePassword($password, $dbConn) {
    if (strlen($password) < 6) {
        return json_encode(array(
            "result" => "invalid",
            "field" => "password",
            "msg" => "Password must be at least 6 characters."
        ));

    } else {
        return json_encode(array(
            "result" => "valid",
            "field" => "password",
            "msg" => "Password is valid!"
        ));
    }
}

function validateConfirmPassword($password, $confirmPassword, $dbConn) {
    if ($password !== $confirmPassword) {
        return json_encode(array(
            "result" => "invalid",
            "field" => "confirmPassword",
            "msg" => "Passwords do not match."
        ));

    } else {
        return json_encode(array(
            "result" => "valid",
            "field" => "confirmPassword",
            "msg" => "Passwords match!"
        ));
    }
}

?>