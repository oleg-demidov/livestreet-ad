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

    <ul class="ls-item-group topic-ad-group">
        {foreach $topics as $topic}
            {component 'ad:topic' template='ad-item' topic=$topic }
        {/foreach}
    </ul>
    
    {$smarty.capture.pagination}
    
{else}
    {component 'blankslate' text=$aLang.common.empty}
{/if}