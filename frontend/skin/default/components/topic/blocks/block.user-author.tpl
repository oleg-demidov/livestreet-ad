{**
 * Блок с фотографией пользователя в профиле
 *}

{if $topic and $topic->getType() == 'ad'}
 
    {capture 'block_content'}    
        {$oUserTopic = $topic->getUser()}
        <img class="topic-user-avatar" src="{$oUserTopic->getProfileFotoPath()}">
        <center>
            <h4>
                <a href="{$oUserTopic->getUserWebPath()}">
                    {if $oUserTopic->getProfileName()}
                        {$oUserTopic->getProfileName()}
                    {else}
                        {$oUserTopic->getDisplayName()}
                    {/if}
                </a>
            </h4>
        </center>
    {/capture}

    {component 'block'
        title   =   {lang 'comments.comment.target_author'}
        content = $smarty.capture.block_content}
    
{/if}