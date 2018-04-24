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

class PluginTest extends Plugin
{

    protected $aInherits = array(
//        'module' => array(
//            'ModuleCategory' => 'PluginFixCategory_ModuleCategory',
//        ),
//        'entity'=>[
//            'ModuleCategory_EntityCategory' => '_ModuleCategory_EntityCategory'
//        ],
//        'template' => array(
//            'admin:component.p-category.form' => '_components/p-category/form.tpl'
//        ),
        //'entity' =>array('ModuleCategory_EntityCategory' => '_ModuleCategory_EntityCategory'),
    );

    public function Init()
    {
       
     }

    public function Activate()
    {
        return true;
    }

    public function Deactivate()
    {
        return true;
    }
}