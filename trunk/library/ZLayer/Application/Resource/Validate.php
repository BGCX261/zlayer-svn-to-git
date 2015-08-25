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
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @see Zend_Translate
 */
require_once 'Zend/Translate.php';

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * Validate resource
 *
 * @uses      Zend_Application_Resource_ResourceAbstract
 * @category  ZLayer
 * @package  ZLayer_Application
 * @subpackage  Resource
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Application_Resource_Validate extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_View
     */
    public function init()
    {
        // locale Options
        $language = null;

        if (Zend_Registry::isRegistered("Zend_Locale")) {
            $locale = Zend_Registry::get("Zend_Locale");
            $language = $locale->getLanguage()."_".$locale->getRegion();
        }
        
        $options = $this->getOptions();
        
        if (!is_null($language) and isset($options["translate"])) {
            
            $options["translate"]["locale"] = $language;
            $translator = new Zend_Translate($options["translate"]);
            Zend_Validate_Abstract::setDefaultTranslator($translator);
            
        }
        
        Zend_Validate::addDefaultNamespaces('ZLayer_Validate');
        
    }

}