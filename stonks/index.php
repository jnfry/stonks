<?php
include_once("server/server-utility.php");
include_once("server/utility-wallets.php");
include_once("server/secure-session.php");
include_once("server/db-conn.php");

startSecureSession();

if (!isLoggedIn()) {
  header('Location: page-login.php');
}

?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Stonks</title>
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
    <link href="assets/css/lib/vector-map/jqvmap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/custom.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>
<body>


    <!-- Left Panel -->
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./"><h5 class="h5-responsive">Stonks</h5></a>
                <a class="navbar-brand hidden" href="./"><h5 class="h5-responsive">S</h5></a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="./"> <i class="menu-icon fa fa-user"></i><?php echo($_SESSION["username"]) ?> </a>
                    </li>
                    <div id="exchangeRates">
                    <!--
                    <li>
                        <a><span class=" text-light">BTC<span id="BTC-price" class="text-muted"></span></span></a>
                    </li>
                    <li>
                        <a><span class=" text-light">LTC<span id="LTC-price" class="text-muted"></span></span></a>
                    </li>
                    <li>
                        <a><span class=" text-light">DOGE<span id="DOGE-price" class="text-muted"></span></span></a>
                    </li>-->
                    </div>
                    <h3 class="menu-title">Wallets</h3>
                    <li>
                        <a id="newWalletBtn" href="#" data-toggle="modal" data-target="#newWalletModal"> <i class="menu-icon ti-plus"></i>Add </a>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-money"></i>Exchange Est.</a>
                        
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-dollar"></i>   <a id="AUD" class="exchangeBtn" href="#">AUD</a></li>
                            <li><i class="fa fa-dollar"></i>       <a id="USD" class="exchangeBtn" href="#">USD</a></li>
                        </ul>
                    </li>
                    <li>
                        <a id="refreshBtn" href=""> <i class="menu-icon fa fa-refresh"></i>Refresh </a>
                    </li>

                    <h3 class="menu-title">Account</h3>
                    <!--
                    <li>
                        <a href="#"> <i class="menu-icon ti-settings"></i>Settings </a>
                    </li>
                    -->
                    <li>
                        <a href="#" data-toggle="modal" data-target="#logoutModal"> <i class="menu-icon ti-shift-left"></i>Sign Out </a>
                    </li>

                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">
                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">
                        <button class="search-trigger"><i class="fa fa-search"></i></button>
                        <div class="form-inline">
                            <form class="search-form">
                                <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                                <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->

        <div class="breadcrumbs">
                <div class="page-header float-left col-12">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
        </div>

        <!-- Cards Here -->
        <div id="content" class="content mt-3">
            
            <div id="loadingMessage" class="align-middle text-center text-dark">
                <i class="fa fa-refresh fa-spin fa-5x"></i>
                <h1 class="display-4">LOADING WALLETS...</h1>
            </div>

            <div hidden id="noWalletsMessage" class="align-middle text-center text-dark">
                <h1 class="display-4">Uh oh!</h1>
                <p>You haven't added any wallets... yet.</p>
            </div>

            <div id="wallets">
            
            </div>

            <!-- Logout Modal -->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logouModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-dark">
                            <h5 class="modal-title text-light" id="logouModalLabel">Sign Out</h5>
                            <button type="button" class="text-light close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                                Do you wish to sign out?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" id="logoutBtn" class="btn btn-primary">Sign Out</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Wallet modal -->
            <div class="modal fade" id="newWalletModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-dark">
                            <h5 class="modal-title text-light" id="addModalLabel">Add Wallet</h5>
                            <button type="button" class="text-light close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="newWalletForm">
                                <div class="form-group">
                                    <label class="form-control-label">Name</label>
                                    <div class="input-group">
                                        <input id="newWalletName" name="name" class="form-control">
                                        <div class="valid-feedback name">
                                        </div>
                            
                                        <div class="invalid-feedback name">
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Address</label>
                                    <div class="input-group">
                                        <input id="newWalletAddress" name="address" class="form-control">
                                        <div class="valid-feedback address">
                                        </div>
                            
                                        <div class="invalid-feedback address">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Currency</label>
                                        <div class="input-group">
                                        <select class="form-control" id="newWalletCurrency" name="currency">
                                            <option>BTC</option>
                                            <option>LTC</option>
                                            <option>DOGE</option>

                                        </select>
                                        <div class="valid-feedback currency">
                                        </div>
                            
                                        <div class="invalid-feedback currency">
                                        </div>
                                    </div>
                                </div>

                                <div class="progress" style="visibility:hidden">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button id="confirmNewWalletBtn" type="button" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Wallet modal -->
            <div class="modal fade" id="editWalletModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-dark">
                            <h5 class="text-light modal-title" id="editModalLabel">Edit Wallet</h5>
                            <button type="button" class="text-light close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editWalletForm">
                                <div class="form-group">
                                    <label class="form-control-label">Name</label>
                                    <div class="input-group">
                                        <input id="editWalletName" name="name" class="form-control">
                                        <div class="valid-feedback name">
                                        </div>
                            
                                        <div class="invalid-feedback name">
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Address</label>
                                    <div class="input-group">
                                        <input id="editWalletAddress" name="address" class="form-control">
                                        <div class="valid-feedback address">
                                        </div>
                            
                                        <div class="invalid-feedback address">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Currency</label>
                                        <div class="input-group">
                                        <select id="editWalletCurrency" class="form-control" name="currency">
                                            <option value="BTC">BTC</option>
                                            <option value="LTC">LTC</option>
                                            <option value="DOGE">DOGE</option>
                                        </select>
                                        <div class="valid-feedback currency">
                                        </div>
                            
                                        <div class="invalid-feedback currency">
                                        </div>
                                    </div>
                                </div>

                                <div class="progress" style="visibility:hidden">
                                    <div class="progress-bar bg-default progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                            </form>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button id="confirmDeleteWalletBtn" type="button" class="btn btn-danger">Delete Wallet</button>
                            <button id="confirmUpdateWalletBtn" type="button" class="btn btn-success">Apply Changes</button>
                        </div>
                    </div>
                </div>
            </div>
                        

        </div> <!-- .content -->
    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <!-- jquery and bootstrap scripts -->
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- Stonk specific scripts -->
    <script type="text/javascript" src="js/view.js"></script>
    <script type="text/javascript" src="js/controller.js"></script>

    <script>
        jQuery(function($) {

            // The view model for client-side MVC.
            $.viewModel = {
                selectedExchange : "AUD",
                wallets : [],
                selectedWallet : 0
            }

            $("#logoutBtn").click(function () {
                window.location = "server/process-logout.php";
            });

            $("#refreshBtn").click(function () {
                location.reload();
            });

            $(".exchangeBtn").click(function () {
                $.viewModel.selectedExchange = $(this).attr("id");
                getWallets($.viewModel);
            })

            $("#confirmNewWalletBtn").click(function (e) {
                e.preventDefault();
                var newWallet = {
                    name : $("#newWalletName").val(),
                    address : $("#newWalletAddress").val(),
                    currency : $("#newWalletCurrency").val()
                }
                addWallet($.viewModel, newWallet);
            });

            $("#confirmDeleteWalletBtn").click(function () {
                deleteWallet($.viewModel);
            });

            $("#confirmUpdateWalletBtn").click(function () {
                var updatedWallet = {
                    walletid : $.viewModel.wallets[$.viewModel.selectedWallet]["walletid"],
                    name : $("#editWalletName").val(),
                    address : $("#editWalletAddress").val(),
                    currency : $("#editWalletCurrency").val()
                }
                updateWallet($.viewModel, updatedWallet);
            });
            
            $(document).ready(function() {
                getWallets($.viewModel);
            });
        });
    </script>

</body>
</html>
