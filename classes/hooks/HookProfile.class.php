<?php
/*
 * Хук показа карты в профиле
 */
class PluginAd_HookProfile extends Hook
{
    
    public function RegisterHook()
    {
        //$this->AddHook('template_user-info-group--items', 'AddCategoryProfile');
    }    
    
    public function AddCategoryProfile($aParams) {
        if(!is_array($aParams['items'])){
            return false;
        }print_r($aParams['items']);
        
        $aParams['items'] = array_merge([[
            'label' => 'Категория',
            'content' => '222'
        ]], $aParams['items']);
        
        return $aParams['items'];
    }
}