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
        
        $oGeoTarget = Engine::GetEntity('Geo_Target');
        if(isset($aFilter['geo_object'])){ 
            $oGeoTarget->_setData( array_merge( [
                'geo_type'  => $aFilter['geo_object']->getType(),
                'geo_id'    => $aFilter['geo_object']->getId(),
                $aFilter['geo_object']->getType().'_id'    => $aFilter['geo_object']->getId()
            ], $aFilter['geo_object']->_getData()) );
            $this->Viewer_Assign('oGeoTarget',$oGeoTarget ); 
            
        }
        
        if($sQuery = getRequest('query')){
            $aFilter['name'] = "%{$sQuery}%";
        }  
        
        if( isset($aFilter['categories']) ){
            $this->Viewer_Assign('categories', $aFilter['categories'] );
            $this->Viewer_Assign('breadcrumbs_items', $this->getBreadcrumbsItems( $aFilter['categories'] ) );
        }
        
        $aFilter['topic_publish'] = 1;
        $aFilter['#with'] = ['#category'];

        $aAds = $this->Topic_GetAdsByFilter($aFilter, ['geo', 'content' => ['properties', 'favourite']]);        
        
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
            '#select' => ['*', 'count(t.topic_tag_text) as count'],
            '#group' => ['topic_tag_text'],
            '#join' => [
                "JOIN " . Config::Get('db.table.topic') . " tc ON tc.topic_id = t.topic_id AND tc.topic_type = 'ad' "
            ]
        ]);
        $this->Tools_MakeCloud($aAdTags);
        $this->Viewer_Assign('tags', $aAdTags );
        
        $this->AssignGeo($oGeoTarget);
        $this->Viewer_Assign('aItemsDropdown', $aItemsDropdown);
        $this->Viewer_Assign('sMenuHeadItemSelect', 'masters');
        $this->Viewer_Assign('sBaseUrl', $sBaseUrl );        
        $this->Viewer_Assign('aAds',$aAds['collection'] ); 
        if(isset($aAds['geo_objects'])){
            $this->Viewer_Assign('aGeoObjects',$aAds['geo_objects'] );
        }
        $this->Viewer_Assign('iAdsCount',$aAds['count'] );
        $this->Viewer_Assign('paging',$aPaging );
        $this->Viewer_AssignJs('url_search_ad', Config::Get('plugin.ad.router.page'));
        $this->Lang_AddLangJs('plugin.ad.ad.search_form.count_results');
    }
    
    public function AssignGeo($oGeoTarget) {
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
    
    public function EventAdsAjax() {
        
        $this->Viewer_SetResponseAjax('json');
        
        $aFilter = $this->_getFilterByRequest();
        
        $aBreadcrumbsHTML = null;
        if( isset($aFilter['categories']) ){
            $aBreadcrumbsHTML = $this->getBreadcrumbsHTML( $aFilter['categories'] );
        }               
        
        $aFilter['#with'] = ['#category'];
        
        $aAds = $this->Topic_GetAdsByFilter($aFilter, ['geo', 'content' => ['properties', 'favourite']]);
        
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
        if(isset($aAds['geo_objects'])){
            $oViewer->Assign('aGeoObjects',$aAds['geo_objects'] );
        }
        
        $this->Viewer_AssignAjax('html', $oViewer->Fetch("component@ad:topic.ad-list"));
                
        $this->Viewer_AssignAjax('breadcrumbs_html', $aBreadcrumbsHTML );
        $this->Viewer_AssignAjax('sBaseUrl', $sBaseUrl );
        $this->Viewer_AssignAjax('requestAllow', $this->_getRequestAllow() );
        $this->Viewer_AssignAjax('request', $this->_getRequest() );
        $this->Viewer_AssignAjax('iPage', $aFilter['#page'] );
        $this->Viewer_AssignAjax('searchCount', $aAds['count']);
        $this->Viewer_AssignAjax('sTitle',  $sTitle);
        
    }
    
    public function EventAdsToMapAjax() {
        
        $this->Viewer_SetResponseAjax('json');
        
        $aFilter = $this->_getFilterByRequest();
        
        $aFilter['#page']   = [1, 10000];
        
        $aBreadcrumbsHTML = null;
        if( isset($aFilter['categories']) ){
            $aBreadcrumbsHTML = $this->getBreadcrumbsHTML( $aFilter['categories'] );
        }               
        
        $aAds = $this->Topic_GetAdsByFilter($aFilter);
        
        $aAds['collection'] = $this->PluginYmaps_Geo_GetTopicsWithLocation($aAds['collection']);
        
        $sBaseUrl = Router::GetPath($this->page.'/'.$this->_getUrlByFilter($aFilter));
        
        $sTitle = $this->Viewer_GetHtmlTitle();        
        
        
        $this->Viewer_AssignAjax('html', ''); 
        $this->Viewer_AssignAjax('objects', $aAds['collection']);
        $this->Viewer_AssignAjax('breadcrumbs_html', $aBreadcrumbsHTML );
        $this->Viewer_AssignAjax('sBaseUrl', $sBaseUrl );
        $this->Viewer_AssignAjax('requestAllow', $this->_getRequestAllow() );
        $this->Viewer_AssignAjax('request', $this->_getRequest() );
        $this->Viewer_AssignAjax('iPage', 1 );
        $this->Viewer_AssignAjax('searchCount', $aAds['count']);
        $this->Viewer_AssignAjax('sTitle',  $sTitle);
        
    }
    
    public function getBreadcrumbsItems($aCategories) {
        if(!sizeof($aCategories)){
            return null;
        }
        
        $aItems = [
            [
                'text'  => $this->Lang_Get('plugin.ad.ad.breadcrumbs.first'),
                'url'   => Router::GetPath($this->page)

            ]
        ];
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