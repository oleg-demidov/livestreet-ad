
{component_define_params params=[ 'topic', 'type', 'skipBlogs', 'blogs', 'classes' ]}

    

{* Местоположение *}
{component 'field' template='geo'
    classes   = 'js-field-geo-default'
    name      = 'geo'
    label     = {lang name='user.settings.profile.fields.place.label'}
    countries = $aGeoCountries
    regions   = $aGeoRegions
    cities    = $aGeoCities
    place     = $oGeoTarget
    rules     = [
        'required'          => true,
        'trigger'           => 'keyup',
        'group'             => 'contacts'
    ]}
    
{* Контакты *}



<fieldset class="js-user-fields">
    <legend>{lang name='user.settings.profile.contact'}</legend>

    {$contacts = $oUserCurrent->getUserFieldValues( true, array('contact', 'social') )}

    {* Список пользовательских полей, шаблон определен в начале файла *}
    <div class="js-user-field-list ls-mb-15">
        {foreach $contacts as $contact}
            {call userfield field=$contact}
        {foreachelse}
            {component 'blankslate' classes='js-user-fields-empty' text=$aLang.common.empty}
        {/foreach}
    </div>

    {if $aUserFieldsContact}
        {component 'button' type='button' classes='js-user-fields-submit' text=$aLang.common.add}
    {/if}
</fieldset>

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