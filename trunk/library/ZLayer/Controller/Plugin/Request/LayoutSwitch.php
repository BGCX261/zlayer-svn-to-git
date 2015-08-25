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
 * ZLayer_Controller_Plugin_LayoutSwitch
 *
 * @uses       ZLayer_Controller_Plugin_Request_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Plugin
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Plugin_Request_LayoutSwitch extends ZLayer_Controller_Plugin_Request_Abstract
{
    /**
     * dispatchLoopStartup
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $oRequest) 
    {
        // Vars
        $reqOptions = $this->_getOptions()->getRequestOptions($oRequest);
        
        // Switch Layout
        if (isset($reqOptions["layout"])) {
            
            $layoutOpt = $reqOptions["layout"];
            $layout = Zend_Layout::getMvcInstance();
            
            if (isset($layoutOpt["layout"])){
                $layout->setLayout($layoutOpt["layout"]);
            }
            
            if (isset($layoutOpt["contentKey"])){
                $layout->setContentKey($layoutOpt["contentKey"]);
            }
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
        $history = ZLayer_Controller_Request_History::getInstance();
        $last = $history->getLast();
        
        $format = $oRequest->getParam("__format");
        
        if (isset($last["layout"]) and ($format=="html-json" or $format=="html-xml")) {
            
            $layout = Zend_Layout::getMvcInstance();
            $layoutName = $layout->getLayout();
        
            if ($last["layout"] != $layoutName) {
                
                //$router = $this->getFrontController()->getRouter();
                //$url    = $router->assemble($params, 'default', true);
                //exit(print_r($history->getCurrent(),true));
                //exit($last["layout"] . " - " . $layoutName);
                //$oRequest->setParam("__format","html");
                //$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                //$redirector->gotoSimple($oRequest->getActionName(), $oRequest->getControllerName(), $oRequest->getModuleName());
            }
        
        }
    }

}    