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
 * @uses           Zend_Dojo_View_Helper_Dijit
 * @category     ZLayer
 * @package      ZLayer_Dojo
 * @subpackage View
 * @author         Henrique Mattos (henrique at visualworks dot com dot br)
 * @license        http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Dojo_View_Helper_CheckedMultiSelect extends Zend_Dojo_View_Helper_Dijit
{
    
    /**
     * Dojox being used
     * @var string
     */
    protected $_dijit  = 'zlayer.form.CheckedMultiSelect';
    
    /**
    * HTML element type
     * @var string
    */
    protected $_elementType = 'checkbox';
    
    /**
     * Dojo module to use
     * @var string
     */
    protected $_module = 'zlayer.form.CheckedMultiSelect';
    
    /**
     *  dojox.form.CheckedMultiSelect
     *
     * @param  int $id
     * @param  mixed $value
     * @param  array $params  Parameters to use for dijit creation
     * @param  array $attribs HTML attributes
     * @param  array|null $options Select options
     * @return string
     */
    public function checkedMultiSelect($id, $value = null, array $params = array(), array $attribs = array(), array $options = null, array $checkedOptions = null)
    {
        $attribs['name'] = $id;
        if (!array_key_exists('id', $attribs)) {
            $attribs['id'] = $id;
        }
        
        // strip options so they don't show up in markup
        if (array_key_exists('options', $attribs)) {
            unset($attribs['options']);
        }
        
        $html = '';
        if (array_key_exists('store', $params) && is_array($params['store'])) {
            // using dojo.data datastore
            if (false !== ($store = $this->_renderStore($params['store'], $id))) {
                $params['store'] = $params['store']['store'];
                if (is_string($store)) {
                    $html .= $store;
                }
                $html .= $this->_createFormElement($id, $value, $params, $attribs);
                
                // do as normal select
                $attribs = $this->_prepareDijit($attribs, $params, 'element');
                
                unset($attribs['jsId']);
                
                $listsep = $attribs['listsep'];
                unset($attribs['listsep']);
                
                // $html .= $this->view->formSelect($id, $value, $attribs, $options);
                
                //$html .= $this->view->formSelect($id, $value, $attribs, $options);
                return $html;
            }
            unset($params['store']);
        }
    }
    
    /**
    * Render data store element
    *
    * Renders to dojo view helper
    *
    * @param  array $params
    * @return string|false
    */
    protected function _renderStore(array $params, $id)
    {
        if (!array_key_exists('store', $params) || !array_key_exists('type', $params)) {
           return false;
        }
        $this->dojo->requireModule($params['type']);

        $extraParams = array();
        $storeParams = array(
            'dojoType' => $params['type'],
            'jsId'     => $params['store'],
        );

        if (array_key_exists('params', $params)) {
            $storeParams = array_merge($storeParams, $params['params']);
            $extraParams = $params['params'];
        }

        if ($this->_useDeclarative()) {
            if (!$this->_useProgrammaticNoScript()) {
                require_once 'Zend/Json.php';
                $this->dojo->addJavascript('var ' . $storeParams['jsId'] . ";\n");
                $js = $storeParams['jsId'] . ' = '
                . 'new ' . $storeParams['dojoType'] . '('
                .     Zend_Json::encode($extraParams)
                . ");\n";
                $js = "function() {\n$js\n}";
                $this->dojo->_addZendLoad($js);
            }
        }
        return '<div' . $this->_htmlAttribs($storeParams) . '></div>';
    }
}