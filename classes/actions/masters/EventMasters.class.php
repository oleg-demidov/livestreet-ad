<?php


/**
 * Description of EventTopic
 *
 * @author oleg
 */

class PluginAd_ActionMasters_EventMasters extends Event {

    public function Init() {
    }   

    public function EventMasters() 
    {
        if(getRequest('form')){
            $url = $this->_getUrlByRequest();
            
            $aRequest = $this->_getRequestAllow();
            $sRequest = sizeof($aRequest)?"?". http_build_query($aRequest):"";
            
            Router::Location(Router::GetPath($url) . $sRequest);
        }
        
        $this->Component_Add('freelancer:master');
        $this->Component_Add('freelancer:search-form');
        $this->Viewer_AssignJs('ymapsOptions', Config::Get('plugin.ymaps.search_map'));
        $this->Viewer_AssignJs('ymapsOptionsStat', Config::Get('plugin.ymaps.options.profile'));
        
        $this->SetTemplateAction('masters');
        
        $aFilter = $this->_getFilterByParams();         
                
        $aOrder['user_rating'] = 'desc';       
        
        if($sQuery = getRequest('query')){
            $aFilter['name'] = "%{$sQuery}%";
        }        
        
        if(isset($aFilter['geo_object'])){
            $aFilter['geo_'.$aFilter['geo_object']->getType()] = $aFilter['geo_object']->getId();
        }

        $aMasters = $this->getMastersByFilter($aFilter, $aOrder);        
        
        $sBaseUrl = Router::GetPath('masters/'.$this->_getUrlByFilter($aFilter));
        
        $aPaging = $this->Viewer_MakePaging($aMasters['count'], $aFilter['#page'], 
            Config::Get('plugin.ad.topic.per_page'),
            Config::Get('plugin.ad.topic.count_page_line'), 
            $sBaseUrl,
            $this->_getRequestAllow());
        
        if(isset($aFilter['geo_object'])){
             $this->Viewer_Assign('oGeo', $aFilter['geo_object'] );
        }        
        
        $this->Viewer_Assign('specializationSelected', isset($aFilter['categories'])?$aFilter['categories']:[] );
        
        $this->Viewer_Assign('sBaseUrl', $sBaseUrl );        
        $this->Viewer_Assign('aMasters',$aMasters['collection'] ); 
        $this->Viewer_Assign('iMastersCount',$aMasters['count'] );
        $this->Viewer_Assign('aPaging',$aPaging );
    }
    
    public function EventMastersAjax() {
        
        $this->Viewer_SetResponseAjax('json');
        
        $aFilter = [];
        
        if (getRequestStr('city')) {
            $aFilter['geo_city'] = getRequestStr('city');
        } elseif (getRequestStr('region')) {
            $aFilter['geo_region'] = getRequestStr('region');
        } elseif (getRequestStr('country')) {
            $aFilter['geo_country'] = getRequestStr('country');
        }
        
        if($oGeo = $this->_getGeoByRequest()){
            $aFilter['geo_object'] = $oGeo;
        }
        
        if(!$sCategoryUrlFull = getRequest('category_url_full')){
            $aCategoryIds = getRequest('categories',[]);
            $oCategory = $this->Category_GetCategoryByFilter( ['id' => end( $aCategoryIds )] );
            $sCategoryUrlFull = $oCategory?$oCategory->getUrlFull():'';
            
        }
        $aCategories = $this->_getCategoriesByUrlFull($sCategoryUrlFull);
        
        if($aCategories and sizeof($aCategories)){
            $aFilter['categories'] = $aCategories;
        }         
        
        $sOrderWay = in_array(getRequestStr('order'), array('desc', 'asc')) ? getRequestStr('order') : 'desc';
        $sOrderField = in_array(getRequestStr('sort_by'), array(
            'user_rating',
            'user_date_register',
            'user_login',
            'user_profile_name'
        )) ? getRequestStr('sort_by') : 'user_rating';
                
        $aOrder =[$sOrderField => $sOrderWay];
        
        if (is_numeric(getRequestStr('page')) and getRequestStr('page') > 0) {
            $aFilter['#page'] = getRequestStr('page');
        } else {
            $aFilter['#page'] = 1;
        } 
        
        if($sQuery = getRequest('query')){
            $aFilter['name'] = "%{$sQuery}%";
        }        
        
        $aAllowData = ['geo_target'];
        
        if(getRequest('bMap')){
            $this->countOnPage = 10000;
            $aAllowData = null;
        }
        
        $aMasters = $this->getMastersByFilter($aFilter, $aOrder, $aAllowData);
        
        $sBaseUrl = Router::GetPath('masters/'.$this->_getUrlByFilter($aFilter));
        
        $sTitle = $this->Viewer_GetHtmlTitle();
        
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
        
        $this->Viewer_AssignAjax('breadcrumbs', $this->getBreadcrumbs(isset($aFilter['categories'])?$aFilter['categories']:[]) );
        
        $this->Viewer_AssignAjax('sBaseUrl', $sBaseUrl );
        $this->Viewer_AssignAjax('iPage', $aFilter['page'] );
        $this->Viewer_AssignAjax('iMastersCount', $aMasters['count']);
        $this->Viewer_AssignAjax('sTitle',  $sTitle);*/
        
    }
    
    public function getBreadcrumbs($aCategories) {
        if(!sizeof($aCategories)){
            return '';
        }
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('aCategories',$aCategories, true);
        return $oViewer->Fetch("component@freelancer:search-form.breadcrumbs");
    }
    
    public function getMastersByFilter($aFilter, $aOrder) {
        
        $aFilter['#with'] = [];
        $aFilter['#index-from'] = 'topic_id';
        //$aFilter['#select'] = ['topic_id', 'user_login','user_profile_avatar', 'user_rating'];
        
        if(isset($aFilter['categories']) and sizeof($aFilter['categories'])){
            $aCategoryIds = array_keys($aFilter['categories']);
            $aFilter['#category'] = end($aCategoryIds);
            $aFilter['#with'][] = 'category'; 
        }
        
        print_r($aFilter);
        
        unset($aFilter['geo_object']);
        
        $oTopics = $this->Topic_GetTopicItemsByFilter($aFilter);
        
        $this->Topic_AttachGeoTargets($oTopics['collection']);
        
        print_r($oTopics);
   
        return $oTopics;//$this->User_GetUsersByFilter($aFilter, $aOrder, $aFilter['page'] ,$this->countOnPage,$aAllowData );        
        
    }
    
    publ
    
}