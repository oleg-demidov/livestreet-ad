{**
 * Контакты
 *}
{if $topic and $topic->getType() == 'ad'}
    {$items = []}
    {$userfields = $oUserProfile->getUserFieldValues(true, array('contact', 'social'))}

    {foreach $userfields as $field}
        {if $field->getName() == 'phone'}
            {$items[] = [
                'label'   => $field->getTitle()|escape,
                'content' => {component 'ad:phone-hide' oField=$field}
            ]}
            {continue}
        {/if}

        {$items[] = [
            'label'   => $field->getTitle()|escape,
            'content' => $field->getValue(true, true)
        ]}

    {/foreach}

    {component 'block' title={lang name='user.profile.contact'} content={component 'user' template='info-group' name='contact'  items=$items}}

{/if}