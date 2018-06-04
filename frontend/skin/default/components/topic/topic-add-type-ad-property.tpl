{**
 * Базовая форма создания топика
 *
 * @param object $topic
 * @param object $type
 * @param array  $blogs
 * @param array  $blogId
 *}

{component_define_params params=[ 'topic', 'type', 'skipBlogs', 'blogs', 'classes' ]}

    

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

    
{component 'button' type="button" text=$aLang.plugin.ad.ad.form.button_next mods="primary" classes="js-next-form"}