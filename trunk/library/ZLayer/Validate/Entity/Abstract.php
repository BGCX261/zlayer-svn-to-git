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
 * @category   ZLayer
 * @package    ZLayer_Validate
 * @subpackage Entity
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @see 	   Zend_Validate_Abstract
 * @category   ZLayer
 * @package    ZLayer_Validate
 * @subpackage Entity
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class ZLayer_Validate_Entity_Abstract extends Zend_Validate_Abstract
{
    /**
    * Error constants
    */
    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_RECORD_FOUND    = 'recordFound';
    
    /**
     * @var array Message templates
     */
    protected $_messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "No record matching %value% was found",
        self::ERROR_RECORD_FOUND    => "A record matching %value% was found",
    );
    
    /**
     * @var string
     */
    protected $_module = null;
    
    /**
     * @var string
     */
    protected $_entity = '';
    
    /**
     * @var string
     */
    protected $_field = '';
    
    /**
     * Provides basic configuration for use with Zend_Validate_Entity Validators
     *
     * The following option keys are supported:
     * 'entity'   => The entity to validate against
     * 'module'  => The module
     * 'field'   => The field to check for a match
     *
     * @param array|Zend_Config $options Options to use for this validator
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else if (func_num_args() > 1) {
            $options       = func_get_args();
            $temp['entity'] = array_shift($options);
            $temp['field'] = array_shift($options);
            $options = $temp;
        }
    
        if (!array_key_exists('entity', $options)) {
            require_once 'ZLayer/Validate/Entity/Exception.php';
            throw new ZLayer_Validate_Entity_Exception('Entity option missing!');
        }
    
        if (!array_key_exists('field', $options)) {
            require_once 'ZLayer/Validate/Entity/Exception.php';
            throw new ZLayer_Validate_Entity_Exception('Field option missing!');
        }
        
        if (!array_key_exists('module', $options)) {
            require_once 'ZLayer/Validate/Entity/Exception.php';
            throw new ZLayer_Validate_Entity_Exception('Module option missing!');
        }
    
        $this->setModule($options['module']);
        $this->setField($options['field']);
        $this->setEntity($options['entity']);
        
    }
    
    /**
     * Returns the set field
     *
     * @return string
     */
    public function getField()
    {
        return $this->_field;
    }

    /**
     * Sets a new field
     *
     * @param string $field
     * @return ZLayer_Validate_Entity_Abstract
     */
    public function setField($field)
    {
        $this->_field = (string) $field;
        return $this;
    }
    
    /**
     * Returns the set module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->_module;
    }
    
    /**
     * Sets a new module
     *
     * @param string $module
     * @return ZLayer_Validate_Entity_Abstract
     */
    public function setModule($module)
    {
        $this->_module = (string) $module;
        return $this;
    }

    /**
     * Returns the set entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->_entity;
    }
    
    /**
     * Sets a new entity
     *
     * @param string entity
     * @return ZLayer_Validate_Entity_Abstract
     */
    public function setEntity($entity)
    {
        $this->_entity = (string) $entity;
        return $this;
    }
    
    
    /**
     * _getRepositoryFromEntity
     *
     * @return ZLayer_Model_Repository_Abstract
     */
    protected function _getRepositoryFromEntity() {
        $loader = new ZLayer_Loader_Model($this->_module);
        return $loader->repository($this->_entity);
    }
    
    
}
