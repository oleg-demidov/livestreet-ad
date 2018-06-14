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
            'ModuleTopic' => '_ModuleTopic'
        ),
        'action' => array(
            'ActionAjax' => '_ActionAjax',
            'ActionProfile' => '_ActionProfile'
        ),
        'entity'=>[
            'ModuleTopic_EntityTopicAd' => '_ModuleTopic_EntityTopicAd'
        ],
        'template' => array(
            'component.topic.topic-add-type-ad' => '_components/topic/topic-add-type-ad.tpl',
            'component.topic.topic-type-ad' => '_components/topic/topic-type-ad.tpl'
        ),
        //'entity' =>array('ModuleCategory_EntityCategory' => '_ModuleCategory_EntityCategory'),
    );

    public function Init()
    {
        $this->Component_Add('ad:category-tabs');
        $this->Component_Add('ad:topic');
        $this->Component_Add('ad:field');
        $this->Component_Add('ad:button');
        $this->Component_Add('ad:breadcrumbs');
        $this->Component_Add('ad:phone-hide');
        $this->Viewer_AppendScript($sPath = Plugin::GetTemplateWebPath('ad').'assets/js/init.js');
        
        $this->Viewer_AppendStyle($sPath = Plugin::GetTemplateWebPath('ad').'assets/css/plugin.css');
        
        $this->Geo_AddTargetType('topic');
    }

    public function Activate()
    {
        $this->Category_CreateTargetType('specialization', 'Специализации', array(), true);
        
        $aProperties = array(
            array(
                'data'=>array(
                'type'=>ModuleProperty::PROPERTY_TYPE_IMAGESET,
                'title'=>'Фотосет',
                'code'=>'fotoset',
                'sort'=>2
                ),
                'validate_rule'=>array(
                    'count_min' => 1,
                    'count_max' => 10
                ),
                'params'=>array(
                    'size' => '120x120crop'
                ),
                'additional'=>array()
            ),
            array(
                'data'=>array(
                'type'=>ModuleProperty::PROPERTY_TYPE_FLOAT,
                'title'=>'Цена',
                'code'=>'price',
                'sort'=>1
                ),
                'validate_rule'=>array(
                    'min' => 0,
                    'max' => 100000000.0,
                    'allowEmpty' => true
                ),
                'params'=>array(
                    'default' => 0
                ),
                'additional'=>array()
            )
        );
        $this->Property_CreateDefaultTargetPropertyFromPlugin($aProperties, 'topic_ad');
        return true;
    }

    public function Deactivate()
    {
        $this->Category_RemoveTargetType('specialization', ModuleCategory::TARGET_STATE_NOT_ACTIVE);
        return true;
    }
}