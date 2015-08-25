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
 * to license@ZLayer.com so we can send you a copy immediately.
 *
 * @category   ZLayer
 * @package    ZLayer
 * @subpackage UnitTests
 * @copyright  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://Extension.zend.com/license/new-bsd     New BSD License
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'ZLayer_AllTests::main');
}


require_once 'ZLayer/VersionTest.php';

/**
 * @category   ZLayer
 * @package    ZLayer
 * @subpackage UnitTests
 * @group      ZLayer
 * @copyright  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_AllTests
{
    public static function main()
    {
        // Run buffered tests as a separate suite first
        ob_start();
        PHPUnit_TextUI_TestRunner::run(self::suiteBuffered());
        if (ob_get_level()) {
            ob_end_flush();
        }

        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * Buffered test suites
     *
     * These tests require no output be sent prior to running as they rely
     * on internal PHP functions.
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suiteBuffered()
    {
        $suite = new PHPUnit_Framework_TestSuite('ZLayer Extension - ZLayer - Buffered Test Suites');

        return $suite;
    }

    /**
     * Regular suite
     *
     * All tests except those that require output buffering.
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('ZLayer Extension - ZLayer');

        $suite->addTestSuite('ZLayer_VersionTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'ZLayer_AllTests::main') {
    ZLayer_AllTests::main();
}
