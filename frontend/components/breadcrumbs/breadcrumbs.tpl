
{$component = 'fl-breadcrumbs'}
{component_define_params params=[ 'items', 'current', 'classes']}

{if {$items|sizeof}}

    <ul class="{$component} {$classes}"> 
        {if is_array($items)}
            {foreach $items as $item name="bread"}
                <li class="{$component}-item ">
                    <a class="{$component}-item-link {if $current == $item.name}active{/if}" href="{$item.url}">{$item.text}</a>
                    {if not $smarty.foreach.bread.last}
                        {component 'icon' icon="chevron-right"}
                    {/if}
                </li>
            {/foreach}
        {/if}
    </ul>
{/if}
    
