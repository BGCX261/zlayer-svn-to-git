<?php

class Error_ErrorController extends ZLayer_Controller_Action_Abstract
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        if (!$errors) {
            return;
        }
        
        $messenger = $this->_helper->getHelper('Messenger');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $messenger->error("addressNotFound");
                break;
                
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $messenger->error("applicationError");
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $flashMessenger = $this->_helper->getHelper('FlashMessenger');
            foreach ($flashMessenger->getCurrentMessages() as $messageArray ) {
                $key = key($messageArray);
                $log->crit($messageArray[$key]);
            }
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->params = $errors->request->getParams();
        $this->view->requestUri = $errors->request->getRequestUri();
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}

