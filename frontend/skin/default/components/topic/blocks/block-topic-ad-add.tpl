{if $oUserCurrent}
    {$butAdd.url = {router page="content/add/ad"}}
{else}
    {$butAdd.url = {router page="auth/login"}}
    {$butAdd.classes = "js-modal-toggle-login"}
{/if}

{component "ad:button" 
    text        = $aLang.plugin.ad.ad.block_add.text
    mods        = "warning large ad-add"
    params      = $butAdd
}