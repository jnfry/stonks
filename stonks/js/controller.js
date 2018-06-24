// Delete wallet with given index within viewModel
function deleteWallet(viewModel) {
    fadeInProgressBar("bg-danger");
    var data = {"delete" : viewModel.wallets[viewModel.selectedWallet]["walletid"]};
    
    jQuery.ajax({  url: "server/process-delete-wallet.php",
            data: data,
            type: "post",
            datatype: "json",
            success: function(result) {
                fadeOutProgressBar();
                if (result.result == "error") {
                    window.location = "error.php?error=" + result.msg;
                    return;
                }

                // Wallet was added, close the window and refresh html
                if (result.result == "success") {
                    jQuery("#editWalletModal").modal("toggle");
                    getWallets(viewModel);
                }
                
            }
    });
}

// Update wallet at given index within viewModel, with details from provided wallet
function updateWallet(viewModel, updatedWallet) {
    fadeInProgressBar("bg-success");

    // Remove form validation indicators
    jQuery("#editWalletName").removeClass("is-invalid");
    jQuery("#editWalletAddress").removeClass("is-invalid");
    jQuery("#editWalletCurrency").removeClass("is-invalid");

    var data = {"update" : updatedWallet};
    jQuery.ajax({  url: "server/process-update-wallet.php",
            data: data,
            type: "post",
            datatype: "json",
            success: function(result) {
                fadeOutProgressBar();

                if (result.result == "error") {
                    window.location = "error.php?error=" + result.msg;
                    return;
                }

                updateFormValidation(result, "#editWallet");

                // Wallet was updated, close the window and refresh html
                if (result.result == "success") {
                    jQuery("#editWalletModal").modal("toggle");
                    viewModel.wallets[viewModel.selectedWallet] = updatedWallet;
                    getWallets(viewModel);
                }
            }
    });

}

// Attempt to add a user defined wallet to the database
function addWallet(viewModel, newWallet) {
    fadeInProgressBar("bg-default");                

    // Remove form validation indicators
    // Super bad, this should be in view.js... same with a lot below.
    jQuery("#newWalletName").removeClass("is-invalid");
    jQuery("#newWalletAddress").removeClass("is-invalid");
    jQuery("#newWalletCurrency").removeClass("is-invalid");

    var data = {"add" : newWallet};
    jQuery.ajax({  url: "server/process-add-wallet.php",
            data: data,
            type: "post",
            datatype: "json",
            success: function(result) {
                fadeOutProgressBar();
                
                if (result.result == "error") {
                    window.location = "error.php?error=" + result.msg;
                    return;
                }

                updateFormValidation(result, "#newWallet");

                // Wallet was added, close the window and refresh html
                if (result.result == "success") {
                    jQuery("#newWalletModal").modal("toggle");
                    getWallets(viewModel);
                }
            }
    });
}

// Request a read of wallets from db.
// Follow up by refreshing wallet html
function getWallets(viewModel) {
    jQuery("#noWalletsMessage").hide();
    jQuery("#wallets").hide();
    jQuery("#loadingMessage").fadeIn('fast', function() {
        jQuery.ajax({  url: "server/process-get-wallets.php",
            type: "post",
            datatype: "json",
            success: function(result) {                            
                viewModel.wallets = [];
                jQuery.each(result, function(index, element) {
                    viewModel.wallets[index] = element;
                });
                updateWalletHtml(viewModel);
                getCurrencyExchange(viewModel);
            }
        });
    });
}

// Get crypto exchange rates
function getCurrencyExchange(viewModel) {
    jQuery.ajax({  url: "server/process-get-exchange.php",
        type: "post",
        datatype: "json",
        success: function(result) {
            viewModel.cryptoCurrencies = new Object();
            jQuery.each(result, function(cryptoCurrency, cryptoData) {
                var exchangeRates = new Object();
        
                jQuery.each(cryptoData, function(currency, data) {
                    exchangeRates[currency] = {
                        "change" : data["CHANGEPCT24HOUR"],
                        "price" : data["PRICE"]
                        };
                })
        
                viewModel.cryptoCurrencies[cryptoCurrency] = exchangeRates;
            });

            updateCurrencyExchangeHtml(viewModel, result);
        }
    });
}  