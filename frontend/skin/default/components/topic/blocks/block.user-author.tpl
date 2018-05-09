{**
 * Блок с фотографией пользователя в профиле
 *}

{if $topic and $topic->getType() == 'ad'}
 
    {capture 'block_content'}
        {component 'user.header' user=$oUserProfile mods="user-author"}
    {/capture}

    {component 'block'
        title   =   {lang 'comments.comment.target_author'}
        content = $smarty.capture.block_content}
    
{/if}