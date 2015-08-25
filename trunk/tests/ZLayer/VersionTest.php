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
 * @package    ZLayer_Version
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 ZLayer Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: VersionTest.php 23775 2011-03-01 17:25:24Z ralph $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'ZLayer_VersionTest::main');
}

/**
 * @see ZLayer_Version
 */
require_once 'ZLayer/Version.php';

/**
 * @category   ZLayer
 * @package    ZLayer_Version
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 ZLayer Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      ZLayer_Version
 */
class ZLayer_VersionTest extends PHPUnit_Framework_TestCase
{
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * @group ZF-10363
     */
    public function testFetchVersion()
    {
        $actual = ZLayer_Version::getVersion();
        $this->assertRegExp('/^[1-2](\.[0-9]+){2}/', $actual);
    }
}

if (PHPUnit_MAIN_METHOD == "ZLayer_VersionTest::main") {
    ZLayer_VersionTest::main();
}
