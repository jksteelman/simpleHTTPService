<?php

$loader = require __DIR__ . "/vendor/autoload.php";

/**
 * Load in the environment variables
 */
$env = new Dotenv\Dotenv(__DIR__ . "/", '.env');
$env->load();
$env->required(['DB_DBNAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST']);
if(getenv('ERROR_REPORTING')) {
    error_reporting(-1);
    ini_set("display_errors", 1);
}

/** Autoload app (must comply with PSR-4 namespace/directories). This should be more robust, but its fine for now. */
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . "/" . str_replace("\\", "/", $class_name). ".php";
    if(file_exists($file)) {
        require __DIR__ . "/" . str_replace("\\", "/", $class_name). ".php";
    }
});

