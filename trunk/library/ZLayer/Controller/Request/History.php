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
 * @subpackage Request
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
    
/**
 * @see Zend_Session_Namespace
 */
require_once 'Zend/Session/Namespace.php';


/**
 * ZLayer_Controller_Request_History
 *
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Request
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Request_History implements IteratorAggregate, Countable
{
    /**
     * $_session - Zend_Session storage object
     *
     * @var Zend_Session
     */
    static protected $_session = null;

    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var ZLayer_Controller_Request_History
     */
    protected static $_instance = null;
    
	/**
     * __construct() - Instance constructor, needed to get iterators, etc
     *
     * @param  string $namespace
     * @return void
     */
    public function __construct()
    {
        if (!self::$_session instanceof Zend_Session_Namespace) {
            self::$_session = new Zend_Session_Namespace($this->getName());
        }
    }
    
    
    /**
     * Singleton instance
     *
     * @return ZLayer_Controller_Request_History
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
    
        return self::$_instance;
    }
    
	/**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        $fullClassName = get_class($this);
        if (strpos($fullClassName, '_') !== false) {
            $helperName = strrchr($fullClassName, '_');
            return ltrim($helperName, '_');
        } elseif (strpos($fullClassName, '\\') !== false) {
            $helperName = strrchr($fullClassName, '\\');
            return ltrim($helperName, '\\');
        } else {
            return $fullClassName;
        }
    }
    
	/**
     * addMessage() - Add an current navigation on history
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @param  string $layout
     * @return ZLayer_Controller_Request_History Provides a fluent interface
     */
    public function addHistory(Zend_Controller_Request_Abstract $oRequest,$layout)
    {
        if (!is_array(self::$_session->history)) {
            self::$_session->history = array();
        }
        
        $params = $oRequest->getParams();
        if (isset($params['__format'])) {
            $context = $params['__format'];
        } else {
            $context = 'default';
        }
        

        $array = array (
            "action" => $oRequest->getActionName(),
            "controller" => $oRequest->getControllerName(),
            "module" => $oRequest->getModuleName(),
            "layout" => $layout,
        	"params" => $oRequest->getParams(),
        	"method" => $oRequest->getMethod(),
        	"context" => $context,
        	"secure" => $oRequest->isSecure(),
        	"xmlHttpRequest" => $oRequest->isXmlHttpRequest(),
        	"flashRequest" => $oRequest->isFlashRequest(),
        );
        
        self::$_session->history[] = $array;
        
        $histAr = self::$_session->history;
        $revAr = array_reverse($histAr);
        $limitRevAr = array_slice($revAr, 0, 10);
        $newHistAr = array_reverse($limitRevAr);

        self::$_session->history = $newHistAr;
        
        return $this;
    }

	/**
     * hasHistory() - Wether a specific namespace has History
     *
     * @return boolean
     */
    public function hasHistory()
    {
        if (isset(self::$_session->history)){
            if (is_array(self::$_session->history)) {
                if (count(self::$_session->history) > 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * getHistory() - Get History from a specific namespace
     *
     * @return array
     */
    public function getHistory()
    {
        if ($this->hasHistory()) {
            return self::$_session->history;
        }

        return array();
    }
    
	/**
     * getLast() - Get last entry
     *
     * @return array
     */
    public function getLast()
    {
        if ($this->count()>1) {
            return self::$_session->history[$this->count()-2];
        }

        return array();
    }
    
	/**
     * getCurrent() - Get current entry
     *
     * @return array
     */
    public function getCurrent()
    {
        if ($this->hasHistory()) {
            return end(self::$_session->history);
        }
        return array();
    }

    /**
     * Clear all History
     *
     * @return boolean True if History were cleared, false if none existed
     */
    public function clearHistory()
    {
        if ($this->hasHistory()) {
            self::$_session->history = array();
            return true;
        }

        return false;
    }
    
	/**
     * getIterator() - complete the IteratorAggregate interface, for iterating
     *
     * @return ArrayObject
     */
    public function getIterator()
    {
        if ($this->hasHistory()) {
            return new ArrayObject($this->getHistory());
        }

        return new ArrayObject();
    }
    
	/**
     * count() - Complete the countable interface
     *
     * @return int
     */
    public function count()
    {
        if ($this->hasHistory()) {
            return count($this->getHistory());
        }

        return 0;
    }
    
}