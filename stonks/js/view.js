// Open an edit window for wallet at index
function openEditWindow(viewModel, index) {
    var wallet = viewModel.wallets[index];
    viewModel.selectedWallet = index;

    jQuery("#editWalletName").removeClass("is-invalid").val(wallet.name);
    jQuery("#editWalletAddress").removeClass("is-invalid").val(wallet.address);
    jQuery("#editWalletCurrency").removeClass("is-invalid").val(wallet.currency);
    jQuery("#editWalletModal").modal("toggle");
}

// Update the target form's validation, works for add and update
function updateFormValidation(validation, target) {
    var form = target + "Form";

    if (validation.result == "invalid") {
        var field = target + validation.field.charAt(0).toUpperCase() + validation.field.slice(1);
        jQuery(form).find(".invalid-feedback." + validation.field).html(validation.msg);
        jQuery(field).addClass("is-invalid");
    }
}

function fadeInProgressBar(colour) {
    jQuery(".progress-bar").removeClass("bg-default bg-danger bg-success");
    jQuery(".progress-bar").addClass(colour);

    jQuery(".progress").fadeTo('fast', 0.3, function() {
        jQuery(".progress").css("visibility", "visible");
    }).fadeTo('fast', 1);
}

function fadeOutProgressBar() {
    jQuery(".progress").fadeTo('fast', 0.3, function() {
        jQuery(".progress").css("visibility", "hidden");
    }).fadeTo('fast', 1);
}

// Update HTML for wallets
function updateWalletHtml(viewModel) {
    jQuery("#wallets").empty();

    var walletCount = 0;
    jQuery.each(viewModel.wallets, function(index, element) {
        walletCount++;
        var walletHtml = genWalletHtml(viewModel, index, element);

        jQuery("#wallets").append(walletHtml);

        // Add the edit button event listener
        jQuery("#editWallet-" + index).click(function (e) {
            e.preventDefault();
            openEditWindow(viewModel, index);
        });
    });

    jQuery("#loadingMessage").fadeOut('fast', function(x = walletCount) {
        if (x == 0) {
            jQuery("#noWalletsMessage").hide().removeAttr("hidden").fadeIn();
        } else {
            jQuery("#wallets").fadeIn();
        }
    });
}

// Generate HTML for a given wallet
function genWalletHtml(viewModel, index, wallet) {
    var walletHtml =  jQuery([
        '<div id="wallet-' + index + '" class="col-sm-12 col-md-6 col-lg-6 col-xl-4">',
            '<div class="card text-light">',
                '<div class="card-header bg-flat-color-5">',
                    '<div class="col-10 pl-0">',
                        '<h4 class="mb-0 no-wrap">',
                            wallet.name,                           
                        '</h4>',
                    '</div>',
                    '<div>',
                        '<a id="editWallet-' + index + '" class="float-right" href="#"> <i class="text-light fa fa-pencil-square-o"></i></a>',
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
                                '<th class="short-table-item" scope="row">' + viewModel.selectedExchange +'</th>',
                                '<td class="dollarValue long-table-item">Pending...</td>',
                                '<td class="shorter-table-item"><small>est.</small></td>',

                            '</tr>',
                        '</tbody>',
                    '</table>',
                '</div>',
            '</div>',
        '</div>'
    ].join("\n"));
    return walletHtml;
}

// Update exchange rates HTML
function updateCurrencyExchangeHtml(viewModel) {
    jQuery.each(viewModel.wallets, function(index, wallet) {
        var price = viewModel.cryptoCurrencies[wallet.currency][viewModel.selectedExchange].price;
        var val = (wallet.balance * price).toFixed(2);
        wallet.val = val;
        jQuery("#wallet-" + index).find(".dollarValue").hide().html("$" + val).fadeIn('fast');
    });

    jQuery("#exchangeRates").hide();
    jQuery("#exchangeRates").empty();
    var exchangeHtml = "";
    jQuery.each(viewModel.cryptoCurrencies, function(cryptoCurrency, cryptoData) {
        jQuery("#exchangeRates").append(genCurrencyExchangeHtml(viewModel, cryptoCurrency, cryptoData));
    });
    jQuery("#exchangeRates").fadeIn('fast');
}

// Generate HTML for crypto exchange rates
function genCurrencyExchangeHtml(viewModel, cryptoCurrency, cryptoData) {
    var change = cryptoData[viewModel.selectedExchange].change;
    var exchangeRateHtml;
    if (change > 0) {
        exchangeRateHtml = "<i class='fa fa-arrow-circle-up text-success'></i> +" + change.toFixed(2) + "%";
    } else if (change < 0) {
        exchangeRateHtml = "<i class='fa fa-arrow-circle-down text-danger'></i> " + change.toFixed(2) + "%";
    } else {
        exchangeRateHtml = "<i class='fa fa-arrow-circle-right text-warning'></i>";
    }

    var priceHtml = "</br>$" + cryptoData[viewModel.selectedExchange].price + " " + viewModel.selectedExchange + " ";

    var cryptoExchangeHtml = jQuery([
        "<li>",
            "<a><span class='text-light'>" + cryptoCurrency + "<span class='text-muted'> " + exchangeRateHtml + priceHtml + "</span></span></a>",
        "</li>"
    ].join("\n"));

    return cryptoExchangeHtml;
}