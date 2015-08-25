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
 * @package    ZLayer_Dojo
 * @subpackage View
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
/**
 * @see Zend_Dojo_View_Helper_Form
 */
require_once "Zend/Dojo/View/Helper/Form.php";

/**
 * ZLayer_Dojo_View_Helper_Form
 *
 * @uses       Zend_Dojo_View_Helper_Form
 * @category   ZLayer
 * @package    ZLayer_Dojo
 * @subpackage View
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Dojo_View_Helper_Form extends Zend_Dojo_View_Helper_Form 
{
	/**
     * dijit.form.Form
     *
     * @param  string $id
     * @param  null|array $attribs HTML attributes
     * @param  false|string $content
     * @return string
     */
    public function form($id, $attribs = null, $content = false)
    {
        if (array_key_exists('dojoType', $attribs)) {
            $this->_dijit  = $attribs['dojoType'];
            $this->_module = $attribs['dojoType'];
            unset($attribs['dojoType']);
        }
        
        return parent::form($id, $attribs, $content);
    }
}