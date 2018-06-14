{**
 * Базовый шаблон топика
 * Используется также для отображения превью топика
 *
 * @param object  $topic
 * @param boolean $isList
 * @param boolean $isPreview
 *}

{$component = 'ls-topic-ad'}
{component_define_params params=[ 'type', 'topic', 'isPreview', 'isList', 'mods', 'classes', 'attributes' ]}

{$user = $topic->getUser()}
{$type = ($topic->getType()) ? $topic->getType() : $type}

{if $isList}
    {component 'ad:topic' template='ad-item' params=$params }
{else}

    {$classes = "{$classes} topic js-topic"}

    {block 'topic_options'}
        {component 'ad:breadcrumbs' items=$breadcrumbs_items classes="js-category-ad-breadcrumbs"}
    {/block}

    <article class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
        {**
         * Хидер
         *}
        {block 'topic_header'}
            <header class="{$component}-header">
                {$_headingTag = ($isList) ? Config::Get('view.seo.topic_heading_list') : Config::Get('view.seo.topic_heading')}

                {* Заголовок *}
                <{$_headingTag} class="{$component}-title ls-word-wrap">
                    {$price = $topic->property->getPropertyValue('price')}
                    {if !$price or $price == '0.00'}
                        {$price = $aLang.plugin.ad.ad.price.contract}
                    {else}
                        {$price = "{$price} {$aLang.plugin.ad.ad.price.currency}"}
                    {/if}

                    <span class="{$component}-right">{$price}</span>
                    {block 'topic_title'}
                        {if $topic->getPublish() == 0}
                            {component 'icon' icon='file' attributes=[ title => {lang 'topic.is_draft'} ]}
                        {/if}

                        {if $isList}
                            <a href="{$topic->getUrl()}">{$topic->getTitle()|escape}</a>
                        {else}
                            {$topic->getTitle()|escape}
                        {/if}

                    {/block}
                </{$_headingTag}>
            </header>




        {/block}

        {* Дополнительные поля }
            {block 'topic_content_properties'}
                {if ! $isList}
                    {component 'property' template='output.list' properties=$topic->property->getPropertyList()}
                {/if}
            {/block*}

        {**
         * Текст
         *}
        {block 'topic_body'}
            {* Галерея *}
            {$aMedia = $topic->property->getPropertyValue('fotoset')}
            {$oProperty =  $topic->property->getProperty('fotoset')}

            {component 'ad:gallery' aMedia=$aMedia sizePreview=$oProperty->getParam('size')}

            {* Информация *}
            <ul class="{$component}-info">
                {block 'topic_header_info'}

                    <li class="{$component}-info-item {$component}-info-item--geo">
                        {component 'ad:topic.ad-item-geo' oGeoTarget=$topic->getGeoTarget()}
                    </li>

                    {$isDeferred = (strtotime($topic->getDatePublish())>time()) ? true : false}
                    <li class="{$component}-info-item {$component}-info-item--date{if $isDeferred}--deferred{/if}">
                        <time datetime="{date_format date=$topic->getDatePublish() format='c'}" title="{if $isDeferred}{lang 'topic.is_deferred'}{else}{date_format date=$topic->getDatePublish() format='j F Y, H:i'}{/if}">
                            {date_format date=$topic->getDatePublish() format="j F Y, H:i"}
                        </time>
                    </li>

                {/block}
            </ul>

            <div class="{$component}-content">
                <div class="{$component}-text ls-text">
                    {block 'topic_content_text'}
                        {if $isList and $topic->getTextShort()}
                            {$topic->getTextShort()}
                        {else}
                            {$topic->getText()}
                        {/if}
                    {/block}
                </div>

                {* Кат *}
                {if $isList && $topic->getTextShort()}
                    {component 'button'
                        classes = "{$component}-cut"
                        url     = "{$topic->getUrl()}#cut"
                        text    = "{$topic->getCutText()|default:$aLang.topic.read_more}"}
                {/if}
            </div>



            {* Опросы *}
            {block 'topic_content_polls'}
                {if ! $isList}
                    {component 'poll' template='list' polls=$topic->getPolls()}
                {/if}
            {/block}
        {/block}


        {**
         * Футер
         *}
        {block 'topic_footer'}
            {if ! $isList && $topic->getTypeObject()->getParam('allow_tags')}
                {$favourite = $topic->getFavourite()}

                {if ! $isPreview}
                    {component 'tags-personal'
                        classes       = 'js-tags-favourite'
                        tags          = $topic->getTagsObjects()
                        tagsPersonal  = ( $favourite ) ? $favourite->getTagsObjects() : []
                        isEditable    = ! $favourite
                        targetType    = 'topic'
                        targetId      = $topic->getId()}
                {/if}
            {/if}



            <footer class="{$component}-footer"> 
                {* Информация *}
                {block 'topic_footer_info'}
                    <ul class="{$component}-actions ls-clearfix">
                        {block 'topic_footer_info_items'}
                            {* Голосование }
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
                            {/if*}


                            {if ! $isList && ! $isPreview}
                                {* Избранное *}
                                <li class="{$component}-info-item {$component}-info-item--favourite">
                                    {component 'favourite' classes="js-favourite-topic-ad" target=$topic attributes=[ 'data-param-target_type' => $type ]}
                                </li>

                                {* Поделиться *}
                                <li class="{$component}-info-item {$component}-info-item--share">
                                    <div 
                                        class="yashare-auto-init" 
                                        data-yashareTitle="{$topic->getTitle()|escape}" 
                                        data-yashareLink="{$topic->getUrl()}" 
                                        data-yashareL10n="ru" 
                                        data-yashareType="" 
                                        data-yashareTheme="counter" 
                                        data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus">
                                    </div>
                                </li>
                            {/if}
                            {* Просмотров *}
                            <li class="{$component}-info-item {$component}-info-item--views">
                                {component 'icon' icon='eye'
                                    attributes=[
                                        'title' => {lang 'plugin.ad.ad.count_read'}
                                    ]}
                                {$topic->getCountRead()}
                            </li>

                            {* Управление *}
                            {if $topic->getIsAllowAction() && ! $isPreview}
                                <li class="{$component}-info-item {$component}-info-item--actions">
                                    {block 'topic_header_actions'}
                                        {$items = [
                                            [ 'icon' => 'edit', 'url' => $topic->getUrlEdit(), 'text' => $aLang.common.edit, 'show' => $topic->getIsAllowEdit() ],
                                            [ 'icon' => 'trash', 'url' => "{$topic->getUrlDelete()}?security_ls_key={$LIVESTREET_SECURITY_KEY}", 'text' => $aLang.common.remove, 'show' => $topic->getIsAllowDelete(), 'classes' => 'js-confirm-remove-default' ]
                                        ]}
                                    {/block}

                                    {component 'button.group' classes="" buttons = $items }
                                </li>
                            {/if}


                        {/block} {* /topic_footer_info_items *}
                    </ul>
                {/block} {* /topic_footer_info *}
            </footer>

            {* Всплывающий блок появляющийся при нажатии на кнопку Поделиться *}
            {if ! $isList && ! $isPreview}
                <div class="ls-tooltip" id="topic_share_{$topic->getId()}">
                    <div class="ls-tooltip-content js-ls-tooltip-content">
                        {hookb run="topic_share" topic=$topic isList=$isList}
                            <div class="yashare-auto-init" data-yashareTitle="{$topic->getTitle()|escape}" data-yashareLink="{$topic->getUrl()}" data-yashareL10n="ru" data-yashareType="small" data-yashareTheme="counter" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div>
                        {/hookb}
                    </div>
                </div>
            {/if}
        {/block} {* /topic_footer *}
    </article>
{/if}