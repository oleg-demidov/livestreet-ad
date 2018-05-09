
{component_define_params params=[ 'topics' ]}

{capture 'block_content'}
    <ul class="ls-item-group topic-ad-group">
        {foreach $topics['collection'] as $topic}
            {component 'ad:topic.ad-item' topic=$topic isSmall=true}
        {/foreach}
    </ul>
{/capture}

{if $topics['count']}
    {component 'block'
        classes = "topics-alike"
        title   =   {lang 'plugin.ad.ad.block_alike_topics.title'}
        content = $smarty.capture.block_content}
{/if}