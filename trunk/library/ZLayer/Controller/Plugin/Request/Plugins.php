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
 * ZLayer_Controller_Plugin_Request_Abstract
 *
 * @uses       Zend_Controller_Plugin_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Plugin
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Plugin_Request_Plugins extends ZLayer_Controller_Plugin_Request_Abstract
{
	/**
     * routeShutdown
     *
     * @throws ZLayer_Controller_Plugin_Exception When plugin not found
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return void
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $oRequest) {

        // Vars
        $reqOptions = $this->_getOptions()->getRequestOptions($oRequest);
        
    
        // Register plugins
        if (isset($reqOptions["plugins"])) {
            
            $front = Zend_Controller_Front::getInstance();
            
            $plugins = $reqOptions["plugins"];
            
            foreach ( $plugins as $key => $name ) {
                
                if (class_exists($name)) {
                    $front->registerPlugin(new $name());
                } else {
                    throw new ZLayer_Controller_Plugin_Exception("Plugin {$name} not found");
                }
                
            }
        }
        
    }
}