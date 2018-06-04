{extends 'component@field.field'}

{block 'field_options' append}
    {component_define_params params=[ 'items' ]}
    {$component2 = 'search-sort'}
    {$classes = "{$classes} {$component2}"}
{/block}

{block 'field_input'}
        
        {component 'ad:sort' template='ajax'
            classes = $classes
            text = $aLang.sort.by_rating
            attributes = $attributes
            items = $items}   
    
    
{/block}




    