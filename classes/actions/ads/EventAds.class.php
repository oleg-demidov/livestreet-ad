<?php


/**
 * Description of EventTopic
 *
 * @author oleg
 */

class PluginAd_ActionAds_EventAds extends Event {

    public function Init() {
    }   

    public function EventAds() 
    {
        if(getRequest('form')){
            $url = $this->_getUrlByRequest();
            
            $aRequest = $this->_getRequestAllow();
            $sRequest = sizeof($aRequest)?"?". http_build_query($aRequest):"";
            
            Router::Location(Router::GetPath($url) . $sRequest);
        }
               
        $this->SetTemplateAction('ads-list');
        
        $aFilter = $this->_getFilterByParams();       
        
        if($sQuery = getRequest('query')){
            $aFilter['name'] = "%{$sQuery}%";
        }        
        

        $aAds = $this->getAdsByFilter($aFilter);        
        
        $sBaseUrl = Router::GetPath('masters/'.$this->_getUrlByFilter($aFilter));
        
        $aPaging = $this->Viewer_MakePaging($aAds['count'], $aFilter['#page'], 
            Config::Get('plugin.ad.topic.per_page'),
            Config::Get('plugin.ad.topic.count_page_line'), 
            $sBaseUrl,
            $this->_getRequestAllow());
        
        if(isset($aFilter['geo_object'])){
             $this->Viewer_Assign('oGeo', $aFilter['geo_object'] );
        }        
        
        $this->Viewer_Assign('specializationSelected', isset($aFilter['categories'])?$aFilter['categories']:[] );
        
        $aCategories = $this->Category_GetCategoriesTreeByTargetType('specialization');        
        $this->Viewer_Assign('aCategories', $aCategories);
        $this->AssignGeo();
        $this->Viewer_Assign('sBaseUrl', $sBaseUrl );        
        $this->Viewer_Assign('aAds',$aAds['collection'] ); 
        $this->Viewer_Assign('iAdsCount',$aAds['count'] );
        $this->Viewer_Assign('aPaging',$aPaging );
        $this->Viewer_AssignJs('url_search_ad', Config::Get('plugin.ad.router.page'));
         $this->Lang_AddLangJs('plugin.ad.ad.search_form.count_results');
    }
    
    public function AssignGeo() {
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
    }
    
    public function EventAdsAjax() {
        
        $this->Viewer_SetResponseAjax('json');
        
        $aFilter = $this->_getFilterByRequest();
        
        $aAds = $this->getAdsByFilter($aFilter);
        
        $sBaseUrl = Router::GetPath('masters/'.$this->_getUrlByFilter($aFilter));
        
        $sTitle = $this->Viewer_GetHtmlTitle();
        
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('topics',$aAds['collection'], true);
        $oViewer->Assign('oUserCurrent',$this->oUserCurrent );
        $this->Viewer_AssignAjax('html', $oViewer->Fetch("component@topic.list"));
        
        /*if(!getRequest('bMap')){
            $aPaging = $this->Viewer_MakePaging($aMasters['count'], $aFilter['page'], 
                $this->countOnPage,
                Config::Get('plugin.freelancer.poisk.count_page_line'), 
                $sBaseUrl, []);

            $oViewer = $this->Viewer_GetLocalViewer();
            $oViewer->Assign('aMasters',$aMasters['collection'], true);
            $oViewer->Assign('iMastersCount',$aMasters['count'], true );
            $oViewer->Assign('aPaging',$aPaging , true);
            $oViewer->Assign('oUserCurrent',$this->oUserCurrent );
            $this->Viewer_AssignAjax('html', $oViewer->Fetch("component@freelancer:master.page"));
            $sTitle = $oViewer->GetHtmlTitle();
        }else{
            $aUsers = $this->PluginYmaps_Geo_GetUsersWithGeo($aMasters['collection']);
            $this->Viewer_AssignAjax('users', $aUsers);
        }
        
        $this->Viewer_AssignAjax('breadcrumbs', $this->getBreadcrumbs(isset($aFilter['categories'])?$aFilter['categories']:[]) );*/
        
        $this->Viewer_AssignAjax('sBaseUrl', $sBaseUrl );
        $this->Viewer_AssignAjax('iPage', $aFilter['#page'] );
        $this->Viewer_AssignAjax('searchCount', $aAds['count']);
        $this->Viewer_AssignAjax('sTitle',  $sTitle);
        
    }
    
    public function getBreadcrumbs($aCategories) {
        if(!sizeof($aCategories)){
            return '';
        }
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('aCategories',$aCategories, true);
        return $oViewer->Fetch("component@freelancer:search-form.breadcrumbs");
    }
    
    public function getAdsByFilter($aFilter) {
        
        $aFilter['#with'] = [];
        $aFilter['#index-from'] = 'topic_id';
        //$aFilter['#select'] = ['topic_id', 'user_login','user_profile_avatar', 'user_rating'];
        
        if(isset($aFilter['categories']) and sizeof($aFilter['categories'])){
            $aCategoryIds = array_keys($aFilter['categories']);
            $aFilter['#category'] = end($aCategoryIds);
            unset($aFilter['categories']);
            $aFilter['#with'][] = 'category'; 
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
        
        $this->Logger_Notice(serialize($aFilter). getRequest('text'));
        
        $oTopics = $this->Topic_GetTopicItemsByFilter($aFilter);
        
        $this->Topic_AttachGeoTargets($oTopics['collection']);
        
        //print_r($oTopics);
   
        return $oTopics;//$this->User_GetUsersByFilter($aFilter, $aOrder, $aFilter['page'] ,$this->countOnPage,$aAllowData );        
        
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
        $Text = "%$Text%";
        $aTopics = $this->Topic_GetTopicItemsByFilter([
            '#select'       => ['t.topic_id'],
            '#index-from'   => 'topic_id',
            'topic_type'    => 'ad',
            'topic_publish' => 1,
            '#where'        => [
                " ((LOWER(t.topic_title) REGEXP ?) OR (LOWER(tc.topic_text) REGEXP ?))"=> [$sRegexp, $sRegexp]
            ],
            '#join'         => [
                "JOIN " . Config::Get('db.table.topic_content') . " tc ON tc.topic_id=t.topic_id" 
            ]
        ]);
        
        return array_keys($aTopics);
    }
}