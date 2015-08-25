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
 * Theme resource
 *
 * @uses      Zend_Application_Resource_ResourceAbstract
 * @category  ZLayer
 * @package  ZLayer_Application
 * @subpackage  Resource
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Application_Resource_Theme extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Initialize Front Controller
     *
     * @return Zend_Controller_Front
     */
    private $_basePath;

    /**
     * Initialize Front Controller
     *
     */
    public function init()
    {
        $resOptions = $this->getOptions();
        $this->_basePath = $resOptions["basePath"];
        
        $cacheId = ZLayer_Cache::id("theme");
        
        if ($requestCache = ZLayer_Cache::get('theme')) {
        
            if ($requestCache->test($cacheId) !== false) {
        
                $optionsAr = $requestCache->load($cacheId, true);
        
            } else {
        
                $optionsAr = $this->_getThemesOptions($resOptions["name"]);
                $requestCache->save($optionsAr, $cacheId, array(), null);
        
            }
        
        } else {
        
            $optionsAr = $this->_getThemesOptions($resOptions["name"]);
        
        }
        
        if (!isset($optionsAr["resources"])) {
            return;
        }
            
        $bootstrap = $this->getBootstrap();
        
        $resAr = $optionsAr["resources"];
        
        $bootstrap = $this->getBootstrap();
        $bootOptionsAr = $bootstrap->getOptions();
        
        foreach ($resAr as $resKey => $resValue ) {
            
            if (isset($bootOptionsAr["resources"][$resKey])) {
                
                $bootResOptionsAr = $bootOptionsAr["resources"][$resKey];
                if (is_array($bootResOptionsAr)) {
                    $resValue = array_merge_recursive($bootResOptionsAr,$resValue);
                }
                
            }

            $resource = $bootstrap->getPluginResource($resKey);
            $resource->setOptions($resValue);
            if ($resKey!="theme") {
                $bootstrap->bootstrap(ucfirst($resKey));
            }
            
        }
        
    }
    
    /**
     * _getThemesOptions
     *
     * @param string $name
     * @throws ZLayer_Application_Resource_Exception When theme not found or was incorrectly created
     * @return array
     */
    private function _getThemesOptions($name)
    {
        //@todo GET SESSION/CACHE/LOAD Config
        $options = array();
        $matched = false;

        foreach ($this->_basePath as $key => $value) {
        
            $basePath = $value.$name."/configs/";
            if (is_dir($basePath)) {
                //define('temp.TEST',"testando");
                $themeOptionsDir = new ZLayer_Config_Directory($basePath, APPLICATION_ENV, false);
                $themeOptions = $themeOptionsDir->process()->toArray();
                if (isset($themeOptions["resources"]["theme"]["parents"])) {
                    $parents = $themeOptions["resources"]["theme"]["parents"];
                    foreach (  $parents as $parent ) {
                        $themeOptions = array_merge_recursive($themeOptions,$this->_getThemesOptions($parent));
                    }
                }

                $options = array_merge_recursive($options,$themeOptions);

                $matched = true;
            }
            
        }

        if (!$matched) {
            throw new ZLayer_Application_Resource_Exception("The theme {$name} not found or was incorrectly created");
        }

        return $options;
        

    }
    
    
}