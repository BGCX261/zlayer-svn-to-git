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
 * @package    ZLayer_Validate
 * @subpackage Entity
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZLayer_Validate_Entity_Abstract
 */
require_once 'ZLayer/Validate/Entity/Abstract.php';

/**
 * ZLayer_Validate_Entity_NotExists
 * 
 * @see 	   ZLayer_Validate_Entity_Abstract
 * @category   ZLayer
 * @package    ZLayer_Validate
 * @subpackage Entity
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Validate_Entity_NotExists extends ZLayer_Validate_Entity_Abstract
{
    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible
     */
    public function isValid($value){
        
        $this->_setValue($value);
        
        $repository = $this->_getRepositoryFromEntity();
        if ($repository->fetch($value,$this->getField())) {
            $this->_error(self::ERROR_RECORD_FOUND);
            return false;
        } else {
            return true;
        }
    }
}