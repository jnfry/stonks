<?php
include_once("server-utility.php");
include_once("utility-registration.php");
include_once("db-conn.php");


if (isset($_POST["validate"])) {
    // Validate a single field
    header("Content-Type: application/json");
    echo(validate($_POST["validate"], $dbConn));

} else if (isset($_POST["username"], $_POST["email"], $_POST["password"], $_POST["confirmPassword"])) {
    // Validate a form submit
    $validation = array(
        "username" => validateUsername($_POST["username"], $dbConn),
        "email" => validateEmail($_POST["email"], $dbConn),
        "password" => validatePassword($_POST["password"], $dbConn),
        "confirmPassword" => validateConfirmPassword($_POST["password"], $_POST["confirmPassword"], $dbConn)
    );

    // check that all fields are valid
    foreach ($validation as $field) {
        $field = json_decode($field, true);
        if ($field["result"] == "invalid") {
            header("Content-Type: application/json");
            $validation["result"] = "invalid";
            echo(json_encode($validation));
            return;
        }
    }

    if (!register($_POST["username"], $_POST["email"], $_POST["password"], $dbConn)) {
        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/stonks/error.php?error=failed to register user");
    } else {
        header("Content-Type: application/json");
        echo(json_encode(array("result" => "succeeded")));
    }

}

// Validate the given form field
function validate($obj, $dbConn) {
    $field = $obj["field"];
    $value = $obj["value"];
    
    if ($field == "username") {
        return validateUsername(strtolower($value), $dbConn);

    } else if ($field == "email") {
        return validateEmail(strtolower($value), $dbConn);

    } else if ($field == "password") {
        return validatePassword($value, $dbConn);

    } else if ($field == "confirmPassword") {
        return validateConfirmPassword($value, $obj["value2"], $dbConn);

    } 
}

// Add this new user to database
function register($username, $email, $password, $dbConn) {
    if (!($sqlStmnt = $dbConn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)"))) {
        die("Bad statement");
    }

    $emailLower = strtolower($email);

    // passwords hashed with php standard / default
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sqlStmnt->bind_param('sss', $username, $emailLower, $hashedPassword);
    return $sqlStmnt->execute();
}

?>