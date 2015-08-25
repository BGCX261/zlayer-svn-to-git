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
 * @category  ZLayer
 * @package  ZLayer_Application
 * @subpackage  Resource
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';


/**
 * Error handler resource
 *
 * @uses      Zend_Application_Resource_ResourceAbstract
 * @category  ZLayer
 * @package  ZLayer_Application
 * @subpackage  Resource
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Application_Resource_Error extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Initialize Front Controller
     *
     * @return Zend_Controller_Front
     */
    public function init()
    {
        // make sure the frontcontroller has been setup
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('FrontController');
        $front = $bootstrap->getResource('frontcontroller');
        
        // option from the config file
        $pluginOptions = $this->getOptions();
        $className = $pluginOptions['class'];

        Zend_Loader::loadClass($className);
        $plugin = new $className($pluginOptions['options']);
        $front->registerPlugin($plugin);
        return $plugin;
    }
    
}