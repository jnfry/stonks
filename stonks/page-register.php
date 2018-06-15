<?php
include_once("server/utility-registration.php");
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
    <title>Stonks Register</title>
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
                    <form id="registerForm" name="registerForm" accept-charset"UTF-8">
                        
                        <div class="form-group">
                            <label>Username</label>
                            <input class="form-control" id="username" name="username" type="text" aria-describedby="usernameHelp" placeholder="Pick a username" autofocus>
                            
                            <div class="valid-feedback username">
                            </div>
                
                            <div class="invalid-feedback username">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Email address</label>
                            <input class="form-control" id="email" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter your email"> 
                            
                            <div class="valid-feedback email">
                            </div>
                
                            <div class="invalid-feedback email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" id="password" name="password" type="password"  placeholder="Password">

                            <div class="valid-feedback password">
                            </div>
                
                            <div class="invalid-feedback password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Confirm password</label>
                            <input class="form-control" id="confirmPassword" name="confirmPassword" type="password"  placeholder="Confirm password">

                            <div class="valid-feedback confirmPassword">
                            </div>
                
                            <div class="invalid-feedback confirmPassword">
                            </div>
                        </div>

                        <input id="submit" type="submit" class="btn btn-primary btn-block m-b-30 m-t-30" value="Register"/>
                        
                        <div class="register-link text-center">
                            <p>Already registered?<a href="page-login.php"> Sign in here!</a></p>
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

        // Update the validation status based on json object
        function updateFormValidation(obj) {
            if (obj.result == "valid") {
                $("#" + obj.field).removeClass("is-invalid").addClass("is-valid");
                // Decided to leave out valid messages,
                // the green boxes are enough, messages provide too much clutter.

            } else {
                $(".invalid-feedback." + obj.field).html(obj.msg);
                $("#" + obj.field).removeClass("is-valid").addClass("is-invalid");

            }
        }

        function validateField(field, value, value2 = false) {
            var data = { "validate": { "field":field, "value":value, "value2":value2 }};

            $.ajax({  url: "server/process-register.php",
                    data: data,
                    type: "post",
                    datatype: "json",
                    success: function(result) {
                        updateFormValidation(result);
                    }
            });
        }

        $(function () {
            var timeout;

            $("input").on("keyup", function (e) {

                timeout = setTimeout(function() { 
                    // Must validate both password fields at once, so check that here.
                    if (e.target.id === "password" || e.target.id === "confirmPassword") {
                        validateField("password", $("#password").val());
                        validateField("confirmPassword", $("#password").val(), $("#confirmPassword").val());
                    } else {
                        validateField(e.target.id, e.target.value); 
                    }
                    
                }, 1000);
            });

            $("input").on("keydown", function (e) {
                clearTimeout(timeout);
            });
        });

        $(function () {
            $("#registerForm").submit(function (e) {
                e.preventDefault();

                $.ajax({  url: "server/process-register.php",
                        data: $("#registerForm").serialize(),
                        type: "post",
                        datatype: "json",
                        success: function(result) {
                            if (result.result === "invalid") {
                                // Update the form if something slipped through                      
                                updateFormValidation(JSON.parse(result.username));
                                updateFormValidation(JSON.parse(result.email));
                                updateFormValidation(JSON.parse(result.password));
                                updateFormValidation(JSON.parse(result.confirmPassword));

                            } else if (result.result === "succeeded") {
                                // change this to a confirmation page
                                window.location = "./";

                            }

                        }
                });
            });
        });
    });



    
    </script>


</body>
</html>
