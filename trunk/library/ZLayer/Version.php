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
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Class to store and retrieve the version of Zend Framework.
 *
 * @category   ZLayer
 * @package    ZLayer_Version
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
final class ZLayer_Version
{
    /**
     * ZLayer Framework version identification
     */
    const VERSION = '1.11.10';

    /**
     * Returns version
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::VERSION;
    }
}
