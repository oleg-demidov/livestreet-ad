{$component = 'ls-topic-geo'}
{component_define_params params=[ 'oGeoTarget' ]}


{if $oGeoTarget}
    {if $aGeoObjects}
        <span class="{$component}">
        {component 'icon' icon="map-marker"}
        {if $aGeoObjects['regions'][$oGeoTarget[0]->getRegionId()]}
            {$aGeoObjects['regions'][$oGeoTarget[0]->getRegionId()]->getName()}
        {else}
            {$aLang.plugin.ad.ad.no_geo_text}
        {/if}
        
        {if $aGeoObjects['cities'][$oGeoTarget[0]->getCityId()]}
            {component 'icon' icon="chevron-right" classes="bold"} 
            {$aGeoObjects['cities'][$oGeoTarget[0]->getCityId()]->getName()}
        {/if}
        </span>
    {/if}
{/if}