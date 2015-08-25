<?php
/**
 * Zend Framework
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
 * @package    ZLayer_Dojo
 * @subpackage View
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Dojo_View_Helper_Dijit */
require_once 'Zend/Dojo/View/Helper/Dijit.php';

/**
 * Arbitrary dijit support
 *
 * @uses       Zend_Dojo_View_Helper_CustomDijit
 * @category   ZLayer
 * @package    ZLayer_Dojo
 * @subpackage View
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Dojo_View_Helper_CustomElementDijit extends Zend_Dojo_View_Helper_Dijit
{
    
    public function customElementDijit($id = null, $value = null, array $params = array(), array $attribs = array())
    {
        if (null === $id) {
            return $this;
        }
        
        if (!array_key_exists('dojoType', $params)) {
            require_once 'Zend/Dojo/View/Exception.php';
            throw new ZLayer_Dojo_View_Exception('No dojoType specified; cannot create dijit');
        } else {
            $this->_dijit  = $params['dojoType'];
            $this->_module = $params['dojoType'];
            unset($params['dojoType']);
        }
        
        if (array_key_exists('elementType', $params)) {
            $this->_elementType  = $params['elementType'];
            unset($params['elementType']);
        }

        return $this->_createFormElement($id, $value, $params, $attribs);
    }
    
}
