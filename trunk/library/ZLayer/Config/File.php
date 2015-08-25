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
 * @see Zend_Config_Xml
 */
require_once "Zend/Config/Xml.php";

/**
 * @see Zend_Config_Ini
 */
require_once "Zend/Config/Ini.php";

/**
 * @see Zend_Config_Yaml
 */
require_once "Zend/Config/Yaml.php";

/**
 * @see Zend_Config_Json
 */
require_once "Zend/Config/Json.php";

/**
 * @see ZLayer_Config_File_Exception
 */
require_once "ZLayer/Config/File/Exception.php";


/**
 * Merge Adapter for Zend_Config
 *
 * @category  ZLayer
 * @package   ZLayer_Config
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Config_File
{
    /**
     * Config section
     *
     * @var string
     */
    private $_section;

    /**
     * Config source
     *
     * @var array
     */
    private $_source;

    /**
     * Config options
     *
     * @var array
     */
    private $_options;

    /**
     * Constructor
     *
     * @param  string  $section Section to process
     * @param  mixed   $source Source(s) directory
     * @return void
     */
    public function __construct( $source = false, $section = null, $options = false ) {
        // Sets object vars
        $this->setSection($section);
        $this->setSource($source);
        $this->setOptions($options);
    }
    
    /**
     * Set section of config
     *
     * @param  string  $section     Name of section
     * @return void
     */
    public function setSection( $section ) {
        $this->_section = (string) $section;
    }
    
    /**
     * Set source value
     *
     * @param  string  $source     config source
     * @return void
     */
    public function setSource( $source ) {
        $this->_source = (string) $source;
    }

    /**
     * Set options array
     *
     * @param  array  $options     config options
     * @return void
     */
    public function setOptions( $options ) {
        $this->_options = (array) $options;
    }
    
    /**
     * Process configuration file 
     *
     * @throws ZLayer_Config_File_Exception When sources is not set
     * @return Zend_Config|void
     */
    public function process() {
    
        $source = $this->_source;
        if (!isset($source)) {
            throw new ZLayer_Config_File_Exception('No source has been set for processing');
        } else {
            if (pathinfo($source, PATHINFO_EXTENSION)) {
                return $this->_getConfig($source);
            } else {
            
                $array = array ("yaml","xml","ini","json","php","inc");
                $config = false;
                foreach($array as $key => $value ) {
                    $sourceext = $source.".".$value;
                    if (is_file($sourceext)) {
                        try {
                            $atconfig = $this->_getConfig($sourceext);
                            if ( $config ) {
                                $config->merge($atconfig);
                            } else {
                                $config = $atconfig;
                            }
                        } catch (ZLayer_Config_File_Exception $e) {}
                    }
                }
                if (!$config) {
                    //throw new ZLayer_Config_File_Exception('The search did not find any configuration file to "'.$source.'"');
                } else {
                    return $config;
                }
            }
        }
        
    }
    
    /**
     * Load configuration file 
     *
     * @throws ZLayer_Config_File_Exception When inc file is not a array
     * @throws ZLayer_Config_File_Exception When config file is not valid
     * @return Zend_Config|void
     */
    private function _getConfig($source) {
        $suffix = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        switch ($suffix) {
            case 'ini':
                $config = new Zend_Config_Ini($source, $this->_section, $this->_options);
                break;
            case 'xml':
                $config = new Zend_Config_Xml($source, $this->_section, $this->_options);
                break;
            case 'yaml':
                $config = new Zend_Config_Yaml($source, $this->_section, $this->_options);
                break;
            case 'json':
                $config = new Zend_Config_Json($source, $this->_section, $this->_options);
                break;
            case 'php':
            case 'inc':
                $config = include $source;
                if (!is_array($config)) {
                    throw new ZLayer_Config_File_Exception('Invalid configuration file provided by '.$source.'; PHP file does not return array value');
                }
                $config = new Zend_Config($config,true);
                break;
            default:
                throw new ZLayer_Config_File_Exception('Invalid configuration file provided by '.$source.'; Unknown config type');
        }
        return $config;
    }
    
}