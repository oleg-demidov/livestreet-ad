<?php


class PluginAd_ActionIndex extends ActionPlugin
{
    protected $oUserCurrent;
    
    protected $page;

    protected function RegisterEvent() {
        $this->AddEventPreg( '/^index$/i', 'EventIndex'); 
        $this->SetDefaultEvent('index');
    }

    public function Init() {
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    public function EventIndex() {
        if ($oType = $this->Category_GetTypeByTargetType('specialization')) {
            $aCategories = $this->Category_LoadTreeOfCategory(array('type_id' => $oType->getId()));
        }
        if(isset($aCategories)){
            $this->Viewer_Assign('aCategories', $aCategories);
        }
        $this->SetTemplateAction('index');
    }   
    
    

}