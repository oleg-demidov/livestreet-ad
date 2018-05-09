<?php


class PluginAd_HookMenu extends Hook{
    public function RegisterHook()
    {
       
        $this->AddHook('template_nav_main', 'NavMain', null, 655);
        $this->AddHook('viewer_init_assign', 'NavMainAssign');
        
    }

    public function NavMain($aParams)
    {
        $aResult = array_merge( [[
            'text' => $this->Lang_Get('plugin.ad.menu.master.title'),
            'name' => 'masters',
            'url'  => Router::GetPath('masters')
        ]], $aParams['items']);
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
