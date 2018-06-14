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
        $this->AddHook('topic_edit_show', 'TopicFieldsEdit');
        
        //$this->AddHook('topic_edit_validate_before', 'TopicValidateBefore');
        $this->AddHook('topic_edit_after', 'TopicEditAfter');
        $this->AddHook('topic_add_after', 'TopicEditAfter');
        
        $this->AddHook('topic_show', 'TopicShow');
        
        
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
        if(Router::GetParam(0) !== 'ad'){
            return false;
        }
        $this->AddFields($aParams);
    }
    
    public function TopicFieldsEdit($aParams) {
        if(!isset($aParams['oTopic']) or $aParams['oTopic']->getType() !== 'ad'){
            return false;
        }  
        $this->AddFields($aParams);
    }
    
    public function AssignCategories() {
        if ($oType = $this->Category_GetTypeByTargetType('specialization')) {
            $aCategories = $this->Category_LoadTreeOfCategory(array('type_id' => $oType->getId()));
        }
        if(isset($aCategories)){
            $this->Viewer_Assign('aCategories', $aCategories);
            return $aCategories;
        }
        return [];
    }
        
    public function AddFields($aParams) {
            
        $this->AssignCategories();
        
        if(isset($aParams['oTopic'])){ 
            $aCategories = [];
            $oTopic  =  $this->AttachCategory($aParams['oTopic']);
            
            $aCategoriesEnt = $oTopic->category->getCategories();
            foreach ($aCategoriesEnt as $oCategory){
                $aCategories[] = $oCategory->getId();
            }
            $this->Viewer_Assign('aCategoriesSelected', $aCategories);
            /**
            * Загружаем гео-объект привязки
            */
            $oGeoTarget = $this->Geo_GetTargetByTarget('topic', $oTopic->getId());
            $this->Viewer_Assign('oGeoTarget', $oGeoTarget);
        }
        
        
        /**
         * Загружаем в шаблон список стран, регионов, городов
         */
        $aCountries = $this->Geo_GetCountries(array(), array('sort' => 'asc'), 1, 300);
        $this->Viewer_Assign('aGeoCountries', $aCountries['collection']);
        if (isset($oGeoTarget)) {
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
        
        $this->Viewer_Assign('aUserFieldsContact', $this->User_getUserFields(array('contact', 'social')));
    }

    public function TopicEditAfter($aParams) {
        
        if($aParams['oTopic']->getType() !== 'ad'){
            return false;
        }
        
        $oTopic = $this->AttachCategory($aParams['oTopic']);
        
        $oTopic->_Validate([$oTopic->category->getParam('validate_field')]);
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
        
        
        $oUserCurrent = $this->User_GetUserCurrent();
        /**
        * Динамические поля контактов, type = array('contact','social')
        */
        $aType = array('contact', 'social');
        $aFields = $this->User_getUserFields($aType);
        /**
         * Удаляем все поля с этим типом
         */
        $this->User_DeleteUserFieldValues($oUserCurrent->getId(), $aType);
        $aFieldsContactType = getRequest('profile_user_field_type');       
        $aFieldsContactValue = getRequest('profile_user_field_value');     
        if (is_array($aFieldsContactType)) {
            foreach ($aFieldsContactType as $k => $v) {
                $v = (string)$v;
                if (isset($aFields[$v]) and isset($aFieldsContactValue[$k]) and is_string($aFieldsContactValue[$k])) {
                    $this->User_setUserFieldsValues($oUserCurrent->getId(),
                        array($v => $aFieldsContactValue[$k]),
                        Config::Get('module.user.userfield_max_identical'));
                }
            }
        }
    }
    
    public function AttachCategory($oTopic) {
        $oTopic->AttachBehavior('category', [
            'class' => 'ModuleCategory_BehaviorEntity',
            'target_type'                    => 'specialization',
            'validate_enable' =>  true,
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
    
    public function TopicShow($aParams) {
        $oUserProfile = $aParams['oTopic']->getUser();
        $this->Viewer_Assign('oUserProfile', $oUserProfile);
        
        $oUserCurrent = $this->User_GetUserCurrent();
        $this->Viewer_Assign('oUserCurrent', $oUserCurrent);
        
        $this->Viewer_AddBlock('right', 'alikeTopics', ['plugin' => 'ad', 'topic' => $aParams['oTopic']], 7);
        
        $iCount = $this->Topic_GetCountFromTopicReadByFilter(['topic_id' => $aParams['oTopic']->getId()]);
        $aParams['oTopic']->setCountRead($iCount);
        
        $oTopic  =  $this->AttachCategory($aParams['oTopic']);
        $oCategory = $oTopic->category->getCategory();
        
        if($oCategory){
            $aCategories = $this->Category_GetCategoriesByUrlFull($oCategory->getUrlFull());
        }else{
            $aCategories = [];
        }
        
        $aBreadcrumbsItems = [
            [
                'text'  => $this->Lang_Get('plugin.ad.ad.breadcrumbs.first'),
                'url'   => Router::GetPath(Config::Get('plugin.ad.router.page'))

            ]
        ];
        foreach($aCategories as $oCategory){
            $aBreadcrumbsItems[] = [
                'text'  => $oCategory->getTitle(),
                'url'   => Router::GetPath(Config::Get('plugin.ad.router.page').'/'.$oCategory->getUrlFull())
            ];
        }        
        
        $this->Viewer_Assign('breadcrumbs_items', $aBreadcrumbsItems );
        
        $aTopics = [$aParams['oTopic']->getId() => $aParams['oTopic']];
        $aGeoObjects  =  $this->Topic_AttachGeoTargets($aTopics);
        $this->Viewer_Assign('aGeoObjects', $aGeoObjects );
        
        $this->Viewer_AddBlock('right', 'topicLocation', ['plugin' => 'ymaps', 'topic' => $aParams['oTopic']], 6);
        
    }
    
}
