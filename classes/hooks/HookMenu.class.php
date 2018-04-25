<?php


class PluginAd_HookMenu extends Hook{
    public function RegisterHook()
    {
       
        $this->AddHook('template_nav_main', 'NavMain', null, 655);
        
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

}
