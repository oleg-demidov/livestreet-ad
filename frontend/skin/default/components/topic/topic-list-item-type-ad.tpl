{component_define_params params=[ 'topic' ]}

{$imagePath = $topic->getPreviewImageWebPath(Config::Get('module.topic.default_preview_size'))}

{if $imagePath}
    {$image = [
        'path' => $imagePath,
        'alt'  => $topic->getTitle()
    ]}
{/if}

{$type = ($topic->getType()) ? $topic->getType() : $type}

{capture 'fav'}
    {* Избранное *}
    <li class="{$component}-info-item {$component}-info-item--favourite">
        {component 'favourite' classes="js-favourite-topic" target=$topic attributes=[ 'data-param-target_type' => $type ]}
    </li>
    {* Управление *}
    {if $topic->getIsAllowAction() && ! $isPreview}
        {block 'topic_header_actions'}
            {$items = [
                [ 'icon' => 'edit', 'url' => $topic->getUrlEdit(), 'text' => $aLang.common.edit, 'show' => $topic->getIsAllowEdit() ],
                [ 'icon' => 'trash', 'url' => "{$topic->getUrlDelete()}?security_ls_key={$LIVESTREET_SECURITY_KEY}", 'text' => $aLang.common.remove, 'show' => $topic->getIsAllowDelete(), 'classes' => 'js-confirm-remove-default' ]
            ]}
        {/block}

        {component 'actionbar' items=[[ 'buttons' => $items ]]}
    {/if}
{/capture}



{component 'item' 
    image   =       $image
    title   =       $topic->getTitle()
    desc    =       $topic->getTextShort()
    content =       $smarty.capture.fav
    }