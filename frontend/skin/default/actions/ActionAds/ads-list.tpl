{extends 'layouts/layout.base.tpl'}
    
{block 'layout_content_header' append}
    <div class="js-search-ajax-ads">
        {component 'ad:topic.ad-search-form'}
        <div class="js-search-ad-results-count">{lang 'plugin.ad.ad.search_form.count_results' count=$iAdsCount plural=true}</div>
{/block}

{block 'layout_content'}
        <div class="js-search-ad-results">
            {component 'ad:topic.ad-list' topics=$aAds paging=$paging}
        </div>
    </div>
{/block}
