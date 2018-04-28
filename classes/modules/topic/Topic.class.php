<?php

class PluginAd_ModuleTopic extends PluginAd_Inherit_ModuleTopic
{
    protected $aBehaviors = array(
        // Категории
        'category' => array(
            'class'       => 'ModuleCategory_BehaviorModule',
            'target_type' => 'specialization',
        ),
    );
    
    public function GetTopicItemsByFilter($aFilter)
    {
        return parent::GetItemsByFilter($aFilter, 'ModuleTopic_EntityTopic');
    }
    
    public function AttachGeoTargets(&$oTopics) {
        $oTopicIds = array_keys($oTopics);
        
        $oGeoTargets = $this->Geo_GetTargetsByTargetArray('topic', $oTopicIds);
        foreach($oTopics as $iTopicId =>  $oTopic){
            $oTopic->setGeoTarget($oGeoTargets[$iTopicId]);
        }
    }
    
}