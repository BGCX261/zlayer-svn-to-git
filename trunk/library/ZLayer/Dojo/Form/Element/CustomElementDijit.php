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
 * @subpackage Form
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Dojo_Form_Element_Dijit */
require_once 'Zend/Dojo/Form/Element/Dijit.php';

/**
 * Zlayer abstraction for the Zend Dojo Abstract
 *
 * @uses       Zend_Dojo_Form_Element_Dijit
 * @category   ZLayer
 * @package    ZLayer_Dojo
 * @subpackage Form
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Dojo_Form_Element_CustomElementDijit extends Zend_Dojo_Form_Element_Dijit {

    /**
     * Use CustomDijit dijit view helper
     * @var string
     */
    public $helper = 'CustomElementDijit';
    
	/**
     * Set object state from options array
     *
     * @param  array $options
     * @return Zend_Form_Element
     */
    public function setOptions(array $options)
    {
        if (isset($options['prefixPath'])) {
            $this->addPrefixPaths($options['prefixPath']);
            unset($options['prefixPath']);
        }

        if (isset($options['disableTranslator'])) {
            $this->setDisableTranslator($options['disableTranslator']);
            unset($options['disableTranslator']);
        }

        unset($options['options']);
        unset($options['config']);

        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (in_array($method, array('setTranslator', 'setPluginLoader', 'setView'))) {
                if (!is_object($value)) {
                    continue;
                }
            }

            $return = $this->$method($value);
            if (!($return instanceof Zend_Dojo_Form_Element_Dijit)) {
                $this->setAttrib($key, $value);
            }
            
        }
        return $this;
    }
    
    /**
     * __call
     *
     * @param  string $name  Method name
     * @param  array $arguments Arguments
     * @return mixed
     */
    public function __call($method, array $args) {

        $action = substr($method, 0, 3);
        $attribute = lcfirst(substr($method, 3));
    	
        switch ($action) {
            case 'get':
                if (!$this->hasDijitParam(lcfirst($attribute)))
                    return false;
                return $this->getDijitParam(lcfirst($attribute));
                break;
            case 'set':
                $this->setDijitParam(lcfirst($attribute), $args[0]);
                return $this;
                break;
            default:
                if ($return = parent::__call($method, $args) )
                    return $return;
                break;
        }
        
        
    }
    

}