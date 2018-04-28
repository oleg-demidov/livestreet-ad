
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
        'trigger'           => 'change focusout submit',
        'group'             => 'contacts',
        'type'              => 'number',
        'multiple'          => true,
        'mincheck'          => 2
    ]}
