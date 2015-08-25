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
 * ZLayer_Controller_Plugin_Request_Context_Abstract
 *
 * @uses       Zend_Controller_Plugin_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Plugin
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class ZLayer_Controller_Plugin_Context_Abstract extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Controller_Front
     */
    private $_front;
    
    /**
     * @var string
     */
    private $_format;
    
    /**
     * @var Zend_Controller_Action_Helper_ViewRenderer
     */
    private $_viewRenderer;
    
    /**
     * @var Zend_View
     */
    private $_view;
    
    /**
     * Called before Zend_Controller_Front enters its dispatch loop.
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->_front = Zend_Controller_Front::getInstance();
        $this->_viewRenderer = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer');
        $this->_view = $this->_viewRenderer->view;
        $this->_format = $this->_front->getRequest()->getParam("__format");
    }
    
    /**
     * Called after an action is dispatched by Zend_Controller_Dispatcher.
     *
     * This callback allows for proxy or filter behavior. By altering the
     * request and resetting its dispatched flag (via
     * {@link Zend_Controller_Request_Abstract::setDispatched() setDispatched(false)}),
     * a new action may be specified for dispatching.
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        switch ($this->_format) {
        
            case "dojo-data":
            case "dojo-auto":
        
                $this->_checkDojoParams();
                break;
        }
    }
    
    /**
     * dispatchLoopShutdown
     *
     * @return void
     */
    public function dispatchLoopShutdown() 
    {
        //@todo response in html-xml, amf ...
        switch ($this->_format) {
            
            case 'json':
            case 'xml':
                $this->_responseException();
                break;
                
            case 'html-json':
            case 'html-xml':
                $this->_htmlAjaxResponse();
                break;
                
            case "html":
            case null:
                $this->_responseHtml();
                break;
                
            case "dojo-data":
            case "dojo-auto":
                $this->_responseException();
                $this->_dojoResponse();
                break;
                
            default:
                break;
                
        }
    }
    
    /**
     * _htmlAjaxResponse
     *
     * @return void
     */
    private function _htmlAjaxResponse() {
        
        $isException = $this->_viewRenderer->getResponse()->isException();
        $respCode = $this->_viewRenderer->getResponse()->getHttpResponseCode();
        
        if ($isException or $respCode != 200) {
            if ($isException) {
                $this->_responseException();
            }
            $contextSwitch = Zend_Controller_Action_HelperBroker::getExistingHelper('ContextSwitch');
            $contextSwitch->postJsonContext();
            return;
        }
            
        $data["body"] = $this->_viewRenderer->getResponse()->getBody();
        
        // HeadScript
        $headScript = $this->_view->headScript();
        $container = $headScript->getContainer();
        $container->ksort();
        
        $items = array();
        foreach ($headScript as $item) {
            if (isset($item->attributes["src"]))
            $items[] = $item->attributes["src"];
        }
        
        if (count($items)>0) {
            $data["headScript"] = $items;
        }
        
        // HeadLink
        $headLink = $this->_view->headLink();
        $container = $headLink->getContainer();
        $container->ksort();
        
        $items = array();
        foreach ($headLink as $item) {
            $items[] = $item->href;
        }
        
        if (count($items)>0) {
            $data["headLink"] = $items;
        }
        
        // Messenger
        if (isset($this->_view->messenger)) {
            $data["messenger"] = $this->_view->messenger;
        }
        
        // Dojo
        $dojo = $this->_view->dojo();
        if ($dojo->isEnabled()) {
        
        
            $dojoData["modules"] = $dojo->getModules();
            $dojoData["stylesheetModules"] = $dojo->getStylesheetModules();
            $dojoData["djConfig"] = $dojo->getDjConfig();
        
        
            $onLoadActions = array();
            foreach ($dojo->_getZendLoadActions() as $callback) {
                $onLoadActions[] = $callback;
            }
            foreach ($dojo->getOnLoadActions() as $callback) {
                $onLoadActions[] = $callback;
            }
            $dojoData["onLoad"] = $onLoadActions;
        
        
            if ($dojo->useCdn()) {
                $source = $dojo->getCdnBase()
                . $dojo->getCdnVersion()
                . $dojo->getCdnDojoPath();
            } else {
                $source = $dojo->getLocalPath();
            }
        
            if ($dojo->useCdn()) {
                $base = $dojo->getCdnBase()
                . $dojo->getCdnVersion();
            } else {
                $base = $dojo->_getLocalRelativePath();
            }
        
            $registeredStylesheets = $dojo->getStylesheetModules();
            foreach ($registeredStylesheets as $stylesheet) {
                $themeName     = substr($stylesheet, strrpos($stylesheet, '.') + 1);
                $stylesheet    = str_replace('.', '/', $stylesheet);
                $stylesheets[] = $base . '/' . $stylesheet . '/' . $themeName . '.css';
            }
        
            foreach ($dojo->getStylesheets() as $stylesheet) {
                $stylesheets[] = $stylesheet;
            }
        
            $stylesheets[] = $base . '/dojo/resources/dojo.css';
            $dojoData["stylesheets"] = $stylesheets;
            $data["dojo"] = $dojoData;
        
        }
        
        $contextSwitch = Zend_Controller_Action_HelperBroker::getExistingHelper('contextSwitch');
        $contextSwitch->setAutoJsonSerialization(false);
        
        switch ($this->_format) {
            case 'html-json':
                
                require_once 'Zend/Json.php';
                $response = Zend_Json::encode($data);
                break;
        
            default:
        }
        
        $this->_viewRenderer->getResponse()->setBody($response);
        
        
    }
    
    /**
     * _checkDojoParams
     *
     * @return void
     */
    private function _checkDojoParams() {
        if (!$this->_front->getRequest()->getParam("__items")) {
            
            $this->_viewRenderer->getResponse()->setException(new ZLayer_Controller_Plugin_Exception('The parameter __items is required in the request'));
            
        } else  {
            $vars = $this->_view->getVars();
            /*
            if (!isset($vars[$this->_front->getRequest()->getParam("__data")])) {
                //$this->_viewRenderer->getResponse()->setException(new ZLayer_Controller_Plugin_Exception('The key variable in the parameter __data was not found in view'));
            }
            */
        }
    }
    
    /**
     * _dojoResponse
     *
     * @return void
     */
    private function _dojoResponse() {
        
        if (!$this->_viewRenderer->getResponse()->isException()) {
            
            $id = $this->_front->getRequest()->getParam("__identifier");
            $label = $this->_front->getRequest()->getParam("__label");
            $itemsVar = $this->_front->getRequest()->getParam("__items");
            $numRowsVar = $this->_front->getRequest()->getParam("__numRows");

            $items = $this->_view->getVar($itemsVar);
            $numRows = $this->_view->getVar($numRowsVar);
            
            $array = array(
                'identifier'    => $id,
                'items'         => $items,
                'label'         => $label,
                'numRows'       => $numRows
            );
            
            $dojoData = Zend_Json::encode($array);
            //$dojoData = new Zend_Dojo_Data($id,$items,$label);
            $this->_viewRenderer->getResponse()->setBody($dojoData);
            //$this->_view->_helper->autoCompleteDojo($data);
        
        } else {
        
            $contextSwitch = Zend_Controller_Action_HelperBroker::getExistingHelper('ContextSwitch');
            $contextSwitch->postJsonContext();
        
        }
        
    }
    
    /**
     * _responseException
     *
     * @return void
     */
    private function _responseException(){

        if (!$this->_viewRenderer->getResponse()->isException()) {
            return;
        }
            
        $frontOptions = $this->_front->getParam('bootstrap')
                                     ->getPluginResource("frontController")
                                     ->getOptions();
        
        if (!isset($frontOptions['params']['displayExceptions'])) {
            return;
        }
        
        if ($frontOptions['params']['displayExceptions'] === false) {
           return;
        }
        
        $exceptions = $this->_viewRenderer->getResponse()->getException();
        $this->_view->exception = array();
        $this->_view->trace = array();
        foreach($exceptions as $exception) {
            $message = $exception->getMessage();
            $trace = $exception->getTraceAsString();
            $this->_view->exception[] = $message;
            $this->_view->trace[] = $trace;
        }
     
    }
    
    /**
     * _responseHtml
     *
     * @return void
     */
    private function _responseHtml() {
        
        $body = $this->_viewRenderer->getResponse()->getBody();
        $respHeader = "{$this->_view->doctype()}
        <html>
        <head>
        {$this->_view->headMeta()}
        {$this->_view->headTitle()}
        {$this->_view->headStyle()}
        {$this->_view->headLink()}
        {$this->_view->headScript()}
        {$this->_view->dojo()}
        </head>";
        
        $respBody = "<body>{$body}</body>";

        $respFooter = "</html>";
        $this->_viewRenderer->getResponse()->setBody($respHeader.$respBody.$respFooter);
        
    }
}







