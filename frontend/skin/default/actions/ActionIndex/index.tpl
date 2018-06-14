{**
 * Главная
 *
 * @parama array $topics
 * @parama array $paging
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_container_before'}
    {$nameSplit = '.'|explode:Config::Get('view.name')}
    {component 'jumbotron'
        title    = "<span class='name-first'>{$nameSplit[0]}</span>.<span class='name-second'>{$nameSplit[1]}</span>"
        subtitle = Config::Get('view.description')
        titleUrl = {router page='/'}
        classes  = 'layout-header'}
{/block}  

{block 'layout_content'}
    <fieldset>
        <legend>{lang name='plugin.ad.ad.titles'}</legend>
        {component 'ad:category-tabs.links' categories=$aCategories}
    </fieldset>
{/block}