{$component = 'ls-topic-ad-item'}
{component_define_params params=[ 'topic', 'isSmall' ]}

{$aMedia = $topic->property->getPropertyValue('fotoset')}
{$oProperty =  $topic->property->getProperty('fotoset')}

{if {$aMedia|sizeof}}
    {if $isSmall}
        {$size = '100x100crop'}
    {else}
        {$size = $oProperty->getParam('size')}
    {/if}
    {$image = [
        'path' => $aMedia[0]->getFileWebPath( $size ),
        'alt'  => $topic->getTitle(),
        'url'  => $topic->getUrl()
    ]}
{else}
    {$image = [
        'path' => "{$LS->Component_GetWebPath('ad:topic')}/img/topic_blank.png",
        'alt'  => $topic->getTitle(),
        'url'  => $topic->getUrl()
    ]}
{/if}

<div class="{$component} {$classes} {cmods name=$component mods=$mods}" {cattr list=$attributes}>
    {if $image}
        <div class="{$component}-left">
            <a href="{$image[ 'url' ]}">
                <img src="{$image[ 'path' ]}" alt="{$image[ 'alt' ]}" title="{$image[ 'title' ]}" class="{$component}-image {$image[ 'classes' ]}">
            </a>
        </div>
    {/if}
    
    <div class="{$component}-body js-{$component}-body">
        
        {$price = $topic->property->getPropertyValue('price')}
        {if !$price or $price == '0.00'}
            {$price = $aLang.plugin.ad.ad.price.contract}
        {else}
            {$price = "{$price} {$aLang.plugin.ad.ad.price.currency}"}
        {/if}
        
        <h4 class="{$component}-title">
            
            <a href="{$topic->getUrl()}">{$topic->getTitle()}</a>            
        </h4>
        <span class="{$component}-price">{$price}</span>

        <div class="{$component}-description">
            {$topic->getTextWords(Config::Get('plugin.ad.topic.count_words_item'))|strip_tags}
        </div>
        
        <div class="{$component}-geo">
            {component 'ad:topic.ad-item-geo' oGeoTarget=$topic->getGeoTarget()}
        </div>
        
    </div>
        
    <div class="{$component}-footer js-{$component}-footer">
        
        <ul class="{$component}-info ls-clearfix">
             {* Избранное *}
            <li class="{$component}-info-item {$component}-info-item--favourite">
                {component 'favourite' classes="js-favourite-topic-ad" target=$topic attributes=[ 'data-param-target_type' => 1 ]}
            </li>  
        
        </ul>
        
    </div>
</div>
    