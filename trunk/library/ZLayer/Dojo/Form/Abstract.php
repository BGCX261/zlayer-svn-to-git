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

/**
 * @see ZLayer_Form_Abstract
 */
require_once "ZLayer/Form/Abstract.php";

/**
 * Zlayer abstraction for the Zend Dojo Abstract
 *
 * @uses       ZLayer_Form
 * @category   ZLayer
 * @package    ZLayer_Dojo
 * @subpackage Form
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class ZLayer_Dojo_Form_Abstract extends ZLayer_Form_Abstract {

    /**
     * Constructor
     *
     * @param  string $dojoType
     * @return void
     */
    public function __construct($dojoType = null) {
        
        // enable dojo form
        $this->_enableDojo();
        
        $this->addPrefixPath('ZLayer_Dojo_Form_Decorator', 'ZLayer/Dojo/Form/Decorator', 'decorator')
             ->addPrefixPath('ZLayer_Dojo_Form_Element', 'ZLayer/Dojo/Form/Element', 'element')
             ->addElementPrefixPath('ZLayer_Dojo_Form_Decorator', 'ZLayer/Dojo/Form/Decorator', 'decorator')
             ->addDisplayGroupPrefixPath('ZLayer_Dojo_Form_Decorator', 'ZLayer/Dojo/Form/Decorator')
             //->setDefaultDisplayGroupClass('ZLayer_Dojo_Form_DisplayGroup')
             ->addPrefixPath('Application_Dojo_Form_Decorator', APPLICATION_PATH . 'application/extras/forms/decorators', 'decorator')
             ->addPrefixPath('Application_Dojo_Form_Element', APPLICATION_PATH . 'application/extras/forms/elements', 'element')
             ->addElementPrefixPath('Application_Dojo_Form_Decorator', APPLICATION_PATH . 'application/extras/forms/decorators', 'decorator')
             ->addDisplayGroupPrefixPath('Application_Dojo_Form_Decorator', APPLICATION_PATH . 'application/extras/forms/decorators');
        
        parent::__construct();
        
        // Set dojo validation options
        $this->_setDojoValidationOptions();
        
    }
    
    /**
     * Enable dojo form
     *
     * @return void
     */
    private function _enableDojo() {
        Zend_Dojo::enableForm($this);
    } 

    /**
     * Set the view object
     *
     * Ensures that the view object has the dojo view helper path set.
     *
     * @param  Zend_View_Interface $view
     * @return Zend_Dojo_Form_Element_Dijit
     */
    public function setView(Zend_View_Interface $view = null) {
        
        if (null !== $view) {
            if (false === $view->getPluginLoader('helper')->getPaths('ZLayer_Dojo_View_Helper')) {
                $view->addHelperPath('ZLayer/Dojo/View/Helper', 'ZLayer_Dojo_View_Helper');
            }
        }
        
        return parent::setView($view);
        
    }

    /**
     * Get options
     *
     * @return Zend_Config
     */
    protected function _getOptions() {

        if ($formCache = ZLayer_Cache::get('form')) {
            
            $cacheId = ZLayer_Cache::id('form_dojo_'.$this->_module.$this->_form);
            
            if ($formCache->test($cacheId) !== false) {
                
                $this->_options = $formCache->load($cacheId, true);
                return $this->_options;
                
            } else {
                
                $this->_makeDojoOptions();
                $formCache->save($this->_options, $cacheId, array(), null);
                return $this->_options;
                
            }
            
        } else {
            
            $this->_makeDojoOptions();
            return $this->_options;
            
        }
        
    }
    
    /**
     * Make options
     *
     * @return Zend_Config
     */
    private function _makeDojoOptions() {
        // Set form options
        parent::_getOptions();
        
        // Set dojo default options
        $this->_setDojoDefaultOptions();
        
        // Set dojo form options
        $this->_setDojoFormOptions();
        
        // Set dojo auto options
        $this->_setDojoAutoOptions();
    }

    /**
     * Set default options
     *
     * @return void
     */
    private function _setDojoDefaultOptions() {
        
    }

    /**
     * Set form options
     *
     * @return void
     */
    private function _setDojoFormOptions() {
        
        $array = array(
            $this->_configPath . '/forms/dojo/',
        );
        $this->_setFormOptionsFromDirs($array);
    }

    /**
     * Set auto options
     *
     * @return void
     */
    private function _setDojoAutoOptions() {

        /*
        $array = array(
                'DijitElement',
                'Errors',
                array(array('data'=>'HtmlTag'),array('tag'=>'td')),
                array('Label',array('tag'=>'td')),
                array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
                );
        
        echo "<pre>";
        print_r($array);
        echo "</pre>";
        */
        
        /*
        if (!isset($this->_options->attribs)) {

            // Sets array of standard config
            $optionsAr = array(
                'attribs' => array(
                    'onsubmit' => 'return this.validate()',
                ),
            );

            $this->_mergeOptions($optionsAr);
        }
        */
    }
    
    /**
     * importdisplayGroups
     *
     * @return void
     */
    protected function _importdisplayGroups() {
        parent::_importdisplayGroups('Dojo');
    }
    
    /**
     * Set validation options
     *
     * @return void
     */
    private function _setDojoValidationOptions() {
    
        foreach ($this->getElements() as $elementName => $element ) {
            
            foreach ($element->getValidators() as $validatorName => $validatorItem) {
                
                // apply required if validor is NotEmpty
                if ($validatorItem instanceof Zend_Validate_NotEmpty) {
                    $element->setRequired(true);
                } 
                
                // get real type for field
                $type = null;
                if ($element instanceof ZLayer_Dojo_Form_Element_CustomElementDijit) {
                    if (isset($this->_options->elements->$elementName->validator))
                        $type = ucfirst($this->_options->elements->$elementName->validator);
                } else {
                    $typeArray = explode("_", $element->getType());
                    $type = end($typeArray);
                }
                
                if (!$type) continue;
                    
                // load the adapter of validator
                $loader = new Zend_Loader_PluginLoader();
                $loader->addPrefixPath('ZLayer_Dojo_Form_Validator_' . $type, 'ZLayer/Dojo/Form/Validator/' . $type);
                
                $validatorNameArray = explode("_", $validatorName);
                $validatorKey = end($validatorNameArray);
                
                $plugin = ucfirst($validatorKey);
                
                if ($class = $loader->load(ucfirst($plugin),false)) {
                    
                    $adapter = new $class($validatorItem);
                    if ( $adapterArray = $adapter->getDojoOptions()) {
                        $element->setOptions($adapterArray);
                    }
                    
                }
                
            }
            
        }
            
    }
    

}