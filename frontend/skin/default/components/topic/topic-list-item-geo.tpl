{$component = 'ls-topic-geo'}
{component_define_params params=[ 'oGeoTarget' ]}


{if $oGeoTarget}
    {if $aGeoObjects}
        <span class="{$component}">
        {component 'icon' icon="map-marker"}
        {$aGeoObjects['regions'][$oGeoTarget[0]->getRegionId()]->getName()}
         {component 'icon' icon="chevron-right" classes="bold"} 
        {$aGeoObjects['cities'][$oGeoTarget[0]->getCityId()]->getName()}
        </span>
    {/if}
{/if}