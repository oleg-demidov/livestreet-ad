
{component_define_params params=[  ]}

{capture 'content_block_search'}
    <form  method="POST" class="{$component}" action="{router page="masters"}" > 
        {component "field.hidden" name="form" value="1"}        
            
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
            
        {* Цена от и до *}
        {component 'ad:field' template='diapazon'
            name      = 'price'
            classes   = 'js-field-price'
            label     = {lang name='plugin.ad.ad.block_search.price.label'}
        }  
            
            
        {component 'button' classes="js-search-ajax-button" mods="primary" text={lang 'plugin.ad.ad.block_search.button.text'}}
        
    </form>
{/capture}

{component 'block'
    title   = {lang 'plugin.ad.ad.block_search.title'}
    classes = 'js-block-default'
    content = $smarty.capture.content_block_search}