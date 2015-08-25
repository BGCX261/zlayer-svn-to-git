<?php

// Define root path directory
defined('ROOT_PATH')
    || define('ROOT_PATH', realpath(dirname(__FILE__) . "/../" ) . '/');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', ROOT_PATH . 'application/');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(ROOT_PATH . 'library/'),
    get_include_path(),
)));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'default'));
/** ZLayer_Application */
require_once 'ZLayer/Application.php';
$application = new ZLayer_Application();
$application->bootstrap()
            ->run();