<?php

class PluginAd_ModuleTopic extends PluginAd_Inherit_ModuleTopic
{
    protected $aBehaviors = array(
        // Категории
        'category' => array(
            'class'       => 'ModuleCategory_BehaviorModule',
            'target_type' => 'specialization',
        ),
        'property' => 'ModuleProperty_BehaviorModule'
    );
    
    public function GetTopicItemsByFilter($aFilter)
    {
        return parent::GetItemsByFilter($aFilter, 'ModuleTopic_EntityTopic');
    }
    
    public function AttachGeoTargets(&$aTopics) {
        $aTopicIds = array_keys($aTopics);        
        
        $oGeoTargets = $this->Geo_GetTargetsByTargetArray('topic', $aTopicIds);
        foreach($aTopics as $iTopicId =>  $oTopic){
            if(isset($oGeoTargets[$iTopicId])){
                $oTopic->setGeoTarget($oGeoTargets[$iTopicId]);
            }
        }
        
        if(sizeof($oGeoTargets)){
            $aGeoTargets = call_user_func_array('array_merge_recursive', $oGeoTargets);
        }else{
            $aGeoTargets = [];
        }
        $aGeoObjects = $this->Geo_GetGeoObjectsByTargets($aGeoTargets);
        
        return $aGeoObjects;
    }    
    
    public function GetAdsByFilter($aFilter, $aAttach = []) {

        $aFilter['#index-from'] = 'topic_id';
        $aFilter['topic_type'] = 'ad';
        $aFilter['#cache'] = [ 2 => 5 ];
        
        //$aFilter['#select'] = ['t.topic_id'];
                
        if(isset($aFilter['categories']) and is_array($aFilter['categories'])){
            $aCategoryIds = [];
            foreach($aFilter['categories'] as $oCategory){
                $aCategoryIds[] = $oCategory->getId();
            } 

            if(sizeof($aCategoryIds)){
                $aFilter['#category'] = $this->Category_GetCategoriesIdByCategory(end($aCategoryIds), true);
            }
            
            unset($aFilter['categories']);
            $aFilter['#with'][] = 'category'; 
        }
        
        if(isset($aFilter['#page']) and ! is_array($aFilter['#page'])){
            $aFilter['#page'] = [$aFilter['#page'], Config::Get('plugin.ad.topic.per_page')];
        }
        
        if(isset($aFilter['geo_object'])){
            $aFilter['#join'] = [
                "JOIN " . Config::Get('db.table.geo_target') . " as g "
                . "ON ( t.topic_id=g.target_id AND g.".$aFilter['geo_object']->getType()."_id = ?d AND g.target_type = 'topic' )" => 
                [$aFilter['geo_object']->getId()]
            ];
        }
        unset($aFilter['geo_object']);
        
        if($sText = getRequest('text')){
            $aTopicIds = $this->GetTopicIdsByText($sText);
            if(is_array($aTopicIds)){
                $aFilter['topic_id in'] = array_merge($aTopicIds, [0]);
            }
        }
        
        //$this->Logger_Notice(print_r($aFilter, true));        
        
        $aTopics = $this->GetTopicAdItemsByFilter($aFilter);  
        
        if(isset($aAttach['content']) and is_array($aAttach['content'])){
            $aTopics['collection'] = $this->GetTopicsAdditionalData(array_keys($aTopics['collection']), $aAttach['content']);
        }
        
        if( in_array('geo', $aAttach)){
            $aTopics['geo_objects'] = $this->AttachGeoTargets($aTopics['collection']);
        }   

        return $aTopics;
        
    }
    
    public function GetTopicIdsByText($Text) {
        /**
         * Получаем список слов для поиска
         */
        $aWords = $this->Search_GetWordsForSearch(mb_strtolower($Text,"utf-8"));
        /**
         * Формируем регулярное выражение для поиска
         */
        $sRegexp = $this->Search_GetRegexpForWords($aWords);
        if(!$sRegexp){
            return false;
        }

        $aTopics = $this->GetTopicItemsByFilter([
            '#select'       => ['t.topic_id'],
            '#index-from'   => 'topic_id',
            'topic_type'    => 'ad',
            'topic_publish' => 1,
            '#where'        => [
                " ((LOWER(t.topic_title) REGEXP ?) OR (LOWER(tc.topic_text) REGEXP ?) OR (LOWER(tg.topic_tag_text) REGEXP ?))"
                => [$sRegexp, $sRegexp, $sRegexp]
            ],
            '#join'         => [
                "JOIN " . Config::Get('db.table.topic_content') . " tc ON tc.topic_id=t.topic_id",
                "LEFT JOIN " . Config::Get('db.table.topic_tag') . " tg ON tg.topic_id=t.topic_id "
            ]
        ]);
        
        return array_keys($aTopics);
    }
    
}