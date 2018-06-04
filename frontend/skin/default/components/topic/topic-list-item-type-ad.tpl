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
            <span class="{$component}-right">{$price}</span>
            <a href="{$topic->getUrl()}">{$topic->getTitle()}</a>            
        </h4>
        

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
            

            {* Автор топика *}
            <li class="{$component}-info-item {$component}-info-item--author">
                {component 'user' template='avatar' user=$topic->getUser() size='xsmall' mods='inline'}
            </li>

            {* Ссылка на комментарии *}
            {* Не показываем если комментирование запрещено и кол-во комментариев равно нулю *}
            {if ( ! $topic->getForbidComment() || ( $topic->getForbidComment() && $topic->getCountComment() ) )}
                <li class="{$component}-info-item {$component}-info-item--comments">
                    <a href="{$topic->getUrl()}#comments">
                        {lang name='comments.comments_declension' count=$topic->getCountComment() plural=true}
                    </a>

                    {if $topic->getCountCommentNew()}<span>+{$topic->getCountCommentNew()}</span>{/if}
                </li>
            {/if}

           

            {* Поделиться *}
            <li class="{$component}-info-item {$component}-info-item--share">
                {component 'icon' icon='share'
                    classes="js-popover-default"
                    attributes=[
                        'title' => {lang 'topic.share'},
                        'data-tooltip-target' => "#topic_share_{$topic->getId()}"
                    ]}
            </li>
            {* Управление *}
            {if $topic->getIsAllowAction() && ! $isPreview}
                <li class="{$component}-info-item {$component}-info-item--author ">
                    {$items = [
                        [ 'icon' => 'edit', 'url' => $topic->getUrlEdit(),  'show' => $topic->getIsAllowEdit() ],
                        [ 'icon' => 'trash', 'url' => "{$topic->getUrlDelete()}?security_ls_key={$LIVESTREET_SECURITY_KEY}",  'show' => $topic->getIsAllowDelete(), 'classes' => 'js-confirm-remove-default' ]
                    ]}

                    {component 'button.group' buttons=$items }
                </li>
            {/if}
        </ul>
        {* Всплывающий блок появляющийся при нажатии на кнопку Поделиться *}
        {if ! $isList && ! $isPreview}
            <div class="ls-tooltip" id="topic_share_{$topic->getId()}">
                <div class="ls-tooltip-content js-ls-tooltip-content">
                    {hookb run="topic_share" topic=$topic isList=$isList}
                        <div class="ya-share2" data-yashareTitle="{$topic->getTitle()|escape}" data-yashareLink="{$topic->getUrl()}" data-yashareL10n="ru" data-yashareType="small" data-yashareTheme="counter" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div>
                    {/hookb}
                </div>
            </div>
        {/if}
    </div>
</div>
    