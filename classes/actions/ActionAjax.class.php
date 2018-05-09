<?php

class PluginAd_ActionAjax extends PluginAd_Inherit_ActionAjax{
    
    public function Init()
    {
        parent::Init();
        
    }
    protected function RegisterEvent() {
        parent::RegisterEvent();
        
        $this->AddEventPreg('/^phone-show$/i','EventUserPhone');
               
    }
    
    protected function EventUserPhone() {
        $oUser = $this->User_GetUserById(getRequest('iUserId'));
        
        $aFields = $this->GetFieldsByName($oUser->getId(), 'phone');
        $sNumber = null;
        foreach($aFields as $oField){
            if(substr($oField->getValue(), 0, getRequest('iFieldValueSize') ) == getRequest('iFieldValueCrop')){
                $sNumber = $oField->getValue();
            }
        }
        
        $this->Viewer_AssignAjax('phone',$sNumber);
    }
    
    public function GetFieldsByName($iUserId, $sName) {
        $aFields = $this->User_getUserFieldsValues($iUserId, true, ['contact', 'social']);
        foreach($aFields as &$oField ){
            if($oField->getName() != $sName){
                unset($oField);
            }
        }
        return $aFields;
    }
}