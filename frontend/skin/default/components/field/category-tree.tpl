{**
 * Вывод категорий на странице создания нового объекта
 *}
{extends 'component@field.field'}

{block 'field_options' append}    
    {component_define_params params=[ 'aCategories', 'categoriesSelected',  'params', 'mods', 'classes', 'attributes', 'url' ]}
    
    {$categoriesSelectedId=[]}
    {foreach $categoriesSelected as $categorySelected}
        {$categoriesSelectedId[] = $categorySelected->getId()}
    {/foreach}
    
    {$categorySelected = end($categoriesSelected)}
    
    {if $categorySelected}
        {$chooseName = $categorySelected->getTitle()}
        {$chooseId = $categorySelected->getId()}
        {$chooseUrlFull = $categorySelected->getUrlFull()}
    {/if}
    
    {$levelItems = []}

    {foreach $aCategories as $aCategory}
        {$levelItems[$aCategory['parent_id']][] = $aCategory}
        
    {/foreach}
    
    {$classes = "$classes fl-category-tree js-category-tree"}
{/block}


{block 'field_input'}
    <div class="{$component}-icon">{component 'icon' icon="bars"}</div>
    <div class="input-close-but" >X</div>
    <input type="text" class="{$component}-input {$component}-category-input js-field-category" 
            placeholder="{$aLang.plugin.ad.ad.search_form.category.placeholder}"
           value="{$chooseName}" data-show-dropdown="{$showDropdown}" autocomplete="off">
    {if $chooseId}
        <input type="hidden" name="categories[]" value="{$chooseId}" class="appended-category appended-category-id" />
    {/if}
    <input type="hidden" name="category_url_full" value="{$chooseUrlFull}" class="appended-category appended-category-url" />
    
    {$aItems = [["text" => {lang 'plugin.ymaps.loading'}, "classes" => 'ls-loading']]} 
    
    {component 'dropdown.menu' text='Выбрать' items=$aItems isSubnav=true classes="js-category-tree-dropdown"}
    {component 'dropdown.menu' text='Выбрать' items=$aItems isSubnav=true classes="js-category-find-dropdown"}
    
    <div class="preparations" style="display:none;">
        {foreach $levelItems as $key => $alevelMenu}
            {$aItems = []}
            {foreach $alevelMenu as $alevelItem}
                {$bSelected = in_array($alevelItem['entity']->getId(), $categoriesSelectedId)}
                {$aItem = [
                    'text' => $alevelItem['entity']->getTitle(), 
                    'classes' => "{if $bSelected}selected{/if}",
                    'attributes'=>[ 
                        'data-url-full' => $alevelItem['entity']->getUrlFull(), 
                        'data-id' => $alevelItem['entity']->getId(), 
                        'valuelo' => {$alevelItem['entity']->getTitle()|lower},
                        'value' => $alevelItem['entity']->getTitle()], 
                    'url' => "{$url}{$alevelItem['entity']->getUrlFull()}"
                ]}
                {if $alevelItem['children_count']}
                    {$aItem['menu'] = ['items' => [[]]]}
                {/if}
                {$aItems[] = $aItem}
            {/foreach}
            
            {$attr = ['data-parent-id' => 0]}
            {if $key}
                {$attr = ['data-parent-id' => $key]}
            {/if}
            {component 'nav' items=$aItems attributes=$attr isSubnav=true mods = 'stacked'}<br>

        {/foreach}
    </div>
    
{/block}



