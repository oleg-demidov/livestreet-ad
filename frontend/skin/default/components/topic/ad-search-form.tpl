{component_define_params params=[ 'classes' ]}

{$component = "topic-ad-search-form"}

<form action="" method="POST" enctype="multipart/form-data" class="{$component} {$classes}" >
    {component 'field.text' 
        name="text"
        classes="{$component}-text"
        label=$aLang.plugin.ad.ad.search_form.text.label}



    {component 'ad:sort' template='ajax'
        classes = 'js-search-sort js-search-sort-menu'
        text = $aLang.sort.by_rating
        items = $aItemsDropdown} 
            
    {* Вид переключатель *}
    {component 'toggle'
        classes = 'js-search-toggle-view'
        label = $aLang.search.toggle_view.label
        hook  = 'search_users_view'
        items = [
            [ name => 'list',  'icon' => 'th-list',  text => $aLang.search.toggle_view.items.list ]
        ]}
 
      
    
</form>

