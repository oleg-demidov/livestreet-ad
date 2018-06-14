<?php


class PluginAd_HookMenu extends Hook{
    public function RegisterHook()
    {
       
        $this->AddHook('template_nav_main', 'NavMain', null, 655);
        $this->AddHook('template_nav_topics', 'NavTopics', null, 655);
        $this->AddHook('template_nav_topics_sub', 'NavTopicsSub', null, 655);
        
        $this->AddHook('viewer_init_assign', 'NavMainAssign');
        
    }

    public function NavMain($aParams)
    {
        foreach($aParams['items'] as &$item){
            if($item['name'] == 'blog'){
                $item['url'] = Router::GetPath('community');
            }
        }
        
        $startItems = array_slice($aParams['items'], 0, Config::Get('plugin.ad.menu.position'));
        
        $endItems = array_slice($aParams['items'], Config::Get('plugin.ad.menu.position'));
        
        $aResult = array_merge($startItems,  [[
            'text' => $this->Lang_Get('plugin.ad.menu.master.title'),
            'name' => 'masters',
            'url'  => Router::GetPath('masters')
        ]], $endItems);
        return    $aResult;

    }
    
    public function NavTopics($aParams)
    {
        foreach($aParams['items'] as &$item){
            if($item['name'] == 'index'){
                $item['url'] = Router::GetPath('community');
            }
        }
        
        return $aParams['items'];
    }
    
    public function NavTopicsSub($aParams)
    {
        foreach($aParams['items'] as &$item){
            $item['url'] = str_replace('index', 'community', $item['url']);
        }
        
        return $aParams['items'];
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
