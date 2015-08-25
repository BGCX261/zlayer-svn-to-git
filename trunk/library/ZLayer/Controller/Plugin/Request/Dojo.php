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
 * ZLayer_Controller_Plugin_Request_Dojo
 *
 * @uses       ZLayer_Controller_Plugin_Request_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Plugin
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Plugin_Request_Dojo extends ZLayer_Controller_Plugin_Request_Abstract
{
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
        
        $this->_view = $front->getParam('bootstrap')
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
        // Hack DOJO
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative();
        
        $contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
        $contextParam = $contextSwitch->getContextParam();
        $format = $oRequest->getParam($contextParam);
        
        // Inject javascript vars
        if (!$format or $format == "html" or $format == "html-json" or $format == "html-xml") {
        
            $this->_view->dojo()->setDjConfigOption('ZlBaseUrl', $this->_view->baseUrl());
            $this->_view->dojo()->setDjConfigOption('ZlBaseThemeUrl', $this->_view->baseThemeUrl());
            $this->_view->dojo()->setDjConfigOption('ZlModule', $oRequest->getModuleName());
            $this->_view->dojo()->setDjConfigOption('ZlController', $oRequest->getControllerName());
            $this->_view->dojo()->setDjConfigOption('ZlAction', $oRequest->getActionName());
            
        }
        
    }
    
    /**
     * postDispatch
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
    public function postDispatch(Zend_Controller_Request_Abstract $oRequest) 
    {
        // Vars
        $options = $this->_getOptions()->getMergedRequestOptions($oRequest);
        $front = Zend_Controller_Front::getInstance();
        
        // Dojo
        if (isset($options["dojo"])) {
        
            if (count($options["dojo"])>0) {
            
                $djOptions = $options["dojo"];
                
                if (isset($djOptions["djConfig"])) {
                    $array = array_merge($djOptions['djConfig'],$this->_view->dojo()->getDjConfig());
                    $this->_view->dojo()->setDjConfig($array);
                    unset($djOptions["djConfig"]);
                }
                
                $this->_view->dojo()->setOptions($djOptions);
            }
            
            Zend_Dojo::enableView($this->_view);

        }
        
    }
    
}    