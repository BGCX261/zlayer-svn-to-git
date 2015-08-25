<?php

class ZLayer_Dojo_View_Helper_ZlayerCustomDijit extends Zend_Dojo_View_Helper_CustomDijit
{
    protected $_defaultDojoType = 'dojox.form.CheckedMultiSelect';
 
    public function zlayerCustomDijit($id = null, $value = null, array $params = array(), array $attribs = array())
    {
        $id = 'unitId';
        $value = null;
        $params = array();
        $attribs = array('multiple' => true, 'jsId' => 'unitStore', 'id' => 'appointmentTreatmentInsertUnitId19633717451048472849', 'listsep' => '<br />', 'name' => 'unitId', 'store' => 'unitStore', 'required' => true, 'dojoType' => 'dojox.form.CheckedMultiSelect');
        
        
        return $this->customDijit($id, $value, $params, $attribs);
    }
}