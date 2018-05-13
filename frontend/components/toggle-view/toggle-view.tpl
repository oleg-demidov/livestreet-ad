{component_define_params params=[ 'itemsAdd', 'classes', 'attributes']}

{$buttons=[
    [  
        'text' => "Вид",  
        'type'=>'button', 
        'isDisabled' => true],
    [ 
        'icon'=>'list', 
        'text' => {$aLang.plugin.ad.ad.search_form.toggle.list}, 
        'classes'=>'js-show-list', 
        'type'=>'button', 
        'isDisabled' => true ,
        'attributes' => ['title'=>{$aLang.plugin.ad.ad.search_form.toggle.list_title} ]],
    [ 
        'icon'=>'map-o', 
        'text' => {$aLang.plugin.ymaps.toggle.map},
        'classes'=>'js-show-map', 
        'type'=>'button', 
        'attributes' => ['title'=>{$aLang.plugin.ad.ad.search_form.toggle.map_title} ]]
]}

{component 'button' template='group' classes="js-search-toggle-view ls-sort {$classes}" buttons=$buttons}