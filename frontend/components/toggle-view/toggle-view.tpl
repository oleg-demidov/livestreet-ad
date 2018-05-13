{component 'button' template='group' classes="js-ymap-enable-map ls-sort" buttons=[
    [  'text' => "Вид",  'type'=>'button', 'isDisabled' => true],
    [ 'icon'=>'list', 'text' => {$aLang.plugin.ad.ad.search_form.toggle.list}, 'classes'=>'js-show-list', 
        'type'=>'button', 'isDisabled' => true ,'attributes' => ['title'=>{$aLang.plugin.ad.ad.search_form.toggle.list_title} ]],
    [ 'icon'=>'map-o', 'text' => {$aLang.plugin.ymaps.toggle.map},'classes'=>'js-show-map', 
        'type'=>'button', 'attributes' => ['title'=>{$aLang.plugin.ad.ad.search_form.toggle.map_title} ]]
]}