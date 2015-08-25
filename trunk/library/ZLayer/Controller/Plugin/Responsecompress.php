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
 * ZLayer_Controller_Plugin_Outputcompress
 *
 * @uses       Zend_Controller_Plugin_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Plugin
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Plugin_Responsecompress extends Zend_Controller_Plugin_Abstract
{
    private $_compression = 1;
    
    /**
     * @param int $compression [optional] The gzip compression level to use when compressing the output
     */
    public function __construct($compression = 1)
    {
        $this->_compression = $compression;
    }
    
    public function dispatchLoopShutdown()
    {
        if (extension_loaded('zlib')) {
            if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
                return;
            } elseif (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false) {
                $this->_response->setHeader('Content-Encoding', 'x-gzip', true);
            } elseif (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                $this->_response->setHeader('Content-Encoding', 'gzip', true);
            } else {
                return;
            }
            
            $contents = $this->_response->getBody();
            $size = strlen($contents);
            $contents = gzcompress($contents, $this->_compression);
            
            $length = strlen($contents);
            $this->_response->setHeader('Content-Length', $length, true);
            
            $this->_response->setBody($contents);
        }
    }
}