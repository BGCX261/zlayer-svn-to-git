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
 * @package    ZLayer_Cache
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZLayer_Config_File
 */
require_once "ZLayer/Config/File.php";

/**
 * ZLayer_Cache
 *
 * @category  ZLayer
 * @package   ZLayer_Cache
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Cache
{
    /**
     * id
     *
     * @param  string  $ns     Name of id
     * @return string
     */
    static public function id($ns) {
        
        $id = ROOT_PATH . '_' . $ns . '_' . APPLICATION_ENV;
        $id = md5($id);
        return $id;
        
    }
    
    /**
     * get
     *
     * @param  string  $name     Name os cache
     * @return null|Zend_Cache_Core
     */
    static public function get($name) {
    
        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam('bootstrap');
    
        if (!$bootstrap->hasPluginResource('cachemanager')) {
            return null;
        }
    
        $managerResource = $bootstrap->getPluginResource('cachemanager');
        $manager = $managerResource->getCacheManager();
    
        if ($cache = $manager->getCache($name)) {
            return $cache;
        }
    
        return null;
    
    }
}