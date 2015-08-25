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
 * @package    ZLayer_Filter
 * @author  JoÃ£o Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Filter_StringToLower
 */
require_once 'Zend/Filter/StringToLower.php';

/**
 * @uses       Zend_Filter_StringToLower
 * @category   ZLayer
 * @package    ZLayer_Filter
 * @author  JoÃ£o Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Filter_StringToLower extends Zend_Filter_StringToLower
{
	/**
     * Constructor
     *
     * @param string|array|Zend_Config $options OPTIONAL
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setEncoding("UTF-8");
    }
}
