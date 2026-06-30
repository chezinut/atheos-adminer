<?php
//////////////////////////////////////////////////////////////////////////////80
// Atheos Adminer Controller
//////////////////////////////////////////////////////////////////////////////80

require_once(__DIR__ . "/../../common.php");
Common::checkSession();

$action = POST("action");
$db = Common::getKeyStore("settings", "users/" . SESSION("user"));

switch ($action) {

    case "loadCredentials":
        $creds = $db->select("adminer.credentials");
        $list = is_array($creds) ? $creds : array();
        $masked = array();
        foreach ($list as $cred) {
            if (!is_array($cred)) continue;
            unset($cred["password"]);
            $masked[] = $cred;
        }
        Common::send(200, array("list" => $masked));
        break;

    case "saveCredentials":
        $raw = POST("credentials");
        $creds = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : null);
        if (!is_array($creds)) {
            Common::send(400, "invalid_data");
            break;
        }

        // Preserve existing passwords when the client returns a masked entry
        // (loadCredentials strips passwords before sending them to the browser).
        $existing = $db->select("adminer.credentials");
        $existing = is_array($existing) ? $existing : array();

        foreach ($creds as $i => &$cred) {
            if (!is_array($cred)) continue;
            $hasPwd = array_key_exists("password", $cred) && $cred["password"] !== "";
            if (!$hasPwd && isset($existing[$i]) && is_array($existing[$i])) {
                $prev = $existing[$i];
                $sameSlot =
                    (isset($prev["server"]) ? $prev["server"] : null) === (isset($cred["server"]) ? $cred["server"] : null) &&
                    (isset($prev["username"]) ? $prev["username"] : null) === (isset($cred["username"]) ? $cred["username"] : null) &&
                    (isset($prev["driver"]) ? $prev["driver"] : "server") === (isset($cred["driver"]) ? $cred["driver"] : "server");
                if ($sameSlot) {
                    $cred["password"] = isset($prev["password"]) ? $prev["password"] : "";
                }
            }
        }
        unset($cred);

        $db->update("adminer.credentials", $creds, true);
        Common::send(200, "saved");
        break;

    default:
        Common::send(416, "invalid_action");
        break;
}
