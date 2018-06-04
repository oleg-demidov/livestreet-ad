{component_define_params params=[ 'topic', 'type', 'skipBlogs', 'blogs', 'classes' ]}



<form action="" method="POST" enctype="multipart/form-data" id="topic-add-form" class="{$classes} js-form-validate-ad" data-content-action="{( $topic ) ? 'edit' : 'add'}">

{component 'tabs' classes='js-topic-forms' mods="align-left" tabs=[
    [ 
        classes => 'tab-category',
        attributes => ['data-tab-group' => 'category'],
        text => {$aLang.plugin.ad.ad.form.tab_specialization_title}, 
        content => {component 'ad:topic.type-ad-category' params=$params } 
    ],
    [ 
        classes => 'tab-form',
        attributes => ['data-tab-group' => 'form'],
        text => {$aLang.plugin.ad.ad.form.tab_form_title}, 
        content => {component 'ad:topic.type-ad-form' params=$params } 
    ],
    [ 
        classes => 'tab-property',
        attributes => ['data-tab-group' => 'property'],
        text => {$aLang.plugin.ad.ad.form.tab_properties_title}, 
        content => {component 'ad:topic.type-ad-property' params=$params } 
    ],
    [ 
        classes => 'tab-contacts',
        attributes => ['data-tab-group' => 'contacts'],
        text => {$aLang.plugin.ad.ad.form.tab_contact_title}, 
        content => {component 'ad:topic.type-ad-contact' params=$params } 
    ]
]}



</form>


{* Блок с превью текста *}
{component 'topic' template='preview'}