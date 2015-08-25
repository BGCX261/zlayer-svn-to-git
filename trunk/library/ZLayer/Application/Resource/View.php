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
 * View resource
 *
 * @uses      Zend_Application_Resource_ResourceAbstract
 * @category  ZLayer
 * @package  ZLayer_Application
 * @subpackage  Resource
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZLayer_Application_Resource_View extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Zend_View_Interface
     */
    protected $_view;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_View
     */
    public function init()
    {
        $view = $this->getView();

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        return $view;
    }

    /**
     * Retrieve view object
     *
     * @return Zend_View
     */
    public function getView()
    {
        if (null === $this->_view) {
            $options = $this->getOptions();
            $this->_view = new ZLayer_View($options);

            if (isset($options['doctype'])) {
                $this->_view->doctype()->setDoctype(strtoupper($options['doctype']));
                if (isset($options['charset']) && $this->_view->doctype()->isHtml5()) {
                    $this->_view->headMeta()->setCharset($options['charset']);
                }
            }
            if (isset($options['contentType'])) {
                $this->_view->headMeta()->appendHttpEquiv('Content-Type', $options['contentType']);
            }
            if (isset($options['assign']) && is_array($options['assign'])) {
                $this->_view->assign($options['assign']);
            }
        }
        return $this->_view;
    }
    
}