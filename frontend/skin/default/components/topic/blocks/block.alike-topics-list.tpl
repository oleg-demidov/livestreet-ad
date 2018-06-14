
{component_define_params params=[ 'topics' ]}

{capture 'block_content'}
    <ul class="ls-item-group topic-ad-group">
        {foreach $topics['collection'] as $topic}
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
            
            {$price = $topic->property->getPropertyValue('price')}
            {if !$price or $price == '0.00'}
                {$price = $aLang.plugin.ad.ad.price.contract}
            {else}
                {$price = "{$price} {$aLang.plugin.ad.ad.price.currency}"}
            {/if}
        
            {component 'item' 
                image   = $image
                title   = $topic->getTitle()
                titleUrl= $topic->getUrl()
                desc    = "<span class='ls-item-price'>{$price}</span>"
                isSmall = true}
        {/foreach}
    </ul>
{/capture}

{if $topics['count']}
    {component 'block'
        classes = "topics-alike"
        title   =   {lang 'plugin.ad.ad.block_alike_topics.title'}
        content = $smarty.capture.block_content}
{/if}