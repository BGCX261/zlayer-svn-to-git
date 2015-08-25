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
 * @subpackage Action
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Controller_Action
 */
require_once "Zend/Controller/Action.php";

/**
 * @see ZLayer_Controller_Request_Options
 */
require_once "ZLayer/Controller/Request/Options.php";

/**
 * ZLayer_Controller_Action_Abstract
 *
 * @uses       Zend_Controller_Action
 * @category   ZLayer
 * @package    ZLayer_Controller
 * @subpackage Action
 * @author  João Paulo Faria (jpfaria at gmail dot com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class ZLayer_Controller_Action_Abstract extends Zend_Controller_Action
{
    /**
     * Model action helper
     * 
     * @var ZLayer_Loader_Model
     */
    public $model;

    /**
     * Redirect flag
     * 
     * @var bool
     */
    private $_redirect = false;
    
    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
    public function init()
    {
        // Set contexts
        $this->_setContexts();

        // Set model
        $this->_setModel();

        // Call start
        $this->start();
    }

    /**
     * Enable/Disable redirect
     *
     * @param $redir bool
     * @return void
     */
    private function _setRedirect($redir)
    {
        $this->_redirect = (bool) $redir;
    }
    
    /**
     * Enable redirect
     *
     * @param $redir bool
     * @return void
     */
    public function allowRedirect()
    {
        $this->_setRedirect(true);
    }
    
    /**
     * Start object
     *
     * Called from {init()} as final step of object instantiation.
     *
     * @return void
     */
    public function start()
    {
    }

    /**
     * preDispatch
     *
     * @return void
     */
    public function preDispatch()
    {
        $this->_setRedirect(false);
    }
    
    /**
     * Post-dispatch routines
     *
     * @return void
     */
    public function postDispatch()
    {
        // Redirect
        $this->_attemptRedirect();
    }

    /**
     * _setContexts
     *
     * @return void
     */
    private function _setContexts()
    {
        // Create empty array
        $contexts = array();

        // Get local context options
        $requestObj = new ZLayer_Controller_Request_Options();
        $reqOptions = $requestObj->getRequestOptions($this->getRequest());
        if (isset($reqOptions["contexts"])) {
            $contexts = array_merge($contexts,$reqOptions["contexts"]);
        }

        // Get global context options
        $globalContexts = $this->getFrontController()
                               ->getParam('bootstrap')
                               ->getPluginResource("contexts")
                               ->getOptions();
        if (is_array($globalContexts)) {
           $contexts = array_merge($contexts,$globalContexts);
        }

        // Switch contexts
        if (count($contexts) > 0) {

            $contextSwitch = $this->_helper->getHelper('ContextSwitch');
            $contextSwitch->setContextParam("__format");

            $contextSwitch->setContext(
                'html', array(
                    'suffix' => '',
                    'headers' => array(
                        'Content-Type' => 'text/html',
                    ),
                    'options' => array(
                        'autoDisableLayout' => true,
                    )
                )
            );
            $contextSwitch->setContext(
                'html-json', array(
                    'suffix' => '',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                    ),
                )
            );
            $contextSwitch->setContext(
                'dojo-data', array(
                    'suffix' => '',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                    ),
            		'callbacks' => array(
                            'init' => 'initJsonContext',
                            //'post' => 'postJsonContext'
                    ),
                    'options' => array(
                        'autoDisableLayout' => true,
                    )
                )
            );
            $contextSwitch->setContext(
                'dojo-auto', array(
                    'suffix' => '',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                    ),
            		'callbacks' => array(
                            'init' => 'initJsonContext',
                            //'post' => 'postJsonContext'
                    ),
            		'options' => array(
                        'autoDisableLayout' => true,
                    )
                )
            );

            $contextSwitch->addActionContext($this->getRequest()->getActionName(), $contexts);
            //$contextSwitch->setAutoDisableLayout(false);
            $contextSwitch->initContext();
        }
    }

    /**
     * _setModel
     *
     * @return void
     */
    private function _setModel()
    {
        $request = $this->getRequest();
        $module = $request->getModuleName();
        $this->model = new ZLayer_Loader_Model($module);
    }

    /**
     * _attemptRedirect
     *
     * @return void
     */
    private function _attemptRedirect()
    {
        // Exit if the redirect is disabled
        if (!$this->_redirect) return;
        
        // get request options
        $requestObj = new ZLayer_Controller_Request_Options();
        $reqOptions = $requestObj->getRequestOptions($this->getRequest());

        // exit if redirect is not set
        if (!isset($reqOptions["redirect"])) {
            return;
        }

        // exit if not dispatchable
        $dispatcher = $this->getFrontController()->getDispatcher();
        if(!$dispatcher->isDispatchable($this->getRequest())) {
            return;
        }

        // exit if action/controller/module is not set
        $redirect = $reqOptions["redirect"];
        if (!isset($redirect["action"]) or 
            !isset($redirect["controller"]) or 
            !isset($redirect["module"])) {
            return;
        }
        
        // parse params
        $params = array();
        if (isset($redirect["params"])) {
            if (is_array($redirect["params"])) {
                $params = $redirect["params"];
            }
        }
        
        $contextSwitch = $this->_helper->getHelper('ContextSwitch');
        $contextParam = $contextSwitch->getContextParam();
        $contextValue = $this->getRequest()->getParam($contextParam);
        if ($contextValue) {
            $params[$contextParam] = $contextValue;
        }

        // call redirect
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $redirector->gotoSimple($redirect["action"],
                                $redirect["controller"],
                                $redirect["module"],
                                $params);
        
        
        
    }

}