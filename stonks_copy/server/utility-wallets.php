<?php
require_once("block-io.php");
include_once("api-keys.php");

// Add this wallet to the db for owner
function addWallet($wallet, $ownerId, $dbConn) {
    $validation = array(
        validateWalletName($wallet["name"]),
        validateWalletAddressLen($wallet["address"]),
        validateDuplicateAddress($ownerId, $wallet["address"], $dbConn),
        validateCurrency($wallet["currency"]),
        validateWalletContent($wallet, $ownerId, $dbConn)
    );

    foreach($validation as $result) {
        if ($result["result"] == "invalid") {
            return json_encode($result);
        }
    }

    if (!insertWallet($ownerId, $wallet["name"], $wallet["currency"], $wallet["address"], $dbConn)) {
        return json_encode(array(
            "result" => "error",
            "msg" => "Failed to add wallet at this time"
        ));

    } else {
        return json_encode(array(
            "result" => "success",
            "newWallet" => array(
                "name" => $wallet["name"],
                "currency" => $wallet["currency"],
                "address" => $wallet["address"]
            )
        ));
    }

}

// Update the wallet with the provided id, for the given owner
function updateWallet($wallet, $ownerId, $dbConn) {
    // Leave out duplicate check, as this most likely will be a duplicate, and that's fine.
    $validation = array(
        validateWalletName($wallet["name"]),
        validateWalletAddressLen($wallet["address"]),
        validateCurrency($wallet["currency"]),
        validateWalletContent($wallet, $ownerId, $dbConn)
    );

    foreach($validation as $result) {
        if ($result["result"] == "invalid") {
            return json_encode($result);
        }
    }

    if (!($sqlStmnt = $dbConn->prepare("UPDATE wallets SET name = ?, currency = ?, address = ?  WHERE walletid = ? AND ownerid = ?"))) {
        die("Bad statement");
        exit();
    }

    $sqlStmnt->bind_param("sssii", $wallet["name"], $wallet["currency"], $wallet["address"], $wallet["walletid"], $ownerId);

    if (!$sqlStmnt->execute()) {
        return json_encode(array(
            "result" => "error",
            "msg" => "Failed to update wallet at this time"
        ));
    } else {
        return json_encode(array(
            "result" => "success",
        ));
    }
}

// Delete the provided wallet from owner's wallets
function deleteWallet($walletId, $ownerId, $dbConn) {
    if (!($sqlStmnt = $dbConn->prepare("DELETE FROM wallets WHERE ownerid = ? AND walletid = ?"))) {
        die("Bad statement");
        exit();
    }
    
    $sqlStmnt->bind_param("ii", $ownerId, $walletId);

    return $sqlStmnt->execute();
    
}

// Create a new wallet
function insertWallet($ownerId, $name, $currency, $address, $dbConn) {
    if (!($sqlStmnt = $dbConn->prepare("INSERT INTO wallets (ownerid, name, currency, address) VALUES (?, ?, ?, ?)"))) {
        die("Bad statement");
        exit();
    }

    $sqlStmnt->bind_param("isss", $ownerId, $name, $currency, $address);
    return $sqlStmnt->execute();
}

// Get all the wallets for user
function getWallets($ownerId, $dbConn) {
    if (!($sqlStmnt = $dbConn->prepare("SELECT walletid, name, currency, address FROM wallets WHERE ownerid = ?"))) {
        die("Bad statement");
        exit();
    }

    $sqlStmnt->bind_param("i", $ownerId);
    $sqlStmnt->execute();

    $result = $sqlStmnt->get_result();

    $sqlStmnt->free_result();
    $sqlStmnt->close();

    $wallets = array();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $wallets[] = $row;

    }
    
    // Split wallets into crypto currency groups
    $split = array();
    foreach ($wallets as $wallet) {
        
        if (!array_key_exists($wallet["currency"], $split)) {
            $split[$wallet["currency"]] = array();
        }
        $split[$wallet["currency"]][] = $wallet["address"];
    }

    // Get balances of each wallet
    $balances = array();
    foreach ($split as $currency => $addresses) {
        $data = getAddressContent($addresses, $currency)["data"]->balances;
        $balances = array_merge($balances, $data);
    }

    // Assign balances to wallets
    foreach($wallets as $index => $wallet) {
        $wallet["balance"] = $balances[$index]->available_balance;
        $wallets[$index] = $wallet;
    }

    return $wallets;
}

function validateWalletName($walletName) {
    if (strlen($walletName) < 3 | strlen($walletName) > 32) {
        return array(
            "result" => "invalid",
            "field" => "name",
            "msg" => "Wallet name must be between 3 and 32 characters."
        );
    } else {
        return array(
            "result" => "valid"
        );
    }
}

function validateWalletAddressLen($address) {
    if (strlen($address) == 0) {
        return array(
            "result" => "invalid",
            "field" => "address",
            "msg" => "Address cannot be empty."
        );
    } else {
        return array(
            "result" => "valid"
        );
    }
}

function validateCurrency($currency) {
    if (!array_key_exists($currency, API_KEYS)) {
        return array(
            "result" => "invalid",
            "field" => "currency",
            "msg" => $currency . " is not a supported currency."
        );
    } else {
        return array(
            "result" => "valid"
        );
    }
}

// Check if user has this address already
function validateDuplicateAddress($userId, $address, $dbConn) {
    foreach (getWallets($userId, $dbConn) as $existingWallet) {
        if ($address == $existingWallet["address"]) {
            return array(
                "result" => "invalid",
                "field" => "address",
                "msg" => "You already have a wallet with this address."
            );
        }
    }
    return array(
        "result" => "valid"
    );
}

// Check the address exists
function validateWalletContent($wallet, $userId, $dbConn) {
    // Check the address is valid and has some content
    $content = getAddressContent(array($wallet["address"]), $wallet["currency"]);
    if ($content["status"] == "failed") {
        return array(
            "result" => "invalid",
            "field" => "address",
            "msg" => "Address does not exist for currency."
        );
    }

    // wallet is good
    return array(
        "result" => "success",
        "content" => $content
    );
}

// Get content of the address of type currency
function getAddressContent($addresses, $currency) {
        $block_io = new BlockIo(API_KEYS[$currency], PIN, VERSION);
        $addresses = implode(",", $addresses);

        try {
            return array(
                "status" => "success",
                "data" => $block_io->get_address_balance(array("addresses" => $addresses))->data
            );

        } catch (Exception $e) {
            return array(
                "status" => "failed",
                "msg" => $e->getMessage()
                );
        }
}

?>