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
 * @subpackage Zend_View_Helper
 * @author  Wagner Rodrigues (wagner.rodrigues at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 */

/** Zend_View_Helper */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Helper for mount base url from theme
 *
 * @package    Zend_View
 * @subpackage Helper
 * @  Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_View_Helper_BaseThemeUrl extends Zend_View_Helper_Abstract
{
	/**
     * Returns site's base url with theme location, or file with base url prepended
     *
     * $file is appended to the base url for simplicity
     *
     * @param  string|null $file
     * @return string
     */
	public function baseThemeUrl($file = null){
    
        // Set front
        $front = Zend_Controller_Front::getInstance();
        
        // Set theme Options
        $themeOptions = $front->getParam('bootstrap')
                              ->getPluginResource("theme")
                              ->getOptions();
    
        $baseUrl = BASE_URL;
        if (substr($baseUrl,-1) != "/") {
            $baseUrl .= "/";
        }
        $baseUrl .= "themes/" . $themeOptions["name"] . "/";
        
        // Remove trailing slashes
        if (null !== $file) {
            $baseUrl .= ltrim($file, '/\\');
        }
        
        return $baseUrl;
        
	}

}