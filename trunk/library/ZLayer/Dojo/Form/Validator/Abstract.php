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
 * @subpackage Validator
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * ZLayer_Dojo_Form_Validator_Abstract
 *
 * @category   ZLayer
 * @package    ZLayer_Dojo
 * @subpackage Validator
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class ZLayer_Dojo_Form_Validator_Abstract implements ZLayer_Dojo_Form_Validator_Interface
{
    /**
     * Validator options
     *
     * @var array
     */
    private $_validator;
    
    /**
     * __construct
     *
     * @param  Zend_Validate_Abstract $validator
     * @return void
     */
    public function __construct( Zend_Validate_Abstract $validator ) {
        $this->_validator = $validator;
    }
    
    /**
     * get Validator
     *
     * @return Zend_Validate_Abstract
     */
    protected function _getValidator() {
        return $this->_validator;
    }
    
    /**
     * get Dojo Options
     *
     * @return array
     */
    public function getDojoOptions() {
        return array();
    }
}