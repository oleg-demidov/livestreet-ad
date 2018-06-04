{**
 * Контакты
 *}
{if $topic and $topic->getType() == 'ad'}
    {$isEmty = 1}
    {$items = []}
    {$userfields = $oUserProfile->getUserFieldValues(true, array('contact', 'social'))}

    {foreach $userfields as $field}
        {if $field->getName() == 'phone'}
            {$isEmty = 0}
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

    {capture 'content'}
        {if $isEmty}
            {component 'blankslate' title={lang 'plugin.ad.ad.block_contacts.blankslate.title'}}
        {else}
            {component 'user' template='info-group' classes="user-contacts-list" name='contact'  items=$items}
        {/if}
        
        {if !$oUserCurrent}
            {$classesBut = "js-modal-toggle-login"}
        {/if}
        
        {component 'button' 
            classes="user-contacts-talk-but {$classesBut}"
            mods="large warning" 
            url={router page="talk/add/?talk_recepient_id={$oUserProfile->getId()}"} 
            text=$aLang.talk.send_message}
    {/capture}
    
    {component 'block' title={lang name='user.profile.contact'} content=$smarty.capture.content}

{/if}