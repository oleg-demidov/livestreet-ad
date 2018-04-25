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
//        'module' => array(
//            'ModuleCategory' => 'PluginFixCategory_ModuleCategory',
//        ),
//        'entity'=>[
//            'ModuleCategory_EntityCategory' => '_ModuleCategory_EntityCategory'
//        ],
//        'template' => array(
//            'component.topic.add-type' => '_components/topic/topic-add-type-ad.tpl'
//        ),
        //'entity' =>array('ModuleCategory_EntityCategory' => '_ModuleCategory_EntityCategory'),
    );

    public function Init()
    {
       
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