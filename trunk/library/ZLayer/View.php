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
 * @package    ZLayer_View
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
    
/**
 * @see Zend_View
 */
require_once 'Zend/View.php';

/**
 * Class for ZLayer View
 *
 * @uses       Zend_View
 * @category   ZLayer
 * @package    ZLayer_View
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_View extends Zend_View
{
    /**
     * Instances of helper objects.
     *
     * @var array
     */
    protected $_vars;
    
    /**
     * Constructor.
     *
     * @param array $config Configuration key-value pairs.
     * @return void
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }
    
    /**
     * Return list of all assigned variables
     *
     * Returns all public properties of the object. Reflection is not used
     * here as testing reflection properties for visibility is buggy.
     *
     * @return array
     */
    public function getVars()
    {
        if (!$this->_vars) {
            $this->_vars = parent::getVars(); 
        }
        return $this->_vars;
    }
    
    /**
     * getVar
     *
     * @param string $var Var index name
     * @return mixed|null
     */
    public function getVar($var)
    {
        $vars = $this->getVars();
        if (isset($vars[$var])) {
            return $vars[$var];
        }
        return null;
    }
    
}