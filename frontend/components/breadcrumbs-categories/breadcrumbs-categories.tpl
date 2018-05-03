
{$component = 'ad-breadcrumbs-categories'}
{component_define_params params=[ 'categories']}

<div class="{$component}">
{if is_array($categories)}
    <ul>
        {foreach $categories as $category}
            <li class="{$component}-item">
                <a href="{router page='masters'}{$category->getUrlFull()}">{$category->getTitle()}</a> >
            </li>
        {/foreach}
    </ul>
{/if}
</div>
    
