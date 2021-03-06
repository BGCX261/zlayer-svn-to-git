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
 * ZLayer_Controller_Plugin_Messenger
 *
 * @uses       Zend_Controller_Plugin_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Plugin
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Plugin_Messenger extends Zend_Controller_Plugin_Abstract
{
    /**
     * dispatchLoopShutdown
     *
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        $messenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        
        $contextSwitch = Zend_Controller_Action_HelperBroker::getExistingHelper('ContextSwitch');
        $contextParam = $contextSwitch->getContextParam();
        
        $front = Zend_Controller_Front::getInstance();
        $format = $front->getRequest()->getParam($contextParam);
        
        $view = Zend_Controller_Action_HelperBroker::getExistingHelper('viewRenderer')->view;
        
        $namespaces = array('error','warning','notice','success');
        
        foreach($namespaces as $namespace) {
            
            $messenger->setNamespace($namespace);
            
            $messages = null;
            
            if ($format != "html" and !is_null($format)) {
                
                if ($messages = $messenger->getCurrentMessages()) {
                    $messenger->clearCurrentMessages();
                } else {
                    $messages = $messenger->getMessages();
                }
                
            } else {
                $messages = $messenger->getMessages();
                
            }
            
            if (!is_array($messages)) continue;
            
            if (count($messages)>0) {
            
                if (!isset($view->messenger))
                    $view->messenger = array();
                
                $view->messenger[$namespace] = $messages;
            
            }
            
        }
        
    }

}    