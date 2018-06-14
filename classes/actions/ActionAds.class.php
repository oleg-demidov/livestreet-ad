<?php


class PluginAd_ActionAds extends ActionPlugin
{
    protected $oUserCurrent;
    
    protected $page;

    protected function RegisterEvent() {
        $url = Config::Get('plugin.ad.router.page');

        $this->RegisterEventExternal('Ads', 'PluginAd_ActionAds_EventAds');
        $this->AddEventPreg( '/^ajax-search$/i', 'Ads::EventAdsAjax');
        $this->AddEventPreg( '/^ajax-search-ads-map$/i', 'Ads::EventAdsToMapAjax');
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
        
        $aCategories = $this->Category_GetCategoriesByUrlFull(join('/',$aParams));

        if($aCategories and sizeof($aCategories)){
            $aFilter['categories'] = $aCategories;
        }       
        
        return $aFilter;
    }
    public function _getFilterByRequest() {
        $aFilter = [];

        if(getRequest('category_url_full')){
            $aCategories = $this->Category_GetCategoriesByUrlFull(getRequest('category_url_full'));
            if(sizeof($aCategories)){
                $aFilter['categories'] = $aCategories;
            }
        }elseif(getRequest('categories') ){
            $aCategories = $this->Category_GetCategoryItemsByFilter([
                '#index-from' => 'id',
                'id in' => getRequest('categories'),
                '#select' => [ 'url_full', 'id', 'title']
            ]);
            $aFilter['categories'] = $aCategories;
        }        
        
        if($oGeo = $this->_getGeoByRequest()){
            $aFilter['geo_object'] = $oGeo;
        }
        
        $sOrderWay = in_array(getRequestStr('order'), array('desc', 'asc')) ? getRequestStr('order') : 'desc';
        $sOrderField = in_array(getRequestStr('sort_by'), array(
            'topic_rating',
            'topic_date_publish',
            'prop:price'
        )) ? getRequestStr('sort_by') : 'topic_rating';        
        $aFilter['#order'] = [
            $sOrderField => $sOrderWay
        ];
        if($sOrderField == 'prop:price'){
            $aFilter['#prop:price !='] = -1;
        }
        
        if(($iPriceFrom = getRequest('price_from')) !== ''){
            $aFilter['#prop:price >'] = $iPriceFrom;
        }
        if(($iPriceTo = getRequest('price_to')) !== ''){
            $aFilter['#prop:price <'] = $iPriceTo;
        }
        
        $aFilter['#page'] = getRequest('page', 1);
        
        return $aFilter;
    }
    
//    public function _getCategoriesByUrlFull($sUrl) {
//        if(!$sUrl){
//            return false;
//        }
//        $aUrls = explode('/', trim($sUrl));
//        $aUrlsField = [];
//        foreach($aUrls as $sUrl){
//            $aUrlsField[] = "'".$sUrl."'";
//        }
//        $aCategories = $this->Category_GetCategoryItemsByFilter([
//            '#index-from' => 'id',
//            'url in' => $aUrls,
//            '#select' => ['id', 'title', 'url_full', 'url'],
//            '#order' => ['field:url' => $aUrlsField]
//        ]);  
//        return $aCategories;        
//    }
    
    
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
            'text'
        ];
        $aRequest=[];
        foreach($aRequestAllows as $sRequestAllow){
            if($val = getRequest($sRequestAllow)){
                $aRequest[$sRequestAllow] = $val;
            }
        }
        return $aRequest;
    }
    
    public function _getRequest() {
        $aRequestAllows = [
            'text',
            'categories',
            'geo',
            'page'            
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
        $aCountryFilter = $this->_getCountryFilter();
        
        $aCities = $this->Geo_GetCities(array_merge($aFilter, $aCountryFilter), [], 1, 1);
        if($aCities['count']){
            return sizeof($aCities['collection'])?array_shift($aCities['collection']):null;
        }
        
        $aRegions = $this->Geo_GetRegions(array_merge($aFilter, $aCountryFilter), [], 1, 1); 
        if($aRegions['count']){
            return sizeof($aRegions['collection'])?array_shift($aRegions['collection']):null;
        }
        
        $aCountries = $this->Geo_GetCountries($aFilter, [], 1, 1);
        if($aCountries['count']){
            return sizeof($aCountries['collection'])?array_shift($aCountries['collection']):null;
        }

    }
    
    public function _getCountryFilter() {
        $aCountries = $this->Geo_GetCountries(['code' => strtoupper(Config::Get('plugin.ad.country_code'))], [], 1, 1);
        if(sizeof($aCountries)){
            return [ 'country_id' => array_shift($aCountries['collection'])->getId() ];
        }
        return [];
    }
    
    public function _getUrlByFilter($aFilter, $iPage = null) {
        $aParams = [];
        
        if(isset($aFilter['categories']) and sizeof($aFilter['categories'])){
            $aParams[] = end($aFilter['categories'])->getUrlFull();
        }
        
        if(isset($aFilter['geo_object'])){
            $aParams[] = strtolower($aFilter['geo_object']->getNameEn());
        }
        
        if( !is_null($iPage) ){
            $aParams[] = 'page'.$iPage;
        }

        $sUrl = join('/',$aParams); 
        
        return $sUrl;
    }   
    

}