{**
 * Главная
 *
 * @parama array $topics
 * @parama array $paging
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_head_styles' append}
    <style>
        
        body{
                /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#dbdbdb+0,f2f2f2+40,f2f2f2+100 */
            background: rgb(219,219,219); /* Old browsers */
            background: -moz-linear-gradient(top, rgba(219,219,219,1) 0%, rgba(242,242,242,1) 40%, rgba(242,242,242,1) 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(top, rgba(219,219,219,1) 0%,rgba(242,242,242,1) 40%,rgba(242,242,242,1) 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to bottom, rgba(219,219,219,1) 0%,rgba(242,242,242,1) 40%,rgba(242,242,242,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#dbdbdb', endColorstr='#f2f2f2',GradientType=0 ); /* IE6-9 */
        }
    </style>
{/block}

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