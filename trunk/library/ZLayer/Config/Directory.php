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
 * @see ZLayer_Config_File
 */
require_once "ZLayer/Config/File.php";

/**
 * @see ZLayer_Config_Directory_Exception
 */
require_once "ZLayer/Config/Directory/Exception.php";
 
/**
 * Directory Adapter for Zend_Config
 *
 * @category  ZLayer
 * @package   ZLayer_Config
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Config_Directory
{
    /**
     * Data Dirs
     *
     * @var array
     */
    private $_dirs;

    /**
     * Config section
     *
     * @var string
     */
    private $_section;

    /**
     * Recursive match
     *
     * @var string
     */
    private $_recursive;
    
    /**
     * Config data
     *
     * @var Zend_Config
     */
    private $_config;
    
    /**
     * Constructor
     *
     * @param  mixed   $dir Source(s) directory
     * @param  string  $section Section to process
     * @param  string  $recursive Recursive match
     * @return void
     */
    public function __construct( $dirs = false, $section = false, $recursive = false ) {
    
        // Sets object vars
        $this->setSection($section);
        $this->setRecursive($recursive);
        
        // When there is a directory variable automatically processes
        if ( isset( $dirs ) ) {
            if (!is_array($dirs)) {
                $this->add($dirs);
            } else {
                $this->_dirs = $dirs;
            }
        }
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
     * Set recursive find
     *
     * @param  string  $recursive     Recursive find
     * @return void
     */
    public function setRecursive( $recursive ) {
        $this->_recursive = (bool) $recursive;
    }
    
    /**
     * Add source directory
     *
     * @param  string  $dir     Full path for configs file
     * @return void
     */
    public function add( $dir ) {
        if (!is_array($this->_dirs)) {
            $this->_dirs = array();
        }
        $this->_dirs[].= (string) $dir;
    }
    
    /**
     * Load configuration file on directory from array
     *
     * @throws ZLayer_Config_Directory_Exception When directories is not set
     * @return Zend_Config|void
     */
     public function process() {
        $dirs = $this->_dirs;
        if (!is_array($dirs)) {
            throw new ZLayer_Config_Directory_Exception('No directory was configured for processing');
        } else {
            $config = new Zend_Config(array(),true);
            foreach( $dirs as $dir ) {
                $config->merge($this->_loadConfigDir($dir));
            }
            $this->_config = $config;
            return $this->_config;
        }
    }
    
    /**
     * Load configuration file on directory
     *
     * @param  string  $dir     Full path for configs file
     * @throws ZLayer_Config_Directory_Exception When directory is not exists
     * @return Zend_Config
     */
    private function _loadConfigDir( $dir ) {
        
        // Scans directory
        if (is_dir($dir)) {

        	$config = new Zend_Config(array(),true);

            $dir = new DirectoryIterator( $dir );
	        foreach( $dir as $entry ){
                
	            if ( $entry->isFile() ) {

                    try {
                        $fileConfig = new ZLayer_Config_File( $entry->getPathname(),$this->_section, true );
                        $config->merge($fileConfig->process());
                    } catch ( ZLayer_Config_File_Exception $e ) { }
                    
	            } elseif ( $entry->isDir() and !$entry->isDot() and $this->_recursive ) {
                
                    $config->merge($this->_loadConfigDir($entry->getPath()));
                    
                }
	        }
            
	        return $config;
	        
        } else {
        	
          	throw new ZLayer_Config_Directory_Exception('The directory ' . $dir . ' is not exists');
        	
        }
        
    }
    
}