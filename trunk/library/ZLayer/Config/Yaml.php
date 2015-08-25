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
 * @package    ZLayer_Config
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Config_Yaml
 */
require_once 'Zend/Config/Yaml.php';

/**
 * Extension for YAML Adapter for Zend_Config
 *
 * @category  ZLayer
 * @package   ZLayer_Config
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Config_Yaml extends Zend_Config_Yaml
{
    
    /**
     * Array constants in parsed YAML
     * @var array|boolean
     */
    protected static $_constants;
    
    
    /**
     * Loads the section $section from the config file encoded as YAML
     *
     * Sections are defined as properties of the main object
     *
     * In order to extend another section, a section defines the "_extends"
     * property having a value of the section name from which the extending
     * section inherits values.
     *
     * Note that the keys in $section will override any keys of the same
     * name in the sections that have been included via "_extends".
     *
     * Options may include:
     * - allow_modifications: whether or not the config object is mutable
     * - skip_extends: whether or not to skip processing of parent configuration
     * - yaml_decoder: a callback to use to decode the Yaml source
     *
     * @param  string        $yaml     YAML file to process
     * @param  mixed         $section  Section to process
     * @param  array|boolean $options 
     * @param  array|boolean $constants    Local constants
     */
    public function __construct($yaml, $section = null, $options = false)
    {
        //if ($constants)
        //self::setLocalConstants($constants);
        
        if (is_array($options)) {
            foreach ($options as $key => $value) {
                switch (strtolower($key)) {
                    case 'constants':
                        $this->setLocalConstants($value);
                        break;
                    default:
                        break;
                }
            }
        }
        
        parent::__construct($yaml, $section, $options);
    }

    
    /**
     * Replace any constants referenced in a string with their values
     *
     * @param  string $value
     * @return string
     */
    /*
    protected static function _replaceConstants($value)
    {
        //$value = self::_replaceConstants($value);
        
        $constants = self::_getLocalConstants();
        if (is_array($constants)) {
            foreach ( $constants as $constKey => $constValue) {
                if (strstr($value, $constKey)) {
                    $value = str_replace($constKey, $constValue, $value);
                }
            }
        }
        return $value;
    }
    */
    
    
    /**
     * Get (reverse) sorted list of defined constant names
     *
     * @return array
     */
    //protected static function _getLocalConstants()
    //{
    //    return self::$_constants;
    //}
    
    
    /**
     * Indicate array of local constants
     *
     * @param  array $array
     * @return void
     */
    //public static function setLocalConstants($array)
    //{
    //    self::$_constants = $array;
    //}
    
    
    
}
