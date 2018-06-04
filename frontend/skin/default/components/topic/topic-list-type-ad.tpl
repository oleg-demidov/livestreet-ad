{**
 * Список топиков
 *
 * @param array $topics
 * @param array $paging
 *}

{component_define_params params=[ 'topics', 'paging' ]}

{if $topics}
    {capture 'pagination'}
        {component 'pagination' 
            total=+$paging.iCountPage 
            current=+$paging.iCurrentPage 
            url="{$paging.sBaseUrl}/page__page__/{$paging.sGetParams}" 
            classes='js-pagination-topics-ad'}
    {/capture}
    
    {$smarty.capture.pagination}

    {foreach $topics as $topic}
        {component 'ad:topic' template='ad-item' topic=$topic }
    {/foreach}
    
    {$smarty.capture.pagination}
    
{else}
    {component 'blankslate' text=$aLang.common.empty}
{/if}