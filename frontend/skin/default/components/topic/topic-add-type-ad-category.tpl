{component 'ad:category-tabs.checkboxes'  
    categories=$aCategories 
    categoriesSelected=$aCategoriesSelected
    rules=[
        'mincheck'      => Config::Get('plugin.ad.acl.user.category.min'), 
        'maxcheck'      => Config::Get('plugin.ad.acl.user.category.max'), 
        'required'      => true, 
        'trigger'       => 'change',
        'errors-container' => '#parsley_errors_container', 
        'group'         => 'category'
]}

{component 'button' type="button" text=$aLang.plugin.ad.ad.form.button_next mods="primary" classes="js-next-form"}