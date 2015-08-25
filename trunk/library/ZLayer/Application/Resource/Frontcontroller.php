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
* @category  ZLayer
* @package  ZLayer_Application
* @subpackage  Resource
* @author  João Paulo Faria (jpfaria at gmail dot com)
* @license   http://framework.zend.com/license/new-bsd     New BSD License
*/

/**
 * @see Zend_Application_Resource_Frontcontroller
 */
require_once 'Zend/Application/Resource/Frontcontroller.php';


/**
 * View resource
 *
 * @uses      Zend_Application_Resource_Frontcontroller
 * @category  ZLayer
 * @package  ZLayer_Application
 * @subpackage  Resource
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Application_Resource_Frontcontroller extends Zend_Application_Resource_Frontcontroller
{
    /**
     * Initialize Front Controller
     *
     * @return Zend_Controller_Front
     */
    public function init()
    {
        //$dispatcher = new ZLayer_Controller_Dispatcher_Standard();
        //$front = $this->getFrontController();
        //$front->setDispatcher($dispatcher);
        $front = parent::init();
        return $front;
    }
}
