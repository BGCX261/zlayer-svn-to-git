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
 * @category ZLayer
 * @package  ZLayer_Application
 * @author   João Paulo Faria <jpfaria@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd     New BSD License
 * @version  1.0.0
 * @link     http://code.google.com/p/zlayer
 */

/**
 * @see Zend_Application
 */
require_once 'Zend/Application.php';

/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';

/**
 * @see ZLayer_Config_Directory
 */
require_once 'ZLayer/Config/Directory.php';

/**
 * @see Zend_Translate
 */
require_once 'Zend/Translate.php';

/**
 * Class for ZLayer applications
 *
 * @uses  Zend_Application
 * @category ZLayer
 * @package  ZLayer_Application
 * @author   João Paulo Faria <jpfaria@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd     New BSD License
 * @link     http://code.google.com/p/zlayer
 */
class ZLayer_Application extends Zend_Application
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        // Call Zend_Application constructor
        parent::__construct(APPLICATION_ENV, $this->_getOptions());
    }

    /**
     * Get options
     *
     * @return array
     */
    private function _getOptions() 
    {
        if (!extension_loaded('apc')) {
            return $this->_makeOptions();
        }
        
        //@todo GET SESSION/CACHE/LOAD Config
        include_once 'ZLayer/Cache.php';
        include_once 'Zend/Cache.php';
        include_once 'Zend/Cache/Core.php';
        include_once 'Zend/Cache/Backend/Apc.php';
        
        $configCache = new Zend_Cache_Core(
            array('automatic_serialization'=>true)
        );
        
        $backend = new Zend_Cache_Backend_Apc();
        $configCache->setBackend($backend);
        
        //$configMTime = filemtime($file);
        
        $cacheId = ZLayer_Cache::id("application");
        
        $configCache->remove($cacheId);
        
        if ( $configCache->test($cacheId) !== false ) {
            return $configCache->load($cacheId, true);
        } else {
            
            $array = $this->_makeOptions();
            $configCache->save($array, $cacheId, array(), null);
            
            return $array;
        }
        
        
    }

    /**
     * _makeOptions
     *
     * @return array
     */
    private function _makeOptions()
    {
        // Creates an empty configuration
        $options = new Zend_Config(array(), true);
        
        // Load default Config
        $options->merge($this->_getDefaultOptions());
        
        // Load application Config
        $options->merge($this->_getAppOptions());
        
        // Load forced Config
        $options->merge($this->_getForcedOptions());
        
        return $options->toarray();
    }

    /**
     * Get default options
     *
     * @return Zend_Config
     */
    private function _getDefaultOptions()
    {
        // Sets array of standard config
        $optionsArr = array(
            'phpSettings' => array(
                'date' => array(
                    'timezone' => 'America/Sao_Paulo',
                ),
            ),
            'resources' => array(
                'session' => array(
                    'save_path' => ROOT_PATH . '/data/sessions/',
                    'use_only_cookies' => false,
                    'remember_me_seconds' => 864000,
                ),
                'theme' => array(
                    'name' => 'default',
                ),
                'frontController' => array(
                    'baseUrl' => preg_replace(
                        '/([^\/]*)$/', '', $_SERVER['PHP_SELF']
                    ),
                    'publicDir' => "public",
                    'throwExceptions' => '0',
                    'params' => array(
                        'noErrorHandler' => '0',
                        'displayExceptions' => '0'
                    ),
                ),
                'router' => true,
                'layout' => array(
                    'layout' => 'default',
                    'contentKey' => 'content',
                ),
                'view' => array(
                    'useStreamWrapper' => false,
                    'doctype' => 'XHTML1_TRANSITIONAL',
                    'contentType' => 'text/html; charset=UTF-8',
                    'charset ' => 'UTF-8',
                    'encoding ' => 'UTF-8',
                ),
                'dojo' => true,
                'contexts' => array(),
                'locale' => array(
                    'default' => 'en',
                ),
                'language' => array(),
                'validate' => array(
                    'translate' => array( 
                        'adapter' => Zend_Translate::AN_ARRAY,
                        'content' => dirname(__FILE__).'/resources/languages/',
                        'scan'    => Zend_Translate::LOCALE_DIRECTORY,
                        //'locale'  => 'pt_BR'
                    ),
                ),
                
            ),
        );

        $options = new Zend_Config($optionsArr, true);
        return $options;

    }
    
    /**
     * Get application options
     *
     * @return Zend_Config
     */
    private function _getAppOptions()
    {
        // Retrieve application options
        $appOptions = new ZLayer_Config_Directory(
            APPLICATION_PATH . 'configs/', APPLICATION_ENV, false
        );
        $options = $appOptions->process();
        return $options;
    }
    
    /**
     * Get forced options
     *
     * @return Zend_Config
     */
    private function _getForcedOptions()
    {
        // Sets array of config enforced for application
        $optionsArr = array(
            'bootstrap' => array(
                'path' => APPLICATION_PATH .
                    'Bootstrap.php',
                'class' => 'Bootstrap',
            ),
            'autoLoaderNameSpaces' => array("ZLayer", "Zend"),
            'pluginPaths' => array(
                'ZLayer_Application_Resource' => 'ZLayer/Application/Resource/',
                'Application_Resource' => APPLICATION_PATH .
                    'resources/',
            ),
            'resources' => array(
                'theme' => array(
                    'basePath' => array(
                        'application' => APPLICATION_PATH . 
                            'themes/',
                    ),
                ),
                'frontController' => array(
                    'moduleControllerDirectoryName' => 'controllers',
                    'moduleDirectory' => array(
                        'application' => APPLICATION_PATH .
                            'modules/',
                    ),
                    'controllerDirectory' => array( 
                        'default' => APPLICATION_PATH .
                            'controllers/',
                    ),
                    'params' => array(
                        'useDefaultControllerAlways' => '0',
                        //'noViewRenderer' => '1',
                        'env' => APPLICATION_ENV,
                        'returnResponse' => '1',
                    ),
                    'plugins' => array(
                        'ZLayer_Controller_Plugin_Theme' => array(
                            'class' => 'ZLayer_Controller_Plugin_Theme',
                            'stackIndex' => '1',
                        ),
        				'ZLayer_Controller_Plugin_Request_LayoutSwitch' => 
                            array('class' => 
                                'ZLayer_Controller_Plugin_Request_LayoutSwitch',
                                'stackIndex' => '2',
                        ),
        				'ZLayer_Controller_Plugin_Request_History' => array(
                            'class' => 'ZLayer_Controller_Plugin_History',
                            'stackIndex' => '3',
                        ),
                        'ZLayer_Controller_Plugin_Request_Plugins' => array(
                            'class' => 
                                'ZLayer_Controller_Plugin_Request_Plugins',
                            'stackIndex' => '4',
                        ),
                        'ZLayer_Controller_Plugin_Request_Dojo' => array(
                            'class' => 'ZLayer_Controller_Plugin_Request_Dojo',
                            'stackIndex' => '5',
                        ),
                        'ZLayer_Controller_Plugin_Request_CssJs' => array(
                            'class' => 'ZLayer_Controller_Plugin_Request_CssJs',
                            'stackIndex' => '6',
                        ),
                        'ZLayer_Controller_Plugin_Request_Messenger' => array(
                            'class' => 'ZLayer_Controller_Plugin_Messenger',
                            'stackIndex' => '7',
                        ),
                        /*
        				'ZLayer_Controller_Plugin_Request_Context' => array(
                            'class' => 'ZLayer_Controller_Plugin_Context',
                            'stackIndex' => '8',
                        ),
                        */
                    ),
                    'actionHelperPaths' => array(
                        'ZLayer_Controller_Action_Helper' =>
                            'ZLayer/Controller/Action/Helper/',
                        'Application_Action_Helper' =>
                            APPLICATION_PATH . 'extras/helpers/actions/',
                    ),
                ),
                'view' => array(
                    'basePath' => null,
                    'scriptPath' => null,
                    'helperPath' => array(
                        'Zend_View_Helper' =>
                            'Zend/View/Helper/',
                        'Zend_Dojo_View_Helper' =>
                            'Zend/Dojo/View/Helper/',
                        'ZLayer_View_Helper' =>
                            'ZLayer/View/Helper/',
                        'ZLayer_Dojo_View_Helper' =>
                            'ZLayer/Dojo/View/Helper/',
                        'Application_View_Helper' =>
                            APPLICATION_PATH . 'extras/helpers/views/',
                        
                    ),
                ),
                'layout' => array(
                    'layoutPath' => null,
                ),
                'modules' => array(),

            ),
            
        );

        $options = new Zend_Config($optionsArr, true);
        return $options;
    }
    
}