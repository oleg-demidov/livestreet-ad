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
    {*insert name='block' block='propertyUpdate' params=[
        'target'      => $topic,
        'entity'      => 'ModuleTopic_EntityTopic',
        'target_type' => "topic_{$type->getCode()}"
    ]*}


   


    {block 'add_topic_form_end'}{/block}
    {hook run="form_add_topic_end" topic=$topic}


    {* Скрытые поля *}
    {component 'field' template='hidden' name='topic_type' value=$type->getCode()}

    {if $topic}
        {component 'field' template='hidden' name='topic[id]' value=$topic->getId()}
    {/if}

{component 'button' type="button" text=$aLang.plugin.ad.ad.form.button_next mods="primary" classes="js-next-form"}
    
