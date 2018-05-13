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
        
        $aItemsDropdown = [
            [
                'text' => $this->Lang_Get('plugin.ad.ad.search_form.sort.items.by_price'),
                'name' => 'prop:price'
            ],
            [
                'text' => $this->Lang_Get('plugin.ad.ad.search_form.sort.items.by_rating'),
                'name' => 'topic_rating'
            ],
            [
                'text' => $this->Lang_Get('plugin.ad.ad.search_form.sort.items.by_date_publish'),
                'name' => 'topic_date_publish'
            ]
        ];
               
        $this->SetTemplateAction('ads-list');
        
        $aFilter = $this->_getFilterByParams();       
        
        if($sQuery = getRequest('query')){
            $aFilter['name'] = "%{$sQuery}%";
        }  
        
        if( isset($aFilter['categories']) ){
            $this->Viewer_Assign('categories', $aFilter['categories'] );
            $this->Viewer_Assign('breadcrumbs_items', $this->getBreadcrumbsItems( $aFilter['categories'] ) );
        }

        $aAds = $this->Topic_GetAdsByFilter($aFilter);        
        
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
        
        $aAdTags = $this->Topic_GetTopicTagItemsByFilter([
            '#select' => ['t.topic_tag_id', 'topic_tag_text'],
            '#index-from' => 'topic_tag_id',
            '#join' => [
                "JOIN " . Config::Get('db.table.topic') . " tc ON tc.topic_id = t.topic_id AND tc.topic_type = 'ad' "
            ]
        ]);
        $this->Viewer_Assign('tags', $aAdTags );
        
        $this->AssignGeo();
        $this->Viewer_Assign('aItemsDropdown', $aItemsDropdown);
        $this->Viewer_Assign('sMenuHeadItemSelect', 'masters');
        $this->Viewer_Assign('sBaseUrl', $sBaseUrl );        
        $this->Viewer_Assign('aAds',$aAds['collection'] ); 
        $this->Viewer_Assign('iAdsCount',$aAds['count'] );
        $this->Viewer_Assign('paging',$aPaging );
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
        
        $aBreadcrumbsHTML = null;
        if( isset($aFilter['categories']) ){
            $aBreadcrumbsHTML = $this->getBreadcrumbsHTML( $aFilter['categories'] );
        }               
        
        $aAds = $this->Topic_GetAdsByFilter($aFilter);
        
        $sBaseUrl = Router::GetPath($this->page.'/'.$this->_getUrlByFilter($aFilter));
        
        $sTitle = $this->Viewer_GetHtmlTitle();
        
        $aPaging = $this->Viewer_MakePaging($aAds['count'], $aFilter['#page'], 
            Config::Get('plugin.ad.topic.per_page'),
            Config::Get('plugin.ad.topic.count_page_line'), 
            $sBaseUrl,
            $this->_getRequestAllow());
        
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('topics',$aAds['collection'], true);
        $oViewer->Assign('paging',$aPaging, true);
        $oViewer->Assign('oUserCurrent',$this->oUserCurrent );
        $this->Viewer_AssignAjax('html', $oViewer->Fetch("component@ad:topic.ad-list"));
        
        
        
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
        }*/
        
        
        $this->Viewer_AssignAjax('breadcrumbs_html', $aBreadcrumbsHTML );
        $this->Viewer_AssignAjax('sBaseUrl', $sBaseUrl );
        $this->Viewer_AssignAjax('requestAllow', $this->_getRequestAllow() );
        $this->Viewer_AssignAjax('request', $this->_getRequest() );
        $this->Viewer_AssignAjax('iPage', $aFilter['#page'] );
        $this->Viewer_AssignAjax('searchCount', $aAds['count']);
        $this->Viewer_AssignAjax('sTitle',  $sTitle);
        
    }
    
    public function getBreadcrumbsItems($aCategories) {
        if(!sizeof($aCategories)){
            return null;
        }
        
        $aItems = [];
        foreach($aCategories as $oCategory){
            $aItems[] = [
                'text'  => $oCategory->getTitle(),
                'url'   => Router::GetPath($this->page.'/'.$oCategory->getUrlFull())
            ];
        }
            
        return $aItems;
    }
    
    public function getBreadcrumbsHTML($aCategories) {
        if(!sizeof($aCategories)){
            return null;
        }
        
        $aItems = $this->getBreadcrumbsItems($aCategories);
            
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('items',$aItems, true);
        $oViewer->Assign('classes', 'js-category-ad-breadcrumbs', true);
        return $oViewer->Fetch("component@ad:breadcrumbs");
    }
    
    
    
    
}