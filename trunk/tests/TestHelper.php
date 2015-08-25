<?php
/**
 * ZLayer Extension
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   ZLayer
 * @package    ZLayer
 * @subpackage UnitTests
 * @copyright  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Include PHPUnit dependencies
 */
require_once 'PHPUnit/Runner/Version.php';

$phpunitVersion = PHPUnit_Runner_Version::id();
if ($phpunitVersion == '@package_version@' || version_compare($phpunitVersion, '3.5.5', '>=')) {
    if (version_compare($phpunitVersion, '3.6.0', '>=')) {
        echo 'This verison of PHPUnit is not supported in ZLayer Extension 1.x unit tests.';
        exit(1);
    }
    require_once 'PHPUnit/Autoload.php'; // >= PHPUnit 3.5.5
} else {
    require_once 'PHPUnit/Extension.php'; // < PHPUnit 3.5.5
}

/*
 * Set error reporting to the level to which ZLayer Extension code must comply.
 */
error_reporting(E_ALL | E_STRICT);

/*
 * Determine the root, library, and tests directories of the framework
 * distribution.
 */
$zeRoot        = realpath(dirname(dirname(__FILE__)));
$zeCoreLibrary = "$zeRoot/library";
$zeCoreTests   = "$zeRoot/tests";

/*
 * Prepend the ZLayer Extension library/ and tests/ directories to the
 * include_path. This allows the tests to run out of the box and helps prevent
 * loading other copies of the framework code and tests that would supersede
 * this copy.
 */
$path = array(
    $zeCoreLibrary,
    $zeCoreTests,
    get_include_path()
    );
set_include_path(implode(PATH_SEPARATOR, $path));

/*
 * Load the user-defined test configuration file, if it exists; otherwise, load
 * the default configuration.
 */
if (is_readable($zeCoreTests . DIRECTORY_SEPARATOR . 'TestConfiguration.php')) {
    require_once $zeCoreTests . DIRECTORY_SEPARATOR . 'TestConfiguration.php';
} else {
    require_once $zeCoreTests . DIRECTORY_SEPARATOR . 'TestConfiguration.php.dist';
}

/**
 * Start output buffering, if enabled
 */
if (defined('TESTS_ZEND_OB_ENABLED') && constant('TESTS_ZEND_OB_ENABLED')) {
    ob_start();
}

/*
 * Unset global variables that are no longer needed.
 */
unset($zeRoot, $zeCoreLibrary, $zeCoreTests, $path);
