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
 * @package    ZLayer_Controller
 * @subpackage Plugin
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
 
/**
 * @see Zend_Controller_Plugin_Abstract
 */ 
require_once "Zend/Controller/Plugin/Abstract.php";


/**
 * Plugin for configuration apps with theme
 *
 * @uses       Zend_Controller_Plugin_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Plugin
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Plugin_Theme extends Zend_Controller_Plugin_Abstract
{
    /**
     * $_front - FrontController reference
     *
     * @var Zend_Controller_Front
     */
    private $_front;
    
    /**
     * $_basePaths - Base paths
     *
     * @var array
     */
    private $_basePaths;
    
    /**
     * $_themes - Themes
     *
     * @var array
     */
    private $_themes;

    /**
     * $_view - Zend_View reference
     *
     * @var Zend_View
     */
    private $_view;
    
    /**
     * routeStartup
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request) {
    
        // Set front
        $this->_front = Zend_Controller_Front::getInstance();
        
        // get theme Options
        $themeOptions = $this->_front->getParam('bootstrap')
                                            ->getPluginResource("theme")
                                            ->getOptions();

        // Set base paths
        $this->_basePaths = $themeOptions["basePath"];

        // Set themes
        $themes = array($themeOptions["name"]);
        if (isset($themeOptions["parents"])) {
            $themes = array_merge_recursive($themes,$themeOptions["parents"]);
        }
        $this->_themes = array_reverse($themes);

        // Set view renderer
        $this->_view = $this->_front->getParam('bootstrap')
                                    ->getPluginResource("view")
                                    ->getView();
                                    
        
        
    }

    /**
     * dispatchLoopStartup
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $oRequest)
    {
        
        // Set layout
        $_layout = $this->_front->getParam('bootstrap')
                                ->getPluginResource("layout")
                                ->getLayout();

        // Create empty layoutPath array
        $layoutPathAr = array();
        
        // Loop theme basePath
        foreach ($this->_basePaths as $basePath) {

            foreach ($this->_themes as $theme) {

                // Mount basepath
                $path = $basePath."/".$theme;

                // Populate layoutPath array
                $layoutPathAr[].=$path."/scripts/layouts/";
                
            }
            
        }
        
        // Set layout path
        $_layout->setLayoutPath($layoutPathAr);
        
    }

    /**
     * preDispatch
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $oRequest) {
        
        // Reset script path
        $this->_view->setScriptPath(null);
                
        // Loop theme basePath
        foreach ($this->_basePaths as $basePath) {

            foreach ($this->_themes as $theme) {

                // Mount basepath
                $path = $basePath."/".$theme . "/scripts";

                // Set module name
                $module = $oRequest->getModuleName();
                
                // Check if request is to module
                if ( $module!="default" ) {
                    $scriptPath = $path."/modules/".$module."/";
                } else {
                    $scriptPath = $path."/controllers/";
                }
                
                // format scriptPath
                $scriptPath = preg_replace("/\/\//", "/", $scriptPath);
                
                // Add view scriptpath for module
                $this->_view->addScriptPath($scriptPath);
                
            }

        }
        
    }

}    