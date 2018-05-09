{**
 * Блок с фотографией пользователя в профиле
 *}

{if $topic and $topic->getType() == 'ad'}
 
    {insert name="block" block="alikeTopics" params=[
        'plugin'    => 'ad',
        'topic' => $topic
    ]}
    
{/if}