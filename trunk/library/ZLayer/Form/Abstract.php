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
 * @package    ZLayer_Form
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Form
 */
require_once "Zend/Form.php";

/**
 * Zlayer abstraction for the Zend Dojo Form
 *
 * @uses       Zend_Form
 * @category   ZLayer
 * @package    ZLayer_Form
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class ZLayer_Form_Abstract extends Zend_Form 
{
    /**
     * $_options - Options database
     *
     * @var Zend_Config
     */
    protected $_options;
    
    /**
     *
     * @var string
     */
    protected $_basePath;
    
    /**
     *
     * @var string
     */
    protected $_configPath;
    
    /**
     *
     * @var string
     */
    protected $_formConfigFile;
    
    /**
     *
     * @var string
     */
    protected $_form;
    
    /**
     *
     * @var string
     */
    protected $_module;
    
    /**
     *
     * @var string
     */
    protected $_extraDisplayGroups;
    
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        $this->addPrefixPath('ZLayer_Form_Decorator', 'ZLayer/Form/Decorator', 'decorator')
             ->addPrefixPath('ZLayer_Form_Element', 'ZLayer/Form/Element', 'element')
             ->addElementPrefixPath('ZLayer_Filter', 'ZLayer/Filter', 'filter')
             ->addElementPrefixPath('ZLayer_Validate', 'ZLayer/Validate', 'validate')
             ->addElementPrefixPath('ZLayer_Form_Decorator', 'ZLayer/Form/Decorator', 'decorator')
             ->addDisplayGroupPrefixPath('ZLayer_Form_Decorator', 'ZLayer/Form/Decorator')
             //->setDefaultDisplayGroupClass('ZLayer_Form_DisplayGroup')
             ->addPrefixPath('Application_Form_Decorator', APPLICATION_PATH . 'extras/forms/decorators', 'decorator')
             ->addPrefixPath('Application_Form_Element', APPLICATION_PATH . 'extras/forms/elements', 'element')
             ->addElementPrefixPath('Application_Form_Decorator', APPLICATION_PATH . 'extras/forms/decorators', 'decorator')
             ->addDisplayGroupPrefixPath('Application_Form_Decorator', APPLICATION_PATH . 'extras/forms/decorators');

        // set infos
        $this->_setInfos();
        
        // get options
        $this->setOptions();
        
        // parent constructor
        parent::__construct();
        
        // Set translator
        $this->_setTranslator();
        
        // Import display groups
        $this->_importdisplayGroups();

        // Translate Display Groups
        $this->_translateDisplayGroups();
        
        // Translate Elements
        $this->_translateElements();
        
        // Remove decorator for hidden fields
        $this->_removeDecoratorsForHidden();
        
        // Rebuild Ids
        $this->_rebuildId();
        
        // Auto Populate
        $this->_populateFromPost();
    }
    
    /**
     * Set infos
     *
     * @return void
     */
    private function _setInfos() {
        
        $class = get_class($this);
        $fields = explode("_", $class);
        $header = strtolower(current($fields));
        $form = end($fields);
        //exit(strtolower(Zend_Filter::filterStatic($form, 'CamelCaseToDash')));
        $this->_form = strtolower($form);
        $this->_formConfigFile = strtolower(Zend_Filter::filterStatic($form, 'CamelCaseToDash'));
        
        if ($header == "application") {
            $this->_module = "default";
            $this->_basePath = APPLICATION_PATH;
            $this->_configPath = $this->_basePath . "/configs/privates";
        } else {
            $front = Zend_Controller_Front::getInstance();
            $this->_module = $header;
            $this->_basePath = $front->getModuleDirectory($header);
            $this->_configPath = $this->_basePath . "/configs";
        }
    }
    
    /**
     * Set form state from options array
     *
     * @return Zend_Form
     */
    public function setOptions()
    {
        $options = $this->_getOptions()->toArray();
        
        if (isset($options['importDisplayGroups'])) {
            $this->_extraDisplayGroups = $options['importDisplayGroups'];
            unset($options['importDisplayGroups']);
        }
        
        if (!isset($options['id'])) {
            $id = $this->_module . '-' . $this->_formConfigFile . '-form';
            $id = Zend_Filter::filterStatic($id, 'DashToCamelCase') . mt_rand();
            $id = lcfirst($id);
            $options['id'] = $id;
        }
        
        parent::setOptions($options);
        
        return $this;
    }
    
    /**
     * Get options
     *
     * @param  array $options
     * @return void
     */
    protected function _mergeOptions( array $options) {
        
        if (!isset($this->_options)) {
            $this->_options = new Zend_Config( array(), true );
        }
        
        $optionsConf = new Zend_Config( $options, true );
        $this->_options->merge($optionsConf);
        
    }
    
    
    /**
     * Get options
     *
     * @return Zend_Config
     */
    protected function _getOptions() {

        if ($formCache = ZLayer_Cache::get('form')) {
        
            $cacheId = ZLayer_Cache::id('form_'.$this->_module.$this->_form);
        
            if ($formCache->test($cacheId) !== false) {
        
                $this->_options = $formCache->load($cacheId, true);
                return $this->_options;
        
            } else {
        
                $this->_makeOptions();
                $formCache->save($this->_options, $cacheId, array(), null);
                return $this->_options;
        
            }
        
        } else {
        
            $this->_makeOptions();
            return $this->_options;
        
        }
        
    }
    
    /**
     * Make options
     *
     * @return Zend_Config
     */
    private function _makeOptions() {
        // Set default options
        $this->_setDefaultOptions();
        
        // Set form options
        $this->_setFormOptions();
        
        // Set auto options
        $this->_setAutoOptions();
    }
    

    /**
     * Set default options
     *
     * @return void
     */
    private function _setDefaultOptions() {

        // Sets array of standard config
        /*
        $optionsArr = array(
            'id' => strtolower($this->_form) . "Form",
        );

        $this->_mergeOptions($optionsArr);
        */
    }

    /**
     * Set form options
     *
     * @return void
     */
    private function _setFormOptions() {

        $langSession = new Zend_Session_Namespace('Language');
        $language = $langSession->name;
        
        $array = array(
            $this->_configPath . '/forms/',
        );

        $this->_setFormOptionsFromDirs($array);
    }
    
    /**
     * Set auto options
     *
     * @return void
     */
    private function _setAutoOptions() {
        
    }
    
    /**
     * _importdisplayGroups
     *
     * @param  string $ns
     * @throws ZLayer_Form_Exception When class for form not found
     * @throws ZLayer_Form_Exception When group in the form not exists
     * @return void
     */
    protected function _importdisplayGroups($ns = null) {
        
        if (!($imports = $this->_extraDisplayGroups)) {
            return;
        }
        
        foreach ($imports as $importName => $importArray) {
            
            if (!isset($importArray['module'])) {
                $module = $this->_module;
            } else {
                $module = (string) $importArray['module'];
            }
            
            $module = Zend_Filter::filterStatic($module, 'DashToCamelCase');
            
            $group = (string) $importArray['group'];
            
            $placeAfter = null;
            if (isset($importArray['placeAfter'])) {
                $placeAfter = $importArray['placeAfter'];
            }
            
            $placeBefore = null;
            if (isset($importArray['placeBefore'])) {
                $placeBefore = $importArray['placeBefore'];
            }
            
            $form = Zend_Filter::filterStatic($importArray['form'], 'DashToCamelCase');
            
            if ($ns) {
                $class = $module . "_Form_" . $ns . "_" . $form;
            } else {
                $class = $module . "_Form_" . $form;
            }
            
            if (!class_exists($class,true)) {
                throw new ZLayer_Form_Exception("The class for the form " . ucfirst($form) . " not found for importing from the module " . ucfirst($module));
            }
            
            $form = new $class();
            
            if ($extraGroupObject = $form->getDisplayGroup($group)) {
                
                $extraGroupObject->setName($importName);
                
                if ($placeAfter or $placeBefore) {
                    
                    $displayGroupsArray = array();
                    foreach($this->getDisplayGroups() as $displayGroupName => $displayGroup) {
                        
                        $this->removeDisplayGroup($displayGroupName);
                        
                        if ($displayGroupName == $placeBefore) {
                            $displayGroupsArray[] = $extraGroupObject;
                        }
                        
                        $displayGroupsArray[] = $displayGroup;
                        
                        if ($displayGroupName == $placeAfter) {
                            $displayGroupsArray[] = $extraGroupObject;
                        }
                        
                    }
                    
                    $this->addDisplayGroups($displayGroupsArray);
                    
                } else {
                    $this->_addDisplayGroupObject($extraGroupObject);
                }
                
                foreach ($extraGroupObject->getElements() as $extraGroupElementName => $extraGroupElementObject) {
                	$this->addElement($extraGroupElementObject);
                }
                
            } else {
                
                throw new ZLayer_Form_Exception("The displayGroup " . $group . " not found in the form " . $class);
                
            }
            
            
        }
        
    }
    
    /**
     * _rebuildId
     *
     * @return void
     */
    private function _rebuildId() {
        foreach ($this->getElements() as $elementName => $element) {
            $elementName = Zend_Filter::filterStatic($elementName, 'DashToCamelCase');
            $id = $this->_module . '-' . $this->_formConfigFile . '-' . $elementName;
            $id = Zend_Filter::filterStatic($id, 'DashToCamelCase') . mt_rand();
            $id = lcfirst($id);
            $element->id = $id . mt_rand();
        }
    }
    
    /**
     * _removeDecoratorsForHidden
     *
     * @return void
     */
    private function _removeDecoratorsForHidden() {
        foreach ($this->getElements() as $elementName => $element) {
            if ($element instanceof Zend_Form_Element_Hidden) {
                $element->removeDecorator('HtmlTag')
                        ->removeDecorator('label');
            }   
        }
    }
    
    /**
     * Translate Elements
     *
     * @return void
     */
    private function _translateDisplayGroups() {
        
        foreach ($this->getDisplayGroups() as $displayGroupName => $displayGroup) {
            
            $legendName = $displayGroupName . "Legend";
            
            if ($legend = $this->_translator->_($legendName)) {
                if ($legend != $legendName) {
                    $displayGroup->setLegend($legend);
                }
            }
            
        }
        
    }
    
    /**
     * Translate Elements
     *
     * @return void
     */
    private function _translateElements() {
    
        foreach ($this->getElements() as $elementName => $element) {

            $labelName = $elementName . "Label";
            
            if ($label = $this->_translator->_($labelName)) {
                if ($label != $labelName) {
                    $element->setLabel($label);
                }
            }
            
            $descrName = $elementName . "Description";
            
            if ($description = $this->_translator->_($descrName)) {
                if ($description != $descrName) {
                    $element->setDescription($description);
                }
            }

        }
    
    }

    /**
     * Set form options from directories
     *
     * @param  array $dirs
     * @param  string $form
     * @return void
     */
    protected function _setFormOptionsFromDirs($dirs) {

        foreach ($dirs as $key => $value) {
            try {
                $file = $value . $this->_formConfigFile;
                $formOptions = new ZLayer_Config_File($file, APPLICATION_ENV);
                $options = $formOptions->process();
                if ($options instanceof Zend_Config) {
                    $this->_mergeOptions($options->toArray());
                }
            } catch (ZLayer_Config_File_Exception $e) {}
        }
    }

    
    /**
     * populate from Post
     *
     * @return void
     */
    private function _populateFromPost() {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if ($request->isPost()) {
            $this->populate($request->getPost());
        }
    }
    
    /**
     * set Translator
     *
     * @return void
     */
    private function _setTranslator() {
        $locale = Zend_Registry::get("Zend_Locale");
        $language = $locale->getLanguage();
        if ( $region = $locale->getRegion() ) {
            $language .= "_" . $region;
        }
        
        $front = Zend_Controller_Front::getInstance();
        $moduleDir = $front->getModuleDirectory($this->_module);
        
        $content = $moduleDir.'/langs/'.$language.'/forms/' . $this->_formConfigFile.".ini";
        
        $this->setTranslator(new ZLayer_Translate($content,$language));
        $this->setDisableTranslator(true);
    }
    
    /**
     * setAction
     *
     * @return void
     */
    public function setAction($action) {
        return parent::setAction($this->getView()->baseUrl($action));
    }
    
    /**
     * Validate the form
     *
     * @param  array $data
     * @return boolean
     */
    public function isValid($data) {
        
        if (parent::isValid($data)) {
            return true;
        }
        
        require_once "Zend/Controller/Action/HelperBroker.php";
        require_once "ZLayer/Controller/Action/Helper/Error.php";
        
        foreach ( $this->getMessages() as $formField => $messageArray ) {
    
            $error = Zend_Controller_Action_HelperBroker::getStaticHelper("error");
    
            foreach($messageArray as $messageKey => $message ) {
                $error->manual($message, $formField, 500);
                    //ZLayer_Controller_Action_Helper_Error::APPLICATION_ERROR);
                    
            }
        }
    
        return false;
        
    }
    
    /**
     * setValueFromArray
     *
     * @param  array $data
     * @param  array $ignored
     * @return void
     */
    public function setValueFromArray($data, array $ignored = null) {
    
        foreach ($this->getElements() as $elementName => $element) {

            if (is_array($ignored)) {
                
                if (in_array($elementName, $ignored)) {
                    continue;
                }
            }
            
            if (isset($data[$elementName])) {
                
                $element->setValue($data[$elementName]);
                
            }
        }
    
    }
    
    /**
     * getRequest
     *
     * @return void
     */
    public function getRequest() {
        return Zend_Controller_Front::getInstance()->getRequest();
    }
}
