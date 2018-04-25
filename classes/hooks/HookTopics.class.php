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
    
    public function TopicFieldsAdd($param) {
        if ($oType = $this->Category_GetTypeByTargetType('specialization')) {
            $aCategories = $this->Category_LoadTreeOfCategory(array('type_id' => $oType->getId()));
        }
        $this->Viewer_Assign('aCategories', $aCategories);
    }

    public function TopicEditAfter($aParams) {
        $aParams['oTopic']->AttachBehavior('category', [
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
        
        
        $aParams['oTopic']->_Validate();
        $this->Logger_Notice(serialize($aParams['oTopic']->_getValidateErrors()));
        $aParams['oTopic']->category->CallbackAfterSave();
    }
    
}
