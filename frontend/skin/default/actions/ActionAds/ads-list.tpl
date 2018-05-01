{extends 'layouts/layout.base.tpl'}
    
{block 'layout_content_header' append}
    <div class="js-search-ajax-ads">
        {component 'ad:topic.ad-search-form'}
        <div class="js-search-ad-results-count"></div>
{/block}

{block 'layout_content'}
        <div class="js-search-ad-results">
            {component 'topic.list' topics=$aAds paging=$paging}
        </div>
    </div>
{/block}
