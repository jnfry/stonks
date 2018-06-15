<?php
include_once("secure-session.php");

startSecureSession();

$_SESSION = array();
session_destroy();
header("Location: ../page-login.php");
?>