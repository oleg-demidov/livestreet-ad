<?php
/*
 * Хук убирает из выдачи поиска топиков типы ad
 */

class PluginAd_HookTopics extends Hook{
    public function RegisterHook()
    {
        
        $this->AddHook('get_count_topics_by_custom_filter', 'TopicAdRemove');
        
        $this->AddHook('get_topics_by_custom_filter', 'TopicAdRemove');
        
        $this->AddHook('topic_add_show', 'TopicFieldsAdd');
        $this->AddHook('topic_edit_show', 'TopicFieldsAdd');
        
        //$this->AddHook('topic_edit_validate_before', 'TopicValidateBefore');
        $this->AddHook('topic_edit_after', 'TopicEditAfter');
        $this->AddHook('topic_add_after', 'TopicEditAfter');
    }

    
    public function TopicAdRemove($aParams)
    {
        $aTypes = $this->Topic_GetTopicTypes(true);
        
        if(($key = array_search('ad', $aTypes)) !== false){
            unset($aTypes[$key]);
        }
        $aParams['aFilter']['topic_type'] = $aTypes;
    }
    
    public function TopicFieldsAdd($aParams) {
        if ($oType = $this->Category_GetTypeByTargetType('specialization')) {
            $aCategories = $this->Category_LoadTreeOfCategory(array('type_id' => $oType->getId()));
        }
        if(isset($aCategories)){
            $this->Viewer_Assign('aCategories', $aCategories);
        }
        
        if(isset($aParams['oTopic'])){ 
            $aCategories = [];
            $oTopic  =  $this->AttachCategory($aParams['oTopic']);
            
            $aCategoriesEnt = $oTopic->category->getCategories();
            foreach ($aCategoriesEnt as $oCategory){
                $aCategories[] = $oCategory->getId();
            }
            $this->Viewer_Assign('aCategoriesSelected', $aCategories);
        }
        
        /**
         * Загружаем гео-объект привязки
         */
        $oGeoTarget = $this->Geo_GetTargetByTarget('topic', $oTopic->getId());
        $this->Viewer_Assign('oGeoTarget', $oGeoTarget);
        /**
         * Загружаем в шаблон список стран, регионов, городов
         */
        $aCountries = $this->Geo_GetCountries(array(), array('sort' => 'asc'), 1, 300);
        $this->Viewer_Assign('aGeoCountries', $aCountries['collection']);
        if ($oGeoTarget) {
            if ($oGeoTarget->getCountryId()) {
                $aRegions = $this->Geo_GetRegions(array('country_id' => $oGeoTarget->getCountryId()),
                    array('sort' => 'asc'), 1, 500);
                $this->Viewer_Assign('aGeoRegions', $aRegions['collection']);
            }
            if ($oGeoTarget->getRegionId()) {
                $aCities = $this->Geo_GetCities(array('region_id' => $oGeoTarget->getRegionId()),
                    array('sort' => 'asc'), 1, 500);
                $this->Viewer_Assign('aGeoCities', $aCities['collection']);
            }
        }
    }

    public function TopicEditAfter($aParams) {
        
        $oTopic = $this->AttachCategory($aParams['oTopic']);
        
        $oTopic->_Validate();
        $oTopic->category->CallbackAfterSave();
        
        
        $aGeo = getRequest('geo');

        if (isset($aGeo['city']) && $aGeo['city']) {
            $oGeoObject = $this->Geo_GetGeoObject('city', (int)$aGeo['city']);
        } elseif (isset($aGeo['region']) && $aGeo['region']) {
            $oGeoObject = $this->Geo_GetGeoObject('region', (int)$aGeo['region']);
        } elseif (isset($aGeo['country']) && $aGeo['country']) {
            $oGeoObject = $this->Geo_GetGeoObject('country', (int)$aGeo['country']);
        } else {
            $oGeoObject = null;
        }
        /**
        * Создаем связь с гео-объектом
        */
        
       
        if ($oGeoObject) {
            $this->Geo_CreateTarget($oGeoObject, 'topic', $oTopic->getId());
            
        } else {
            $this->Geo_DeleteTargetsByTarget('topic', $oTopic->getId());
        }
    }
    
    public function AttachCategory($oTopic) {
        $oTopic->AttachBehavior('category', [
            'class' => 'ModuleCategory_BehaviorEntity',
            'target_type'                    => 'specialization',
            // Обязательное заполнение категории
            'validate_require'               => false,
            // Получать значение валидации не из сущности, а из реквеста (используется поле form_field)
            'validate_from_request'          => true,
            // Минимальное количество категорий, доступное для выбора
            'validate_min'                   => 1,
            // Максимальное количество категорий, доступное для выбора
            'validate_max'                   => 5,
            // Возможность выбрать только те категории, у которых нет дочерних
            'validate_only_without_children' => true
        ]);
        return $oTopic;
    }
    
}
