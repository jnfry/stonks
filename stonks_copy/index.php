<?php
include_once("server/server-utility.php");
include_once("server/utility-wallets.php");
include_once("server/secure-session.php");
include_once("server/db-conn.php");

startSecureSession();

if (!isLoggedIn()) {
  header('Location: page-login.php');
}



//$_SESSION["wallets"] = getWallets($_SESSION["userId"], $dbConn);

//$_SESSION["walletChecked"] = time();
//60 is 1 min


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
                    <li>
                        <a><span class=" text-light">BTC<span id="BTC-price" class="text-muted"></span></span></a>
                    </li>
                    <li>
                        <a><span class=" text-light">LTC<span id="LTC-price" class="text-muted"></span></span></a>
                    </li>
                    <li>
                        <a><span class=" text-light">DOGE<span id="DOGE-price" class="text-muted"></span></span></a>
                    </li>

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

    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        jQuery(function($) {
            // store wallets
            $.wallets = [];
            // for editing wallets, stores the most recently selected
            $.selectedWallet;
            // For different currencies
            $.exchanges;
            // Store the currently selected exchange
            $.selectedExchange = "AUD";


            $("#logoutBtn").click(function () {
                window.location = "server/process-logout.php";
            });

            $("#refreshBtn").click(function (e) {
                e.preventDefault();
                location.reload();
            });

            $(".exchangeBtn").click(function (e) {
                $.selectedExchange = $(this).attr("id");
                getWallets();

            })

            $("#confirmNewWalletBtn").click(function (e) {
                e.preventDefault();
                var newWallet = {
                    name : $("#newWalletName").val(),
                    address : $("#newWalletAddress").val(),
                    currency : $("#newWalletCurrency").val()
                }
                addWallet(newWallet);
                
            });

            $("#confirmDeleteWalletBtn").click(function () {
                deleteWallet($.selectedWallet);
            });

            $("#confirmUpdateWalletBtn").click(function () {
                var updatedWallet = {
                    walletid : $.wallets[$.selectedWallet]["walletid"],
                    name : $("#editWalletName").val(),
                    address : $("#editWalletAddress").val(),
                    currency : $("#editWalletCurrency").val()
                }
                updateWallet(updatedWallet, $.selectedWallet);
            });

            function fadeInLoadbar(colour) {
                $(".progress-bar").removeClass("bg-default bg-danger bg-success");
                $(".progress-bar").addClass(colour);

                $(".progress").fadeTo('fast', 0.3, function() {
                    $(".progress").css("visibility", "visible");
                }).fadeTo('fast', 1);
            }

            function fadeOutLoadbar() {
                $(".progress").fadeTo('fast', 0.3, function() {
                    $(".progress").css("visibility", "hidden");
                }).fadeTo('fast', 1);
            }

            // Update the target form's validation, specified by obj
            function updateFormValidation(obj, target) {
                var form = target + "Form";

                if (obj.result == "invalid") {
                    var field = target + obj.field.charAt(0).toUpperCase() + obj.field.slice(1);
                    $(form).find(".invalid-feedback." + obj.field).html(obj.msg);
                    $(field).addClass("is-invalid");
                }
            }

            // Open an edit window for wallet at index
            function openEditWindow(index) {
                var wallet = $.wallets[index];
                $.selectedWallet = index;

                $("#editWalletName").removeClass("is-invalid").val(wallet.name);
                $("#editWalletAddress").removeClass("is-invalid").val(wallet.address);
                $("#editWalletCurrency").removeClass("is-invalid").val(wallet.currency);
                $("#editWalletModal").modal("toggle");
            }

            // Delete a wallet at given index in $.wallets
            function deleteWallet(index) {
                fadeInLoadbar("bg-danger");
                var data = {"delete" : $.wallets[index]["walletid"]};
                
                $.ajax({  url: "server/process-delete-wallet.php",
                        data: data,
                        type: "post",
                        datatype: "json",
                        success: function(result) {
                            fadeOutLoadbar();
                            if (result.result == "error") {
                                window.location = "error.php?error=" + result.msg;
                                return;
                            }

                            // Wallet was added, close the window and refresh html
                            if (result.result == "success") {
                                $("#editWalletModal").modal("toggle");
                                getWallets();
                            }
                            
                        }
                });
            }

            // Update wallet at index in $.wallet with new info
            function updateWallet(updatedWallet, index) {
                fadeInLoadbar("bg-success");

                // Remove form validation indicators
                $("#editWalletName").removeClass("is-invalid");
                $("#editWalletAddress").removeClass("is-invalid");
                $("#editWalletCurrency").removeClass("is-invalid");

                var data = {"update" : updatedWallet};
                $.ajax({  url: "server/process-update-wallet.php",
                        data: data,
                        type: "post",
                        datatype: "json",
                        success: function(result) {
                            fadeOutLoadbar();

                            if (result.result == "error") {
                                window.location = "error.php?error=" + result.msg;
                                return;
                            }

                            updateFormValidation(result, "#editWallet");

                            // Wallet was updated, close the window and refresh html
                            if (result.result == "success") {
                                $("#editWalletModal").modal("toggle");
                                $.wallets[index] = updatedWallet;
                                getWallets();
                            }
                        }
                });

            }

            // Attempt to add a user defined wallet to the database
            function addWallet(newWallet) {
                fadeInLoadbar("bg-default");                

                // Remove form validation indicators
                $("#newWalletName").removeClass("is-invalid");
                $("#newWalletAddress").removeClass("is-invalid");
                $("#newWalletCurrency").removeClass("is-invalid");

                var data = {"add" : newWallet};
                $.ajax({  url: "server/process-add-wallet.php",
                        data: data,
                        type: "post",
                        datatype: "json",
                        success: function(result) {
                            fadeOutLoadbar();
                            
                            if (result.result == "error") {
                                window.location = "error.php?error=" + result.msg;
                                return;
                            }

                            updateFormValidation(result, "#newWallet");

                            // Wallet was added, close the window and refresh html
                            if (result.result == "success") {
                                $("#newWalletModal").modal("toggle");
                                getWallets();
                            }
                        }
                });
            }

            // Request a read of wallets from db.
            // Follow up by refreshing wallet html
            function getWallets() {
                $("#noWalletsMessage").hide();
                $("#wallets").hide();
                $("#loadingMessage").fadeIn('fast', function() {
                    $.ajax({  url: "server/process-get-wallets.php",
                        type: "post",
                        datatype: "json",
                        success: function(result) {                            
                            $.wallets = [];
                            $.each(result, function(index, element) {
                                $.wallets[index] = element;
                            });
                            updateWalletHtml();
                            getCurrencyExchange();
                        }
                    });
                });
            }

            // Update html for wallets
            function updateWalletHtml() {
                $("#wallets").empty();

                var walletCount = 0;
                $.each($.wallets, function(index, element) {
                    walletCount++;
                    var divId = index;
                    genWalletHtml(divId, element);
                    
                });

                $("#loadingMessage").fadeOut('fast', function(x = walletCount) {
                    if (x == 0) {
                        $("#noWalletsMessage").hide().removeAttr("hidden").fadeIn();
                    } else {
                        $("#wallets").fadeIn();
                    }
                });
            }

            // Generate html for a wallet
            function genWalletHtml(index, wallet) {
                var walletHtml =  $([
                    '<div id="wallet-' + index + '" class="col-sm-12 col-md-6 col-lg-6 col-xl-4">',
                        '<div class="card text-light">',
                            '<div class="card-header bg-flat-color-5">',
                                '<div class="col-10 pl-0">',
                                    '<h4 class="mb-0 no-wrap">',
                                    wallet.name,                           
                                    '</h4>',
                                '</div>',
                                '<div>',
                                    '<a id="editWallet-' + index +'" class="float-right" href="#"> <i class="text-light fa fa-pencil-square-o"></i></a>',
                                '</div>',
                            '</div>',
                            
                            '<div class="card-body pb-0 text-muted">',
                                '<table class="table mb-0">',
                                    '<tbody class="mt-0">',
                                        '<tr>',
                                            '<th class="short-table-item" scope="row">' + wallet.currency + '</th>',
                                            '<td class="long-table-item">' + wallet.balance + '</td>',
                                            '<td class="shorter-table-item"></td>',
                                        '</tr>',
                                        '<tr >',
                                            '<th class="short-table-item" scope="row">' + $.selectedExchange +'</th>',
                                            '<td class="dollarValue long-table-item">Pending...</td>',
                                            '<td class="shorter-table-item"><small>est.</small></td>',

                                        '</tr>',
                                    '</tbody>',
                                '</table>',
                            '</div>',
                        '</div>',
                    '</div>'
                    ].join("\n"));
                    $("#wallets").append(walletHtml);
                    $("#editWallet-" + index).click(function (e) {
                        e.preventDefault();
                        openEditWindow(index);
                    })
            }

            // Update the prices for currencies etc.
            function getCurrencyExchange() {
                $.ajax({  url: "server/process-get-exchange.php",
                    type: "post",
                    datatype: "json",
                    success: function(result) {
                        $.exchanges = new Object();                         
                        $.each(result, function(index, element) {
                                $.exchanges[index] = element[$.selectedExchange];
                        });

                        $.each($.wallets, function(index, wallet) {
                            var price = $.exchanges[wallet.currency]["PRICE"];
                            var val = (wallet.balance * price).toFixed(2);
                            wallet.val = val;
                            $("#wallet-" + index).find(".dollarValue").hide().html("$" + val).fadeIn('fast');
                        });


                        $.each($.exchanges, function(currency, exchange) {
                            var newHtml = " "
                            var change = exchange["CHANGEPCTDAY"];

                            if (change > 0) {
                                newHtml += "<i class='fa fa-arrow-circle-up text-success'></i> +" + change.toFixed(2) + "% today";
                            } else if (change < 0) {
                                newHtml += "<i class='fa fa-arrow-circle-down text-danger'></i> " + change.toFixed(2) + "% today";
                            } else {
                                newHtml += "<i class='fa fa-arrow-circle-right text-warning'></i> today";
                            }

                            newHtml += "</br>$" + exchange["PRICE"] + " " + $.selectedExchange + " ";
                            $("#" + currency + "-price").hide().html(newHtml).fadeIn('fast');
                        });
                    }
                });
            }       

            $(document).ready(function() {
                getWallets();
            });
        });
    </script>

</body>
</html>
