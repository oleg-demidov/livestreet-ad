
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