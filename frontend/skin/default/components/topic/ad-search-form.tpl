{component_define_params params=[ 'classes' ]}

{$component = "topic-ad-search-form"}

<form action="" method="POST" enctype="multipart/form-data" class="{$component} {$classes}" >
    <div class="ls-grid-row">
        <div class="ls-grid-col ls-grid-col-8">
            {component 'field.text' 
                name="text"
                classes="{$component}-text"
                label=$aLang.plugin.ad.ad.search_form.text.label}
        </div>
        <div class="ls-grid-col ls-grid-col-4">
        
            {component 'ad:field.sort'
                label  = {lang 'plugin.ad.ad.search_form.sort.label'}
                classes = 'js-search-sort js-search-sort-menu'
                items = $aItemsDropdown
            }
        </div>
    </div>
      
    
</form>

