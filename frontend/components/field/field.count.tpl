{extends 'component@field.field'}

{block 'field_options' append}
    {$component2 = 'fl-field-count'}
    {$classes = "{$classes} {$component2}"}
{/block}

{block 'field_input'}
    
    {capture 'field'}
        <input type="{$type|default:'text'}" {field_input_attr_common} />
    {/capture}  
    
    {component 'actionbar' items=[
        [
            'buttons' => [
                $smarty.capture.field
            ]
        ],
        [
            'buttons' => [
                [ 'text' => '<b>-</b>', 'classes'=>"{$component2}-min", 'type' => 'button', 'mods' => 'danger' ],
                [ 'text' => '<b>+</b>', 'classes'=>"{$component2}-plus", 'type' => 'button', 'mods' => 'success' ]
            ]
        ]
    ]}
{/block}




    