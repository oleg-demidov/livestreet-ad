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
            if(isset($oGeoTargets[$iTopicId])){
                $oTopic->setGeoTarget($oGeoTargets[$iTopicId]);
            }
        }
    }
    
    public function GetAdsByFilter($aFilter) {
        
        $aFilter['#with'] = [];
        $aFilter['#index-from'] = 'topic_id';
        $aFilter['topic_type'] = 'ad';
        //$aFilter['#select'] = ['t.topic_id'];
        
        if(isset($aFilter['categories']) and sizeof($aFilter['categories'])){
            $aCategoryIds = [];
            foreach($aFilter['categories'] as $oCategory){
                $aCategoryIds[] = $oCategory->getId();
            } 
            
            $aFilter['#category'] = $this->Category_GetCategoriesIdByCategory(end($aCategoryIds), true);
            unset($aFilter['categories']);
            $aFilter['#with'][] = 'category'; 
        }
        
        if(isset($aFilter['#page']) and ! is_array($aFilter['#page'])){
            $aFilter['#page'] = [$aFilter['#page'], Config::Get('plugin.ad.topic.per_page')];
        }
        
        if(isset($aFilter['geo_object'])){
            $aFilter['#join'] = [
                "JOIN " . Config::Get('db.table.geo_target') . " as g "
                . "ON ( t.topic_id=g.target_id and g.".$aFilter['geo_object']->getType()."_id = ?d )" => 
                [$aFilter['geo_object']->getId()]
            ];
            unset($aFilter['geo_object']);
        }
        
        if($sText = getRequest('text')){
            $aTopicIds = $this->GetTopicIdsByText($sText);
            if(is_array($aTopicIds)){
                $aFilter['topic_id in'] = array_merge($aTopicIds, [0]);
            }
        }
        
        //$this->Logger_Notice(print_r($aFilter, true));        
        
        $aTopics = $this->GetTopicItemsByFilter($aFilter);

        $this->AttachGeoTargets($aTopics['collection']);
        
        $aTopics['collection'] = $this->GetTopicsAdditionalData(array_keys($aTopics['collection']));
        
        if(sizeof($aTopics['collection'])){
            $this->Category_AttachCategoriesForTargetItems($aTopics['collection'], 'specialization');
        }
        
        return $aTopics;
        
    }
    
}