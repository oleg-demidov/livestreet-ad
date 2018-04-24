<?php


class PluginTest_HookAdmin extends Hook{
    public function RegisterHook()
    {
        /**
         * Хук на отображение админки
         */
        $this->AddHook('init_action_admin', 'InitActionAdmin');
        
        
    }

    /**
     * Добавляем в главное меню админки свой раздел с подпунктами
     */
    public function InitActionAdmin()
    {
        /**
         * Получаем объект главного меню
         */
        $oMenu = $this->PluginAdmin_Ui_GetMenuMain();
        /**
         * Добавляем новый раздел
         */
        $oMenu->AddSection(
            Engine::GetEntity('PluginAdmin_Ui_MenuSection')
                ->SetCaption($this->Lang_Get('plugin.test.admin.menu.title'))->SetName('test')->SetUrl('plugin/test')->setIcon('question-circle')
//                ->AddItem(Engine::GetEntity('PluginAdmin_Ui_MenuItem')->SetCaption($this->Lang_Get('plugin.ydirect.admin.menu.ads'))->SetUrl('adgroups'))
//                ->AddItem(Engine::GetEntity('PluginAdmin_Ui_MenuItem')->SetCaption("Получить токен")->SetUrl('token'))
                //->AddItem(Engine::GetEntity('PluginAdmin_Ui_MenuItem')->SetCaption($this->Lang_Get('plugin.freelancer.admin.menu.responses'))->SetUrl('responses'))
        );
    }

}
