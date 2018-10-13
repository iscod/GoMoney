<?php

require_once __DIR__ . '/vendor/autoload.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

/**
 * CORE DIRECTORY NAME
 * DEFAULT Core
 */
$core_path = 'Core///';

/**
 * APP DIRECTORY NAME
 * DEFAULT App
 */
$app_path = 'App';

/**
 * VIEW DIRECTORY NAME
 * DEFAULT app/view'
 */
$view_path = 'App/view';

/**
 * APP CONFIG DIRECTORY NAME
 * DEFAULT App/config
 */
$config_path = 'App/config';

/**
 * SET TIME ZONE
 * ASIA/Shanghai or UTC-8
 * USE CAN GET HELP http://php.net/manual/zh/function.date-default-timezone-set.php
 */
$time_zone = 'Asia/Shanghai';

if (!defined('CORE_PATH')) {
    define('CORE_PATH', trim($core_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
}

date_default_timezone_set($time_zone);

if (!defined('APP_PATH')) {
    define('APP_PATH', trim($app_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
}

if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', trim($view_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
}

if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', trim($config_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
}

if (!is_dir(CORE_PATH)) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your application core path does not fine. Please open the core file and correct';
    exit(3); // EXIT_CONFIG
}

/**
 * Load the global functions
 */

if (file_exists(trim(CORE_PATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'Common.php')) {
    require_once trim(CORE_PATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'Common.php';
}

set_error_handler('_error_handler');
spl_autoload_register('_shutdown_handler');

/**
 * Load GoMoney
 */
$app = new GoMoney\App();
$app->run();
