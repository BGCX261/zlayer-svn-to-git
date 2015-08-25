<?php

class Example_AccountController extends ZLayer_Controller_Action_Abstract {

    public function gridAction() {
        
    }
    
    public function listAction() {
    
        $where = $this->_getParam('where',null);
        $order = $this->_getParam('sort','name');
        $count = $this->_getParam('count','25');
        $offset = $this->_getParam('start','0');
        
        /*
        $accountTable = new Example_Model_Dao_Db_Table_Account();
        $accountRowset = $accountTable->fetchAll($where, $order, $count, $offset);
        
        $data = array();
        
        foreach ($accountRowset as $accountRow) {
        
            $departamentRow = $accountRow->findParentRow('Example_Model_Dao_Db_Table_Departament');
            $data[] = array(	'id' => $accountRow->id,
            					'departamentId' => $accountRow->departamentId,
            					'departamentName' => $departamentRow->name,
            					'name' => $accountRow->name);
        }
        
        $accountRowset = $accountTable->fetchAll($where);
        $rows = $accountRowset->count();
        */
        
        
        // get items
        //$accountDepartamentService = $this->model->service('AccountDepartament');
        //$data = $accountDepartamentService->fetchAllAccountsWithDepartament($where, $order, $count, $offset);
        
        // get rows
        //$accountRepository = $this->model->repository('Account');
        //$rows = $accountRepository->count($where);
        
        
        //exit($order);
        
        $account = new Example_Model_Account();
        $data = $account->fetchAllWithDepartamentName($where, $order, $count, $offset)->toArray();        
        $rows = $account->count($where);
        
        $this->view->assign("data", $data);
        $this->view->assign("rows", $rows);
        
        
    }
    
    public function getAction() {
        //$this->_helper->messenger->error("user_login_error");
        //$this->_helper->messenger->error("user_login_success");
        //$this->_helper->messenger->warning("user_login_error");
        $this->view->assign("var", "Example_ExampleController->helloAction");
        //$this->_redirect("/example/example/bye");
    }

    public function updateAction() {
        
    }

    public function insertAction() {
    
        /*
        $front = Zend_Controller_Front::getInstance();
        $log = $front->getParam('bootstrap')
                     ->getPluginResource("log")
                     ->getLog();
        $log->log("aqui",Zend_Log::INFO);
        */
        
        if ($this->getRequest()->isPost()) {
    
            $form = new Example_Form_AccountInsert();
            $params = $this->_getAllParams();
    
            if ($form->isValid($params)) {
                
                //$acEntity = $this->model->entity('account',$form->getValues());
                //$acEntity->ignoreFilteringAndValidation(true);
                //$acEntity->save();
                
                $account = new Example_Model_Account();
                $data = array(	
                        'name' => $this->_getParam('name'), 
              	        'departamentId' => $this->_getParam('departamentId')
                        );
                $account->insert($data);
                
                $this->_helper->messenger->success("insertSuccess");
                $this->allowRedirect();
                
            } else {
                //$this->_helper->error("exampleError");
            }
            
        }
        
        //echo "<pre>";
        //print_r($_SESSION);
        //echo "</pre>";
        
        //$this->_helper->error->byKey("insertSuccess",200);
        //$this->_helper->messenger->success("insertSuccess");
    
    }
    
    public function deleteAction() {
    
    }
    
    
    public function modelTestAction() {

        // Call Entity
        /*
        $array = array(
            'name' => 'Wagner Rodrigues',
            'departamentId' => 1
        );
        $acEntity = $this->model->entity('account',$array);
        // OR
        // $acEntity = new Example_Model_Entity_Account($array);
        */
        
        // Call Service
        /*
        $acService = $this->model->service('account');
        // OR
        // $acService = new Example_Model_Service_Account();
        */

        // Call Repository
        /*
        $acRepo = $this->model->repository('account');
        // OR
        // $acRepo = new Example_Model_Repository_Account();
        */
        
        // Fetch
        //$acRepo = $this->model->repository('account');
        //$acEntity = $acRepo->fetch('1','id');
        
        //$depRepo = $this->model->repository('departament');
        //$depEntity = $depRepo->fetchByParams(array('id' => '1'));
        

        // Insert
        ///$params = array('departamentId' => '1', 'name' => 'teste de nome no CÚ 2');
        
        //$acEntity = $this->model->entity('account',$params);
        //$acEntity->ignoreFilteringAndValidation(true);
        //$acEntity->setName("Wagner Rodrigues JOAO");
        //$acEntity->setDepartamentId('1');
        //$acEntity->save();
        
        
        /*
        
        
        // OR
        // $array = array( 'name' => 'Wagner Rodrigues', 'departamentId' => 1  );
        // $acEntity = $this->model->entity('account', $array);
        $acRepo = $this->model->repository('account');
        $id = $acRepo->insert($acEntity);
        $acEntity = $acRepo->fetch($id);
		*/

        // Update
        /*
        $acRepo = $this->model->repository('account');
        $acEntity = $acRepo->fetch('10');
        $acEntity->setName("Fulano de TalXYz");
        $rows = $acEntity->save();
        */
        //$rows = $acRepo->update($acEntity);
    	
        /*
        $acRepo = $this->model->repository('account');
        $acEntity = $acRepo->fetch(11);
        $acEntity->setName("AAAAAAAAAAAAA AAAa AAAXX 2 AAAAsdsss Das Dos De Du DanÇa - + )");
        $rows = $acRepo->update($acEntity);
        */
        
        //$acEntity->save();

        // Delete
        /*
        $acRepo = $this->model->repository('account');
        $rows = $acRepo->deleteOne(11);
        */

        // FetchAll
        //$acRepo = $this->model->repository('account');
        //$acCollection = $acRepo->fetchAll();
        
        //echo "<pre>";
        //var_dump($acCollection);
        //echo "</pre>";
        
        /*
        foreach ($acCollection as $acEntity) 
        {
            //echo $acEntity->getId() . "-" . $acEntity->getName()."<br/>";
            //echo "<pre>";
            //var_dump($acEntity);
            //echo "</pre>";
        }
        */
        
        //$this->view->assign("var", "Example_ExampleController->helloAction");
        //$this->_helper->messenger->warning("example");
        //$this->_helper->messenger->error("example");
        //$this->_helper->messenger->success("example");
        
        // get items
        $where = $this->_getParam('where',null);
        $order = $this->_getParam('sort','name');
        $count = $this->_getParam('count','25');
        $offset = $this->_getParam('start','0');
        $accountRepository = $this->model->repository('Account');
        $data = $accountRepository->fetchAllWithDepartament($where, $order, $count, $offset);
        
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        

    }

}

