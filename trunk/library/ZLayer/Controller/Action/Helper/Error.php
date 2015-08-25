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
 * @subpackage ZLayer_Controller_Action_Helper
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Error 
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage ZLayer_Controller_Action_Helper
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Action_Helper_Error extends Zend_Controller_Action_Helper_Abstract
{
    const PAGE_NOT_FOUND = '404';
    const APPLICATION_ERROR = '500';
    
    /**
     * direct
     *
     * @param string $message
     * @param int $code
     * @return void
     */
    public function direct($key, $code = null)
    {
        $this->byKey($key, $code);
    }
    
    /**
     * byKey
     *
     * @param string $key
     * @param int $code
     * @return void
     */
    public function byKey($key, $code = null)
    {
        $messenger = Zend_Controller_Action_HelperBroker::getStaticHelper('Messenger');
        $messenger->error($key);
    
        if (is_null($code)) 
            $code = ZLayer_Controller_Action_Helper_Error::PAGE_NOT_FOUND;
        
        $this->_setHttpResponseCode($code);
    }
    
    
    /**
     * Manual
     *
     * @param string $message
     * @param string $label
     * @param int $code
     * @return void
     */
    public function manual($message, $label = null, $code = null)
    {
        $messenger = Zend_Controller_Action_HelperBroker::getStaticHelper('Messenger');
        $messenger->addMessage($message,
        ZLayer_Controller_Action_Helper_Messenger::ERROR, $label);
    
        if (is_null($code)) 
            $code = ZLayer_Controller_Action_Helper_Error::PAGE_NOT_FOUND;
        
        $this->_setHttpResponseCode($code);
    }
    
    /**
     * _setHttpResponseCode
     *
     * @param array $code
     * @return void
     */
    private function _setHttpResponseCode($code) {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getExistingHelper('viewRenderer');
        $viewRenderer->getResponse()->setHttpResponseCode($code);
        
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('noErrorHandler', true);
    }
    
    
}