{$component = 'fl-phone-hide'}
{component_define_params params=[ 'oField', 'iSize']}

{$oField->getValue()|substr:0:{$iSize|default:5}}... 
<a 
    data-param-i-user-id="{$oUserProfile->getId()}" 
    data-param-i-field-value-crop="{$oField->getValue()|substr:0:{$iSize|default:5}}" 
    data-param-i-field-value-size="{$iSize|default:5}" 
    href="/" 
    class="{$component}-linkshow"
    
>показать</a>
    