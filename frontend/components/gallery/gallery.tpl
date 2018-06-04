
{$component = 'fl-breadcrumbs'}
{component_define_params params=[ 'aMedia', 'sizePreview']}

{$sizePreview = {$sizePreview|default:'100x100crop'}}


{$galleryItems = []}
{if $aMedia}
    {foreach $aMedia as $oMedia}
        {$galleryItems[] = 
            [
                'preview'   => $oMedia->getFileWebPath( $sizePreview ),
                'url'       => $oMedia->getFileWebPath( )
            ]
        }
    {/foreach}
{/if}

{component 'gallery' items=$galleryItems}
    
