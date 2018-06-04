{extends 'component@field.field'}

{block 'field_options' append}
    {$component2 = 'field-diapazon'}
    {$pref_name = $name}
{/block}

{block 'field_input'}
    
    {* Цена от и до *}
        <div class="ls-grid-row {$component2}-row">
            <div class="ls-grid-col ls-grid-col-6">
                {$name = "{$pref_name}_from"}
                От <input class="{$component2}-input-from" type="text" {field_input_attr_common} />
            </div>
            <div class="ls-grid-col ls-grid-col-6">
                {$name = "{$pref_name}_to"}
                До <input class="{$component2}-input-to" type="text" {field_input_attr_common} />
            </div>
        </div>
{/block}




    