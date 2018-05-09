{$component = 'ls-topic'}
{component_define_params params=[ 'topic', 'isSmall' ]}

{$aMedia = $topic->property->getPropertyValue('fotoset')}
{$oProperty =  $topic->property->getProperty('fotoset')}

{if {$aMedia|sizeof}}
    {if $isSmall}
        {$size = '70x70crop'}
    {else}
        {$size = $oProperty->getParam('size')}
    {/if}
    {$image = [
        'path' => $aMedia[0]->getFileWebPath( $size ),
        'alt'  => $topic->getTitle(),
        'url'  => $topic->getUrl()
    ]}
{/if}

{$type = ($topic->getType()) ? $topic->getType() : $type}

{capture 'footer'}
    {if !$isSmall}
        <div class="{$component}-footer">
            <ul class="{$component}-info ls-clearfix">
                {* Голосование *}
                {if ! $isPreview}
                    <li class="{$component}-info-item {$component}-info-item--vote">
                        {$isExpired = strtotime($topic->getDatePublish()) < $smarty.now - Config::Get('acl.vote.topic.limit_time')}

                        {component 'vote'
                                 target     = $topic
                                 classes    = 'js-vote-topic'
                                 mods       = 'small white topic'
                                 useAbstain = true
                                 isLocked   = ( $oUserCurrent && $topic->getUserId() == $oUserCurrent->getId() ) || $isExpired
                                 showRating = $topic->getVote() || ($oUserCurrent && $topic->getUserId() == $oUserCurrent->getId()) || $isExpired}
                    </li>
                {/if}

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

                {* Избранное *}
                <li class="{$component}-info-item {$component}-info-item--favourite">
                    {component 'favourite' classes="js-favourite-topic" target=$topic attributes=[ 'data-param-target_type' => $type ]}
                </li>

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
        </div>
    {/if}
{/capture}



{component 'item' 
    image   =       $image
    title   =       $topic->getTitle()
    titleUrl=       $topic->getUrl()
    desc    =       $topic->getTextShort()
    content =       $smarty.capture.footer
    classes =       "topic js-topic"
    }