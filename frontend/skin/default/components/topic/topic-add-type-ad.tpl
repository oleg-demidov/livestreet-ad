{component_define_params params=[ 'topic', 'type', 'skipBlogs', 'blogs', 'classes' ]}



<form action="" method="POST" enctype="multipart/form-data" id="topic-add-form" class="{$classes} js-form-validate js-form-validate-ad" data-content-action="{( $topic ) ? 'edit' : 'add'}">

{component 'tabs' classes='js-topic-category' tabs=[
    [ 
        classes => 'category-tab',
        text => {$aLang.plugin.ad.ad.form.tab_specialization_title}, 
        content => {component 'ad:category-tabs.checkboxes'  
                        categories=$aCategories 
                        categoriesSelected=$aCategoriesSelected
                        rules=[
                            'mincheck'      => Config::Get('plugin.ad.acl.user.category.min'), 
                            'maxcheck'      => Config::Get('plugin.ad.acl.user.category.max'), 
                            'required'      => true, 
                            'trigger'       => 'change',
                            'errors-container' => '#parsley_errors_container', 
                            'group'         => 'category'
                    ]}
    ],
    [ 
        classes => 'form-tab',
        text => {$aLang.plugin.ad.ad.form.tab_form_title}, 
        content => {component 'ad:topic.type-ad-form' params=$params } 
    ],
    [ 
        classes => 'form-imageset',
        text => {$aLang.plugin.ad.ad.form.tab_properties_title}, 
        content => {component 'ad:topic.type-ad-imageset' params=$params } 
    ],
    [ 
        classes => 'contacts-tab',
        text => {$aLang.plugin.ad.ad.form.tab_contact_title}, 
        content => {component 'ad:topic.type-ad-contact' params=$params } 
    ]
]}

{**
     * Кнопки
     *}

    {* Опубликовать / Сохранить изменения *}
    {component 'button'
        id      = {( $topic ) ? 'submit-edit-topic-publish' : 'submit-add-topic-publish' }
        mods    = 'primary'
        classes = 'ls-fl-r'
        text    = $aLang.topic.add.button[ ( !$topic or ( $topic && $topic->getPublish() == 0 ) ) ? 'publish' : 'update' ]}

    {* Превью *}
    {component 'button' type='button' classes='js-topic-preview-text-button' text=$aLang.common.preview_text}

    {* Сохранить в черновиках / Перенести в черновики *}
    {if ! $topic}
        {component 'button' type='button' classes='js-topic-draft-button' text=$aLang.topic.add.button.save_as_draft}
    {else}
        {component 'button' type='button' classes='js-topic-draft-button' text=$aLang.topic.add.button[ ( $topic->getPublish() != 0 ) ? 'mark_as_draft' : 'update' ]}
    {/if}

</form>


{* Блок с превью текста *}
{component 'topic' template='preview'}