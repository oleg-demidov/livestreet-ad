
{component_define_params params=[  ]}

{capture 'content_block_search'}
    <form  method="POST" class="{$component}" action="{router page="masters"}" > 
        {component "field.hidden" name="form" value="1"}        
            
        {*component 'ymaps:fields.ajaxgeo' 
            classes="js-search-form-geo"
            label=$geoLabel 
            place=$oGeoTarget
            choosenGeo     = $oGeo*} 
            
        
            
        {component 'ad:field.category-tree' 
            url = {router page="masters"}
            categoriesSelected = $specializationSelected
            aCategories=$aCategories 
            label="Специализация"} 
            
        {* Местоположение *}
        {component 'field' template='geo'
            classes   = 'js-field-geo-default'
            name      = 'geo'
            label     = {lang name='user.settings.profile.fields.place.label'}
            countries = $aGeoCountries
            regions   = $aGeoRegions
            cities    = $aGeoCities
            place     = $oGeoTarget}
            
        {component 'button' text={lang 'plugin.ad.ad.block_search.button.text'}}
        
    </form>
{/capture}

{component 'block'
    title   = {lang 'plugin.ad.ad.block_search.title'}
    classes = 'js-block-default'
    content = $smarty.capture.content_block_search}