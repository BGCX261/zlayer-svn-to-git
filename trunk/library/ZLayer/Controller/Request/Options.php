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
 * @package    ZLayer_Controller
 * @subpackage Request
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
    
/**
 * @see Zend_Controller_Front
 */
require_once 'Zend/Controller/Front.php';

/**
 * @see Zend_Registry
 */
require_once 'Zend/Registry.php';

/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';

/**
 * @see ZLayer_Config_File
 */
require_once 'ZLayer/Config/File.php';

/**
 * ZLayer_Controller_Request_Options
 *
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Request
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Controller_Request_Options
{
    /**
     * getRequestOptions
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return array
     */
    public function getRequestOptions(Zend_Controller_Request_Abstract $oRequest) {
        
        $module = $oRequest->getModuleName();
        $controller = $oRequest->getControllerName();
        $action = $oRequest->getActionName();
        
        $cacheId = ZLayer_Cache::id("request_".$module.$controller.$action);
        
        if (Zend_Registry::isRegistered($cacheId)) {
            
            return Zend_Registry::get($cacheId);
            
        } else {
            
            if ($requestCache = ZLayer_Cache::get('request')) {
                
                if ($requestCache->test($cacheId) !== false) {
                
                    $array = $requestCache->load($cacheId, true);
                
                } else {
                
                    $array = $this->_makeRequestOptions($oRequest);
                    $requestCache->save($array, $cacheId, array(), null);
                
                }
                
            } else {
                
                $array = $this->_makeRequestOptions($oRequest);
                
            }
        }
        
        return $array;
        
    }
    
    /**
     * _makeRequestOptions
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return array
     */
    private function _makeRequestOptions(Zend_Controller_Request_Abstract $oRequest) {
        
        // Get action options
        $array = $this->_getActionOptions($oRequest);
        
        // Get layout options
        $contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
        $contextParam = $contextSwitch->getContextParam();
        $format = $oRequest->getParam($contextParam);
        
        if (!$format) {
        
            if (isset($array["layout"])) {
                $layEntryAr = $array["layout"];
            } else {
                $front = Zend_Controller_Front::getInstance();
                $layEntryAr = $front->getParam('bootstrap')
                                    ->getPluginResource("layout")
                                    ->getOptions();
            }
        
            $layoutAr = $this->_getLayoutOptions($layEntryAr);
        
            if ($layoutAr) {
                $array = array_merge_recursive($array,$layoutAr);
            }
        }
        
        
        // Get groups options
        if (isset($config->groups)) {
            $groupsAr = $this->_getGroupsOptions($array["groups"]);
            if ($groupsAr) {
                $array = array_merge_recursive($array,$groupsAr);
            }
        }
        
        return $array;
        
    }
    
    
    /**
     * getActionOptions
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return array
     */
    private function _getActionOptions(Zend_Controller_Request_Abstract $oRequest) 
    {
        $module = $oRequest->getModuleName();
        $controller = $oRequest->getControllerName();
        $action = $oRequest->getActionName();
        
        if ($module == "default") {
            $baseDir = "/controllers/{$controller}/";
        } else {
            $baseDir = "/modules/{$module}/{$controller}/";
        }
        
        $baseDir = "privates/".$baseDir;

        $optionsAr = $this->_getFileOptions($action,$baseDir);
        
        return $optionsAr;
    }
    
    /**
     * getLayoutOptions
     *
     * @param  array $array
     * @return array
     */
    private function _getLayoutOptions(array $array) 
    {
        $layOptionsAr = $this->_getFileOptions($array["layout"],"privates/layouts/");
            
        if (is_array($layOptionsAr)) {
        
            unset($layOptionsAr["redirect"]);
            unset($layOptionsAr["forward"]);
            unset($layOptionsAr["layout"]);
            unset($layOptionsAr["contexts"]);
            
        }
        
        return $layOptionsAr;
         
    }
    
    /**
     * _getGroupsOptions
     *
     * @param  array $groups
     * @return array
     */
    private function _getGroupsOptions(array $groups) 
    {
        $array = array();
        foreach ($groups as $group ) {
            $groupAr = $this->_getFileOptions($group,"groups/");
            if (is_array($groupAr)) {
                unset($groupAr["groups"]);
                $array = array_merge_recursive($array,$groupAr);
            }
        }
        return $array;
        
    }
    
    /**
     * getMergedRequestOptions
     *
     * @param  Zend_Controller_Request_Abstract $oRequest
     * @return array
     */
    public function getMergedRequestOptions(Zend_Controller_Request_Abstract $oRequest) {
        
        $key = "ZLayerControllerRequestOptionsMerged";
        
        if (Zend_Registry::isRegistered($key)) {
            $array = Zend_Registry::get($key);
        } else {
            $array = array();
        }
        
        $array = array_merge_recursive($array,$this->getRequestOptions($oRequest));
        
        Zend_Registry::set($key, $array);
        
        return $array;
    }
    
    
    /**
     * _getFileOptions
     *
     * @param  string $file
     * @param  string $baseDir
     * @return array|bool
     */
    private function _getFileOptions($file, $baseDir) 
    {
        if ($fileCache = $requestCache = ZLayer_Cache::get('request')) {
        
            $cacheId = ZLayer_Cache::id("request_file_".$baseDir.$file);
        
            if ($fileCache->test($cacheId) !== false) {
        
                return $fileCache->load($cacheId, true);
        
            } else {
        
                $array = $this->_makeFileOptions($file, $baseDir);
                $fileCache->save($array, $cacheId, array(), null);
                return $array;
        
            }
        
        } else {
        
            return $this->_makeFileOptions($file, $baseDir);
        
        }
        
    }
    
    /**
     * _makeFileOptions
     *
     * @param  string $file
     * @param  string $baseDir
     * @return array|bool
     */
    private function _makeFileOptions($file, $baseDir)
    {
        $front = Zend_Controller_Front::getInstance();
        $themeOptions = $front->getParam('bootstrap')
                              ->getPluginResource("theme")
                              ->getOptions();
        
        // Set themes
        $themes = array($themeOptions["name"]);
        if (isset($themeOptions["parents"])) {
            $themes = array_merge_recursive($themes,$themeOptions["parents"]);
        }
        
        
        // Mount dirs
        $dirs = array();
        $dirs[].= APPLICATION_PATH."/application/configs/".$baseDir;
        foreach ($themeOptions["basePath"] as $key => $value) {
            foreach ($themes as $theme) {
                $dir = $value.$theme."/configs/".$baseDir;
                if (is_dir($dir)) {
                    $dirs[].= $dir;
                }
            }
        }
        
        try {
        
            $array = array();
        
            foreach( $dirs as $dir  ) {
        
                $fileUri = $dir . $file;
        
                $fileObjConfig = new ZLayer_Config_File( $fileUri, APPLICATION_ENV );
                $fileConfig = $fileObjConfig->process();
                if ($fileConfig) {
                    $array = array_merge_recursive($array,$fileConfig->toArray());
                }
        
            }
            Zend_Registry::set($key, $array);
            return $array;
        
        } catch (ZLayer_Config_File_Exception $e) {
        }
        
        return null;
    }
    
}

