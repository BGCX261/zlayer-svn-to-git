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
 * @package    ZLayer_Application
 * @subpackage Bootstrap
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Application_Bootstrap_Bootstrap
 */
require_once 'Zend/Application/Bootstrap/Bootstrap.php';

/**
 * @see Zend_Controller_Front
 */
require_once 'Zend/Controller/Front.php';

/**
 * Bootstrap for ZLayer applications
 *
 * @uses       Zend_Application_Bootstrap_Bootstrap
 * @category   ZLayer
 * @package    ZLayer_Application
 * @subpackage Bootstrap
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    
    /**
     * _initAutoConstants
     *
     * @return void
     */
    public function _initAutoConstants() 
    {
        $frontOptions = $this->getPluginResource("frontController")
                             ->getOptions();
        
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        
        // Define application baseUrl
        defined('BASE_URL') || define('BASE_URL',
               ( getenv('BASE_URL') ? getenv('BASE_URL') : 
               $front->getBaseUrl()));
        
        // Define application publicPath
        defined('PUBLIC_PATH') || define('PUBLIC_PATH',
               ( getenv('PUBLIC_PATH') ? getenv('PUBLIC_PATH') : 
               ROOT_PATH . $frontOptions["publicDir"] ));
        
        
        //echo "<pre>";
        //$front->setRequest(new Zend_Controller_Request_Http());
        //print_r($front->getRequest());
               
    }
    
    /**
     * _initAutoLoader
     *
     * @return Zend_Loader_Autoloader
     */
    public function _initAutoLoader() 
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
    
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath'      => APPLICATION_PATH ,
            'namespace'     => 'Application',
            'resourceTypes' => array(
                'plugin' => array(
                    'path'      => 'plugins/',
                    'namespace' => 'Plugin',
                ),
                'form' => array(
                    'path'      => 'forms/',
                    'namespace' => 'Form',
                ),
                'model' => array(
                    'path'      => 'models/',
                    'namespace' => 'Model',
                ),
            ),
        ));
        
        $autoloader->pushAutoloader($resourceLoader);
        
        return $autoloader;
        
    }
    
    /**
     * _initFilter
     *
     * @return void
     */
    public function _initFilter()
    {
        Zend_Filter::addDefaultNamespaces("ZLayer_Filter");
        Zend_Filter::addDefaultNamespaces("Zend_Filter_Word");
    }
    
  
    /*
    public function _initMyFrontController()
    {
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->setDispatcher(new Zend_Controller_Dispatcher_Standard());
    
        return $frontController;
    }
    */
    
}