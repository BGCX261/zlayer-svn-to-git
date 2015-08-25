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
 * @package    ZLayer_Controller
 * @subpackage ZLayer_Controller_Action_Helper
 * @author  Wagner Rodrigues (wagner.rodrigues at gmail dot com)
 * 				João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
* @see Zend_Controller_Action_Helper_Abstract
*/
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Add a message 
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage ZLayer_Controller_Action_Helper
 * @author  Wagner Rodrigues (wagner.rodrigues at gmail dot com)
 * 				João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Action_Helper_Messenger extends Zend_Controller_Action_Helper_Abstract
{

    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const SUCCESS = 'success';
    
    /**
     * 
     * @var ZLayer_Translate
     */
    private $_translator;
    
    
    /**
     *
     * @return void
     */
    public function __construct() {
        
        // Set translator
        $this->_setTranslator();
    }
    

    /**
     * _addMessageKey() - Add a message to flash message by key
     *
     * @param  string $key
     * @param  const $type
     */
    private function _addMessageKey($key, $type) {
        return $this->addMessage($this->_translator->_($key),$type,$key);
    }
    
	/**
     * addMessage() - Add a message to flash message
     *
     * @param  string $message
     * @param  const $type
     */
    public function addMessage($message, $type, $label=null) {
        
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $flashMessenger->setNamespace($type);
        
        if(!is_null($label))
            $message = array($label => $message);
        
        $flashMessenger->addMessage($message);
    }

    /**
     * alert() - Add an warning flash mesage on session 
     *
     * @param  string $key
     * @return ZLayer_Controller_Action_Helper_Messenger
     */
    public function warning($key){ 
        return $this->_addMessageKey($key,self::WARNING);
    }
    
    /**
     * error() - Add an error flash mesage on session 
     *
     * @param  string $key
     * @return ZLayer_Controller_Action_Helper_Messenger
     */
    public function error($key){ 
        return $this->_addMessageKey($key,self::ERROR);
    }
    
    /**
     * notice() - Add an notice flash mesage on session 
     *
     * @param  string $key
     * @return ZLayer_Controller_Action_Helper_Messenger
     */
    public function notice($key){ 
        return $this->_addMessageKey($key,self::NOTICE);
    }
    
    /**
     * success() - Add an success flash mesage on session 
     *
     * @param  string $key
     * @return ZLayer_Controller_Action_Helper_Messenger
     */
    public function success($key){ 
        return $this->_addMessageKey($key,self::SUCCESS);
    }
    
    /**
     * set Translator
     *
     * @return void
     */
    private function _setTranslator() {
        
        $locale = Zend_Registry::get("Zend_Locale");
        $language = $locale->getLanguage();
        if ($region = $locale->getRegion()) {
            $language .= "_" . $region;
        }
        
        $front = $this->getFrontController();
        
        $moduleDir = $front->getModuleDirectory($this->getRequest()->getModuleName());
        $fileName = strtolower(Zend_Filter::filterStatic($this->getRequest()->getControllerName(), 'CamelCaseToDash'));
        $content = $moduleDir.'/langs/'.$language.'/helpers/messenger/' . $fileName .".ini";
        
        $this->_translator = new ZLayer_Translate($content,$language);
    }
    
}