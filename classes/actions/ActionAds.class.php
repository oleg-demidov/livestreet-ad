<?php


class PluginAd_ActionAds extends ActionPlugin
{
    protected $oUserCurrent;
    
    protected $page;

    protected function RegisterEvent() {
        $url = Config::Get('plugin.ad.router.page');

        $this->RegisterEventExternal('Ads', 'PluginAd_ActionAds_EventAds');
        $this->AddEventPreg( '/^ajax-search$/i', 'Ads::EventAdsAjax');
        $this->AddEventPreg( '/^(page([1-9]\d{0,5}))?$/i', array('Ads::EventAds', $this->page));
        $this->AddEventPreg('/^[a-zA-Z0-9_]{1,50}/i', '/^(page([1-9]\d{0,5}))?$/i', array('Ads::EventAds', $this->page));
        $this->AddEventPreg('/^[a-zA-Z0-9_]{1,50}/i', '/^[a-zA-Z0-9_]{1,50}/i', '/^(page([1-9]\d{0,5}))?$/i', array('Ads::EventAds', $this->page));
        $this->AddEventPreg('/^[a-zA-Z0-9_]{1,50}/i', '/^[a-zA-Z0-9_]{1,50}/i', '/^[a-zA-Z0-9_]{1,50}/i', '/^(page([1-9]\d{0,5}))?$/i', array('Ads::EventAds', $this->page));
        $this->AddEventPreg('/^[a-zA-Z0-9_]{1,50}/i', '/^[a-zA-Z0-9_]{1,50}/i', '/^[a-zA-Z0-9_]{1,50}/i', '/^[a-zA-Z0-9_]{1,50}/i', '/^(page([1-9]\d{0,5}))?$/i', array('Ads::EventAds', $this->page));
    }

    public function Init() {
        $this->oUserCurrent = $this->User_GetUserCurrent();
        $this->page = Config::Get('plugin.ad.router.page');
    }

    public function _getFilterByParams() {
        
        $aParams = $this->GetParams();
        
        $aCountParams = sizeof($aParams);
        
        $iPageMatch = $this->_getPage($aCountParams-1);
        
        if($this->sCurrentEvent){
            $aParams = array_merge([$this->sCurrentEvent], $aParams);
        }
        
        $aFilter = [];
         
        if($iPageMatch){
            array_pop($aParams);
        }
        $aFilter['#page'] = $iPageMatch?$iPageMatch:1;
        
        $tryNameGeo = ucfirst( urldecode( end($aParams) ) );
        if($tryNameGeo){
            $oGeo = $this->_getGeoByParam( ['name_en_like' => $tryNameGeo] );
        }        
        if(isset($oGeo) and $oGeo){
            array_pop($aParams);
            $aFilter['geo_object'] = $oGeo;
        }
        
        $aCategories = $this->_getCategoriesByUrlFull(join('/',$aParams));

        if($aCategories and sizeof($aCategories)){
            $aFilter['categories'] = $aCategories;
        }       
        
        return $aFilter;
    }
    public function _getFilterByRequest() {
        $aFilter = [];
        
        if(getRequest('categories')){
            $aCategories = $this->Category_GetCategoryItemsByFilter([
                '#index-from' => 'id',
                'id in' => getRequest('categories'),
                '#select' => [ 'url_full', 'id']
            ]);
            $aFilter['categories'] = $aCategories;
        }        
        
        if($oGeo = $this->_getGeoByRequest()){
            $aFilter['geo_object'] = $oGeo;
        }
        
        $aFilter['#page'] = getRequest('page', 1);
        
        return $aFilter;
    }
    
    public function _getCategoriesByUrlFull($sUrl) {
        if(!$sUrl){
            return false;
        }
        $aUrls = explode('/', trim($sUrl));
        $aUrlsField = [];
        foreach($aUrls as $sUrl){
            $aUrlsField[] = "'".$sUrl."'";
        }
        $aCategories = $this->Category_GetCategoryItemsByFilter([
            '#index-from' => 'id',
            'url in' => $aUrls,
            '#select' => ['id', 'title', 'url_full', 'url'],
            '#order' => ['field:url' => $aUrlsField]
        ]);  
        return $aCategories;        
    }
    
    
    public function _getPage($aNumberLastParam) {
        if($iPageMatch =  $this->GetEventMatch( 2)){
            return $iPageMatch;
        }
        if($iPageMatch =  $this->GetParamEventMatch($aNumberLastParam, 2)){
            return $iPageMatch;
        }
        return 0;
    }
    
    public function _getUrlByRequest() {
        $url = $this->page;
        
        if(getRequest('categories')){
            $oCategory = $this->Category_GetCategoryByFilter([
                'id in' => getRequest('categories'),
                '#select' => [ 'url_full']
            ]);
            if($oCategory){
                $url .= '/'.$oCategory->getUrlFull();
            }
        }

        $oGeo = $this->_getGeoByRequest();
        
        if($oGeo){
            $url .= '/'.urlencode($oGeo->getNameEn());
        }
        
        return $url;
    }
    
    public function _getRequestAllow() {
        $aRequestAllows = [
            'text',
            //'categories',
            'country',
            'region',
            'city',
            'query',
            'sort_by'
        ];
        $aRequest=[];
        foreach($aRequestAllows as $sRequestAllow){
            if($val = getRequest($sRequestAllow)){
                $aRequest[$sRequestAllow] = $val;
            }
        }
        return $aRequest;
    }
        
    public function _getGeoByRequest() {
        $aFilter = [];
        if(!$aGeo = getRequest('geo')){
            return false;
        }
        if( isset($aGeo['country']) and ($iGeo = $aGeo['country']) ){
            $aFilter['country_id'] = $iGeo;
            $sGeoType = 'country';
        }
        if( isset($aGeo['region']) and ($iGeo = $aGeo['region']) ){
            $aFilter['region_id'] = $iGeo;
            $sGeoType = 'region';
        }
        if( isset($aGeo['city']) and ($iGeo = $aGeo['city']) ){
            $aFilter['city_id'] = $iGeo;
            $sGeoType = 'city';
        }
        if(isset($sGeoType)){
            $oGeo = $this->Geo_GetGeoObject($sGeoType, $aFilter[$sGeoType.'_id']);
            return $oGeo;
        }
        return false;
    }
    
    public function _getGeoByParam($aFilter) {
        $aCities = $this->Geo_GetCities($aFilter, [], 1, 1);
        if($aCities['count']){
            return sizeof($aCities['collection'])?$aCities['collection'][0]:null;
        }
        
        $aRegions = $this->Geo_GetRegions($aFilter, [], 1, 1); 
        if($aRegions['count']){
            return sizeof($aRegions['collection'])?$aRegions['collection'][0]:null;
        }
        
        $aCountries = $this->Geo_GetCountries($aFilter, [], 1, 1);
        if($aCountries['count']){
            return sizeof($aCountries['collection'])?$aCountries['collection'][0]:null;
        }

    }
    
    public function _getUrlByFilter($aFilter, $iPage = null) {
        $aParams = [];
        
        if(isset($aFilter['categories']) and sizeof($aFilter['categories'])){
            $aParams[] = end($aFilter['categories'])->getUrlFull();
        }
        
        if(isset($aFilter['geo_object'])){
            $aParams[] = strtolower($aFilter['geo_object']->getNameEn());
        }
        
        if($aFilter['#page'] and $aFilter['#page']>1){
            $aParams[] = 'page'.$aFilter['page'];
        }

        $sUrl = join('/',$aParams); 
        
        return $sUrl;
    }   
    
    

}