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
 * @category   Application
 * @copyright  Copyright (c) 2005-2011 João Paulo Faria (http://www.jpfaria.com.br)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
 
/**
 * @see Zend_Controller_Plugin_Abstract
 */ 
require_once "Zend/Controller/Plugin/Abstract.php";


/**
 * Example plugin on application
 *
 * @uses       Zend_Controller_Plugin_Abstract
 * @category   Application
 * @copyright  Copyright (c) 2005-2011 João Paulo Faria (http://www.jpfaria.com.br)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Example_Plugin_Example extends Zend_Controller_Plugin_Abstract
{
	/**
     * dispatchLoopStartup
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $oRequest) {
	}
    
    /**
     * preDispatch
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
	public function preDispatch(Zend_Controller_Request_Abstract $oRequest) {
    }
    
    /**
     * postDispatch
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
	public function postDispatch(Zend_Controller_Request_Abstract $oRequest) {
    }
    
    /**
     * dispatchLoopShutdown
     *
     * @return void
     */
	public function dispatchLoopShutdown() {
    }
}    