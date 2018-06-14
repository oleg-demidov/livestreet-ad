<?php
/*
 * Хук показа карты в профиле
 */
class PluginAd_HookProfile extends Hook
{
    public $oUser;
    
    public function __construct() {
        $this->oUser = $this->User_GetUserCurrent();
    }

    public function RegisterHook()
    {
        if(!($this->oUser)){
            return false;
        }
        
        if( Router::GetAction() == 'profile'){
            $this->AddHook('viewer_init_assign', 'AssignVarsProfile');
        }
        
        $this->AddHook('template_nav_profile_created', 'AddProfileCreatedItemMenu');
        
        $this->AddHook('template_nav_user_profile', 'ProfileMenuItem');
    }    
    
    public function ProfileMenuItem($aParams) {
        if(!is_array($aParams['items'])){
            return false;
        }
        
        foreach($aParams['items'] as &$item){
            if($item['name'] == 'created'){
                $item['url'] = $this->oUser->getUserWebPath()."created/ads/";
            }
        }        
        
        return $aParams['items'];
    }
    
    public function AddProfileCreatedItemMenu($aParams) {
        if(!is_array($aParams['items'])){
            return false;
        }
        
        $iCountTopicAdUser = $this->Topic_GetCountFromTopicByFilter(['topic_type' => 'ad', 'user_id' => $this->oUser->getId()]);
        
        $aParams['items'] = array_merge([[
            'text' => $this->Lang_Get('plugin.ad.ad.titles'),
            'url' => $this->oUser->getUserWebPath()."created/ads/",
            'name' => 'ads',
            'count' => $iCountTopicAdUser
        ]], $aParams['items']);
        
        return $aParams['items'];
    }
    
    public function AssignVarsProfile($aParams) {
        
        $iCountTopicUser = $this->Topic_GetCountFromTopicByFilter(['topic_type' => 'topic', 'user_id' => $this->oUser->getId()]);
        $this->Viewer_Assign('iCountTopicUser', $iCountTopicUser);
    }
}