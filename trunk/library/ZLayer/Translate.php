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
 * @package    ZLayer_Translate
 * @author  Wagner Rodrigues (wagner dot rodrigues at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
    
/**
 * @see Zend_Translate
 */
require_once 'Zend/Translate.php';

/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';

/**
 * Class for ZLayer translate
 *
 * @uses       Zend_Translate
 * @category   ZLayer
 * @package    ZLayer_Translate
 * @author  Wagner Rodrigues (wagner dot rodrigues at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Translate extends Zend_Translate
{

	/**
	 * 
	 * Path to content file
	 * @var string
	 */
	private $_contentFile;
	
	/**
	*
	* Path to content file
	* @var string
	*/
	private $_adapter;
	
	
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($contentFile,$locale) {
        $this->_contentFile = $contentFile;
        parent::__construct($this->getConfig());
    }
   
    /**
     * 
     * Return the translate object
     */
    private function getConfig(){
        //TODO: Check if there is a better way to do it 
        if(file_exists($this->_contentFile)){
            $configAr = array(
    			"adapter" => "ini",
    			"content" => $this->_contentFile
            );
        }
        else{
            $configAr = array(
    			"adapter" => "array",
    			"content" => array("{locale}")
            );
        }
        
        $config = new Zend_Config($configAr,true);
        
        return $config;
    }

}