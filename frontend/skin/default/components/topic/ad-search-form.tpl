{component_define_params params=[ 'classes' ]}

{$component = "topic-ad-search-form"}

<form action="" method="POST" enctype="multipart/form-data" class="{$component} {$classes}" >

    {component 'field.text' 
        name="text"
        classes="{$component}-text"
        label=$aLang.plugin.ad.ad.search_form.text.label}
        
    
        
    {component 'dropdown' 
        menu=$aItemsDropdown
        classes="js-{$component}-order"
        label=$aLang.plugin.ad.ad.search_form.text.label}
</form>

