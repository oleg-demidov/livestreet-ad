<?php

class PluginAd_BlockAlikeTopics extends Block
{

    public function Exec()
    {
        $oTopic = $this->GetParam('topic');
        
        $aFilter = [
            '#where' => [
                't.topic_id <> ?d' => [$oTopic->getId()]
            ],
            '#page' => [1, Config::Get('plugin.ad.topic.count_alike')]
        ];
        
        $oTopic->AttachBehavior('category', [
            'class' => 'ModuleCategory_BehaviorEntity',
            'target_type' => 'specialization'
        ]);
        $aFilter['categories'] = $oTopic->category->getCategories();
        
        $oGeoObject = $this->Geo_GetGeoObjectByTarget('topic', $oTopic->getId());
        $aFilter['geo_object'] = $oGeoObject;
        
        $aTopics = $this->Topic_GetAdsByFilter($aFilter);
        
        $this->Viewer_Assign('topics', $aTopics, true);
        $this->SetTemplate('component@ad:topic.block.alike-topics-list');
    }
    
}