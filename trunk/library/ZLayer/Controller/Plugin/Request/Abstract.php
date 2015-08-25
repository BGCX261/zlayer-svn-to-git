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
* @see ZLayer_Controller_Request_Options
*/
require_once "ZLayer/Controller/Request/Options.php";

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
abstract class ZLayer_Controller_Plugin_Request_Abstract extends Zend_Controller_Plugin_Abstract
{
    /**
    * @var ZLayer_Controller_Request_Options
    */
    private $_options = null;
    
    /**
     * _getOptions
     *
     * @return ZLayer_Controller_Request_Options
     */
    protected function _getOptions() {
        
        if ($this->_options === null) {
            $this->options = new ZLayer_Controller_Request_Options();
        }
        
        return $this->options;
    }

}    