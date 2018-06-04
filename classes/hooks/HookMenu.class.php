<?php


class PluginAd_HookMenu extends Hook{
    public function RegisterHook()
    {
       
        $this->AddHook('template_nav_main', 'NavMain', null, 655);
        $this->AddHook('viewer_init_assign', 'NavMainAssign');
        
    }

    public function NavMain($aParams)
    {
        $startItems = array_slice($aParams['items'], 0, Config::Get('plugin.ad.menu.position'));
        
        $endItems = array_slice($aParams['items'], Config::Get('plugin.ad.menu.position'));
        
        $aResult = array_merge($startItems,  [[
            'text' => $this->Lang_Get('plugin.ad.menu.master.title'),
            'name' => 'masters',
            'url'  => Router::GetPath('masters')
        ]], $endItems);
        return    $aResult;

    }
    
    public function NavMainAssign($aParams) {        
        if(Router::GetActionEventName() == 'topic'){
            $iTopicId = substr(Router::GetActionEvent(), 0,-5);
            $oTopic = $this->Topic_GetTopicByFilter([
                'topic_id' => $iTopicId,
                '#select'  => ['topic_type']
            ]);
            if( $oTopic->getType() == 'ad'){
                $this->Viewer_Assign('sMenuHeadItemSelect', 'masters');
            }
        }
        
    }

}
