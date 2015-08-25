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
 * @package    Application
 * @subpackage Module
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Application_Module_Bootstrap
 */
require_once "Zend/Application/Module/Bootstrap.php";

/**
 * @see Zend_Application_Module_Autoloader
 */
require_once "Zend/Application/Module/Autoloader.php";

/**
 * Application Module Bootstrap
 *
 * @uses       Zend_Application_Module_Bootstrap
 * @category   ZLayer
 * @package    Application
 * @subpackage Module
 * @author  Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zlayer_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap
{

    public function _initAutoLoader() 
    {
        
    }
}