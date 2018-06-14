<?php

class PluginAd_ActionProfile extends PluginAd_Inherit_ActionProfile{
    
    public function Init()
    {
        parent::Init();
        
    }
    protected function RegisterEvent() {
        parent::RegisterEvent();
        
         $this->AddEventPreg('/^.+$/i', '/^created/i', '/^ads/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventCreatedTopicsAd');
               
    }
    
    protected function EventCreatedTopicsAd() {
        if (!$this->CheckUserProfile()) {
            return parent::EventNotFound();
        }
        $this->sMenuProfileItemSelect = 'created';
        $this->sMenuSubItemSelect = 'ads';
        /**
         * Передан ли номер страницы
         */
        if ($this->GetParamEventMatch(1, 0) == 'ads') {
            $iPage = $this->GetParamEventMatch(2, 2) ? $this->GetParamEventMatch(2, 2) : 1;
        } else {
            $iPage = $this->GetParamEventMatch(1, 2) ? $this->GetParamEventMatch(1, 2) : 1;
        }
        /**
         * Получаем список топиков
         */
        $aFilter = array(
            'topic_type'    => 'ad',
            //'topic_publish' => 1,
            'user_id'       => $this->oUserProfile->getId(),
            '#page'         => [$iPage,  Config::Get('module.topic.per_page')],
            '#with'         => ['#category']
        );        
        $aResult = $this->Topic_GetAdsByFilter($aFilter, ['geo', 'content' => ['properties', 'favourite']]);
        
        $aTopics = $aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_ad_list_show', array('aTopics' => $aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), $this->oUserProfile->getUserWebPath() . 'created/ads');
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('paging', $aPaging);
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_AddHtmlTitle($this->Lang_Get('user.publications.title') . ' ' . $this->oUserProfile->getLogin());
        $this->Viewer_AddHtmlTitle($this->Lang_Get('user.publications.nav.topics'));
        $this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss') . 'personal_blog/' . $this->oUserProfile->getLogin() . '/',
            $this->oUserProfile->getLogin());
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('created.topics');
    }
}