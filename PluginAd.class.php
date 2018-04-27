<?php
/**
 * 
 * @author Oleg Demidov
 *
 */

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginAd extends Plugin
{

    protected $aInherits = array(
        'module' => array(
            'ModuleGeo' => '_ModuleGeo',
        ),
//        'entity'=>[
//            'ModuleCategory_EntityCategory' => '_ModuleCategory_EntityCategory'
//        ],
        'template' => array(
            'component.topic.add-type' => '_components/topic/topic-add-type.tpl'
        ),
        //'entity' =>array('ModuleCategory_EntityCategory' => '_ModuleCategory_EntityCategory'),
    );

    public function Init()
    {
        $this->Component_Add('ad:category-tabs');
        $this->Viewer_AppendScript($sPath = Plugin::GetTemplateWebPath('ad').'assets/js/init.js');
        
        $this->Geo_AddTargetType('topic');
    }

    public function Activate()
    {
        $this->Category_CreateTargetType('specialization', 'Специализации', array(), true);
        return true;
    }

    public function Deactivate()
    {
        $this->Category_RemoveTargetType('specialization', ModuleCategory::TARGET_STATE_NOT_ACTIVE);
        return true;
    }
}