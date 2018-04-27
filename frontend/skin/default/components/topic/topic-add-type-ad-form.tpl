{**
 * Базовая форма создания топика
 *
 * @param object $topic
 * @param object $type
 * @param array  $blogs
 * @param array  $blogId
 *}

{component_define_params params=[ 'topic', 'type', 'skipBlogs', 'blogs', 'classes' ]}

    {hook run="form_add_topic_begin" topic=$topic}
    {block 'add_topic_form_begin'}{/block}

    {* Выбор блога *}
    {if ! $skipBlogs and Config::Get('plugin.ad.acl.user.choose_ad_blog')}
        {$blogsSelect = []}
        {$blogsSelectId = []}
        {$blogsSelectedId=[]}

        {foreach $blogs as $blogType => $blogItems}
            {$blogsSelectOptions = []}

            {foreach $blogItems as $blog}
                {$blogsSelectOptions[] = [
                    'text' => $blog->getTitle()|escape,
                    'value' => $blog->getId()
                ]}
                {$blogsSelectId[]=$blog->getId()}
            {/foreach}

            {$blogsSelect[] = [
                'text' => {lang "blog.types.{$blogType}"},
                'value' => $blogsSelectOptions
            ]}
        {/foreach}

        {if $topic}
            {json var=array_intersect($topic->getBlogsId(),$blogsSelectId) assign='chosenOrder'}
            {$blogsSelectedId = $topic->getBlogsId()}
        {else}
            {if $_aRequest.blog_id}
                {$blogsSelectedId[] = $_aRequest.blog_id}
            {/if}
        {/if}

        {component 'field.autocomplete'
            label         = $aLang.topic.add.fields.blog.label
            name          = ''
            placeholder   = $aLang.topic.add.fields.blog.placeholder
            inputClasses  = 'js-topic-add-blogs ls-hidden'
            isMultiple    = true
            selectedValue = $blogsSelectedId
            inputAttributes    = [ 'data-chosen-order' => {$chosenOrder} ]
            items         = $blogsSelect}
    {/if}


    {* Заголовок топика *}
    {component 'field' template='text'
        name        = 'topic[topic_title]'
        value       = {(( $topic ) ? $topic->getTitle() : '')}
        label       = $aLang.topic.add.fields.title.label
        rules       = [
            'required'      => true,
            'maxlength'     => Config::Get('module.topic.title_max_length'),
            'minlength'     => Config::Get('module.topic.title_min_length'),
            'trigger'       => 'keyup focusout',
            'group'         => 'form'
    ]}

    {* URL топика *}
    {if $oUserCurrent->isAdministrator()}
        {component 'field' template='text'
            name        = 'topic[topic_slug_raw]'
            value       = {(( $topic ) ? $topic->getSlug() : '')}
            note        = {lang 'topic.add.fields.slug.note'}
            label       = {lang 'topic.add.fields.slug.label'}}
    {/if}

    {block 'add_topic_form_text_before'}{/block}


    {* Текст топика *}
    {if $type->getParam('allow_text')}
        {component 'editor'
            name            = 'topic[topic_text_source]'
            value           = (( $topic ) ? $topic->getTextSource() : '')
            label           = $aLang.topic.add.fields.text.label
            inputClasses    = 'js-editor-default'
            mediaTargetType = 'topic'
            mediaTargetId   = ( $topic ) ? $topic->getId() : ''
            rules       = [
            'required'          => true,
            'maxlength'         => Config::Get('module.topic.max_length'),
            'minlength'         => Config::Get('module.topic.min_length'),
            'trigger'           => 'keyup focusout',
            'group'             => 'form'
        ]}
    {/if}

    {block 'add_topic_form_text_after'}{/block}


    {* Теги *}
    {if $type->getParam('allow_tags')}
        {$tagsCountMin=Config::Get('module.topic.tags_count_min')}
        {$tagsCountMax=Config::Get('module.topic.tags_count_max')}
        {component 'field' template='text'
            name    = 'topic[topic_tags]'
            value     = {(( $topic ) ? $topic->getTags() : '')}
            rules   = [ 
                'trigger'   => 'keyup focusout',
                'group'     => 'form', 
                'required'  => !Config::Get('module.topic.tags_allow_empty'), 
                'rangetags' => "[{$tagsCountMin},{$tagsCountMax}]"]
            label   = {lang 'topic.add.fields.tags.label'}
            note    = {lang 'topic.add.fields.tags.note'}
            inputClasses = 'ls-width-full autocomplete-tags-sep'}
    {/if}


    {* Показывает дополнительные поля *}
    {insert name='block' block='propertyUpdate' params=[
        'target'      => $topic,
        'entity'      => 'ModuleTopic_EntityTopic',
        'target_type' => "topic_{$type->getCode()}"
    ]}


    {* Выбор превью *}
    {if $type->getParam('allow_preview')}
        {component 'field' template='image-ajax'
            label      = 'Превью'
            modalTitle = 'Выбор превью для топика'
            targetType = 'topic'
            targetId   = ( $topic ) ? $topic->getId() : ''
            classes    = 'js-topic-add-field-image-preview'}
    {/if}

    {* Вставка опросов *}
    {if $type->getParam('allow_poll')}
        {component 'poll' template='manage'
            targetType = 'topic'
            targetId   = ( $topic ) ? $topic->getId() : ''}
    {/if}

    {if $type->isAllowCreateDeferredTopic($oUserCurrent)}
        {if !$topic or !$topic->getPublishDraft() or ($topic->getDatePublish() and strtotime($topic->getDatePublish()) > time())}
            {$iDatePublish = null}
            {if $topic}
                {$iDatePublish = strtotime($topic->getDatePublish())}
                {if $iDatePublish < time()}
                    {$iDatePublish = null}
                {/if}
            {/if}
            <div>
                <div>{lang 'topic.add.fields.publish_date.label'}:</div>
                {component 'field.date' mods = 'inline'
                    name         = "topic[publish_date_raw][date]"
                    inputAttributes=[ "data-lsdate-format" => 'DD.MM.YYYY' ]
                    inputClasses = "js-field-date-default"
                    placeholder = {lang 'topic.add.fields.publish_date.label_date'}
                    value        = ($iDatePublish) ? {date_format date=$iDatePublish format='d.m.Y'} : ''}

                {component 'field.time' mods = 'inline'
                    name         = "topic[publish_date_raw][time]"
                    inputAttributes=[ "data-lstime-time-format" => 'H:i' ]
                    inputClasses = "js-field-time-default"
                    placeholder = {lang 'topic.add.fields.publish_date.label_time'}
                    value        = ($iDatePublish) ? {date_format date=$iDatePublish format='H:i'} : ''}
            </div>
        {/if}
    {/if}

    {* Запретить комментарии *}
    {component 'field' template='checkbox'
        name    = 'topic[topic_forbid_comment]'
        checked = {( $topic && $topic->getForbidComment() ) ? true : false }
        note    = $aLang.topic.add.fields.forbid_comments.note
        label   = $aLang.topic.add.fields.forbid_comments.label}


    {* Принудительный вывод топиков на главную (доступно только админам) *}
    {if $oUserCurrent->isAdministrator()}
        {component 'field' template='checkbox'
            name    = 'topic[topic_publish_index]'
            checked = {($topic && $topic->getPublishIndex()) ? true : false }
            note    = $aLang.topic.add.fields.publish_index.note
            label   = $aLang.topic.add.fields.publish_index.label}

        {component 'field' template='checkbox'
            name    = 'topic[topic_skip_index]'
            checked = {($topic && $topic->getSkipIndex()) ? true : false }
            note    = $aLang.topic.add.fields.skip_index.note
            label   = $aLang.topic.add.fields.skip_index.label}
    {/if}


    {block 'add_topic_form_end'}{/block}
    {hook run="form_add_topic_end" topic=$topic}


    {* Скрытые поля *}
    {component 'field' template='hidden' name='topic_type' value=$type->getCode()}

    {if $topic}
        {component 'field' template='hidden' name='topic[id]' value=$topic->getId()}
    {/if}


    
