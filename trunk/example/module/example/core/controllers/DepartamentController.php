<?php

class Example_DepartamentController extends ZLayer_Controller_Action_Abstract {

    public function listAction() {
        
        $departament = new Example_Model_Departament();
        $data = $departament->fetchAll(null, 'name')->toArray();
        $this->view->assign("data",$data);
        
        //$departamentRepository = $this->model->repository('Departament');
        //$departamentCollection = $departamentRepository->fetchAll();
        //$this->view->assign("data",$departamentCollection->toArray());
    }
    
}