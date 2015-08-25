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
 * @see Zend_Application_Resource_Locale
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @see Zend_Session_Namespace
 */
require_once 'Zend/Session/Namespace.php';

/**
 * Language resource
 *
 * @uses      Zend_Application_Resource_ResourceAbstract
 * @category  ZLayer
 * @package  ZLayer_Application
 * @subpackage  Resource
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Application_Resource_Language extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
        $langSession = new Zend_Session_Namespace('Language');
        
        $locale = Zend_Registry::get("Zend_Locale");
        
        if ($langSession->name) {
            
            $locale->setLocale($langSession->name);
            $language = $langSession->name;
        
        } else {
            
            $language = $locale->getLanguage();
            
            if ($locale->getRegion()) {
                $language .= "_".$locale->getRegion();
            }
            
            $langSession->name = $language;
            
        }
        
        Zend_Registry::set("Language",$language);
        
    }

}