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
 * @see ZLayer_Controller_Plugin_Request_Abstract
 */
require_once "ZLayer/Controller/Plugin/Request/Abstract.php";

/**
 * ZLayer_Controller_Plugin_Request_CssJs
 *
 * @uses       ZLayer_Controller_Plugin_Request_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Plugin
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Plugin_Request_CssJs extends ZLayer_Controller_Plugin_Request_Abstract
{
    /**
     * @var array
     */
    protected $_frontOptions;

    /**
     * @var array
     */
    protected $_themeOptions;

    /**
     * @var array
     */
    protected $_themes;

    /**
     * @var Zend_View
     */
    protected $_view;

    /**
     * Called before Zend_Controller_Front begins evaluating the
     * request against its routes.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function routeStartup(Zend_Controller_Request_Abstract $oRequest) {
        $front = Zend_Controller_Front::getInstance();

        $this->_frontOptions = $front->getParam('bootstrap')
                                     ->getPluginResource("frontController")
                                     ->getOptions();

        $this->_themeOptions = $front->getParam('bootstrap')
                                     ->getPluginResource("theme")
                                     ->getOptions();

        // Set themes
        $themes = array($this->_themeOptions["name"]);
        if (isset($this->_themeOptions["parents"])) {
            $themes = array_merge_recursive($themes, $this->_themeOptions["parents"]);
        }
        $this->_themes = array_reverse($themes);



        $this->_view = $front->getParam('bootstrap')
                             ->getPluginResource("view")
                             ->getView();
    }

    /**
     * dispatchLoopShutdown
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $oRequest) {
        $contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
        $contextParam = $contextSwitch->getContextParam();
        $format = $oRequest->getParam($contextParam);

        if (!$format) {

            // Vars
            $front = Zend_Controller_Front::getInstance();
            $reqOptions = $this->_getOptions()->getRequestOptions($front->getRequest());

            // Auto include layout css/js
            // @todo Include css/js from alternative directories if public folder for the theme
            //       is founded in other folder
            if (isset($reqOptions["layout"])) {
                $layout = $reqOptions["layout"];
            } else {

                $layout = $front->getParam('bootstrap')
                                ->getPluginResource("layout")
                                ->getOptions();
            }

            $jsFind = false;
            $layout = $layout["layout"];
            $publicPath = ROOT_PATH . $this->_frontOptions['publicDir'];

            foreach ($this->_themes as $theme) {

                $themePath = '/themes/' . $theme;
                $cssUri = $themePath . "/layouts/{$layout}.css";
                $jsUri = $themePath . "/layouts/{$layout}.js";

                if (is_file($publicPath . "/" . $cssUri)) {
                    $this->_view->headLink()->appendStylesheet($this->_view->baseUrl($cssUri));
                }

                if (is_file($publicPath . "/" . $jsUri) and !$jsFind) {
                    $this->_view->headScript()->appendFile($this->_view->baseUrl($jsUri));
                    $jsFind = true;
                }
            }
        }

        
    }

    /**
     * postDispatch
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
    public function postDispatch(Zend_Controller_Request_Abstract $oRequest) {
        // Vars
        $module = $oRequest->getModuleName();
        $controller = $oRequest->getControllerName();
        $action = $oRequest->getActionName();

        // Auto include action css/js
        // @todo Include css/js from alternative directories if public folder for the theme
        //       is founded in other folder

        $jsFind = false;
        $publicPath = ROOT_PATH . $this->_frontOptions['publicDir'];

        foreach ($this->_themes as $theme) {

            $themePath = '/themes/' . $theme;

            if ($module == "default") {
                $cssUri = $themePath . "/controllers/{$controller}/{$action}.css";
                $jsUri = $themePath . "/controllers/{$controller}/{$action}.js";
            } else {
                $cssUri = $themePath . "/modules/{$module}/{$controller}/{$action}.css";
                $jsUri = $themePath . "/modules/{$module}/{$controller}/{$action}.js";
            }

            if (is_file($publicPath . "/" . $cssUri)) {
                $this->_view->headLink()->appendStylesheet($this->_view->baseUrl($cssUri));
            }

            if (is_file($publicPath . "/" . $jsUri) and !$jsFind) {
                $this->_view->headScript()->appendFile($this->_view->baseUrl($jsUri));
                $jsFind = true;
            }
        }
    }

}

