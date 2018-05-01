{extends 'component@field.field'}

{block 'field_options' append}
    {component_define_params params=[ '' ]}
    {$classes = "{$classes} fl-field-query"}
{/block}

{block 'field_input'}
    <div class="{$component}-icon">{component 'icon' icon="search"}</div>
    <input type="{$type|default:'text'}" {field_input_attr_common} placeholder="Введите запрос" />
    
    
{/block}




    