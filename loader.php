<?php
//////////////////////////////////////////////////////////////////////////////80
// Atheos Adminer
//////////////////////////////////////////////////////////////////////////////80

if (ob_get_level() === 0) {
    ob_start();
}

require_once(__DIR__ . "/../../common.php");

// Adminer 5.x doesn't need adminer_object() for basic usage
// The function is only needed for plugins

include __DIR__ . "/editor.php";

if (ob_get_level() > 0) {
    ob_end_flush();
}
