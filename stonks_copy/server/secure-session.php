<?php

function startSecureSession() {
    // only available over https. Set to TRUE for deployment.
    $secureSession = true;

    if (ini_get("session.use_only_cookies") == "0") {
        ini_set("session.use_only_cookies", "1");
    }
    
    $cookieParameters = session_get_cookie_params();

    // ensure original params are kept, update what we need to.
    session_set_cookie_params(
        $cookieParameters["lifetime"], 
        $cookieParameters["path"], 
        $cookieParameters["domain"],
        $secureSession,
        // prevent javascript from accessing session
        true
    );

    session_name("secure_session");
    session_start();
    session_regenerate_id();
}

?>