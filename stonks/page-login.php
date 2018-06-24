<?php
include_once("server/server-utility.php");
include_once("server/secure-session.php");

startSecureSession();

if (isLoggedIn()) {
    header("Location: ./");
}

// Empty out POST
$_POST = array(); 
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Stonks Login</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="assets/scss/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>
<body class="bg-dark">


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="./">
                    <h1 class="display-4 text-light">STONKS DASHBOARD</h1>
                    </a>
                </div>
                <div class="login-form">
                    <form id="loginForm">
                        <div class="form-group" accept-charset"UTF-8">
                            <label>Email address</label>
                            <input class="form-control" id="email" name="email" type="email"  aria-describedby="emailHelp" placeholder="example@stonks.com" autofocus>
                            
                            <div class="invalid-feedback">
                                Email address is not registered!
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" id="password" name="password" type="password"  placeholder="Password">
                            <div class="invalid-feedback">
                                Incorrect Password!
                            </div>
                        </div>
                        <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="remember" name="remember"> Remember Me
                                </label>
                            </div>
                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>

                        <div class="register-link text-center">
                            <p>Don't have an account? <a href="page-register.php"> Register Here!</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>

    <script>

    jQuery(function($) {

        $(function () {
            $("#loginForm").submit(function (e) {
                e.preventDefault();

                $("#email").removeClass("is-invalid");
                $("#password").removeClass("is-invalid");

                $.ajax({    url: "server/process-login.php",
                            data: $("#loginForm").serialize(),
                            type: "post",
                            datatype: "json",
                            success: function(result) {
                                if (result.result === "success") {
                                    window.location = "./";

                                } else if (result.result === "failed") {
                                    $("#" + result.error).addClass("is-invalid");

                                }
                            }
                });
            });
        });

    });
        
    </script>


</body>
</html>
