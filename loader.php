<?php
//////////////////////////////////////////////////////////////////////////////80
// Atheos Adminer
//////////////////////////////////////////////////////////////////////////////80

if (ob_get_level() === 0) {
    ob_start();
}

require_once(__DIR__ . "/../../common.php");
Common::checkSession();

// Auto-login flow: ?cred=<index> resolves the saved credential server-side
// and feeds it to Adminer through $_POST["auth"], which Adminer handles as
// a normal login submission. The password is never exposed via GET.
if (isset($_GET["cred"]) && empty($_POST["auth"])) {
    $idx = (int) $_GET["cred"];
    $db = Common::getKeyStore("settings", "users/" . SESSION("user"));
    $creds = $db->select("adminer.credentials");
    if (is_array($creds) && isset($creds[$idx]) && is_array($creds[$idx])) {
        $cred = $creds[$idx];
        $_POST["auth"] = array(
            "driver"   => isset($cred["driver"]) ? $cred["driver"] : "server",
            "server"   => isset($cred["server"]) ? $cred["server"] : "",
            "username" => isset($cred["username"]) ? $cred["username"] : "",
            "password" => isset($cred["password"]) ? $cred["password"] : "",
            "db"       => isset($cred["dbname"]) ? $cred["dbname"] : "",
        );
    }
    // Strip the query so Adminer's internal redirects don't carry it around.
    $_GET = array_diff_key($_GET, array("cred" => 1));
    $_SERVER["REQUEST_URI"] = preg_replace('~([?&])cred=[^&]*&?~', '$1', $_SERVER["REQUEST_URI"]);
    $_SERVER["REQUEST_URI"] = rtrim($_SERVER["REQUEST_URI"], "?&");
}

include __DIR__ . "/editor.php";

if (ob_get_level() > 0) {
    ob_end_flush();
}
