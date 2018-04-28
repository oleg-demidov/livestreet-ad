(function($) {
    "use strict";

    $.widget( "livestreet.categoryTabs", $.livestreet.lsTabs, {
        /**
         * Дефолтные опции
         */
        options: {
            height:1000,
            selectors: {
                panes:".ls-tabs-panes",
                checkboxes:".ls-field--checkbox input",
            },
            masonry:{
                itemSelector:".fl-category-tabs-block",
                columnWidth:300,
//                transitionDuration: '0.2s',
                gutter: 5                
            },

            params : {}
        },
        _create: function () {
            this._super();
            
            this.onMasonry()
            
            this.option('tabactivate', this.onMasonry.bind(this));           
            
            //this.elements.panes.height( this.option('height') );
            
            if(this.element.hasClass('checkboxes')){
                
                this._on(this.elements.checkboxes,{change:'onClickCheckbox'});
            }
            
        },
        onMasonry: function(){
            $(this.getActiveTab()).lsTab('getPane').masonry(this.option('masonry'));
        },
        onClickCheckbox:function(e){ 
            this._trigger( 'checkboxchange', e, this );
            
            var field = $(e.target).closest('.ls-field--checkbox');
            var childItems = field.parent().find('input:not(.parent-item input)')
            var parentItem = field.parent().find('.parent-item input');
            
            if(field.hasClass('parent-item')){ 
                childItems.prop("checked", e.target.checked).change();
                return;
            }
            
            var bAllChecked = true;
            childItems.each(function(i, item){
                if(!$(this).prop("checked")){
                    bAllChecked = false;
                }
            })
            
            if(bAllChecked){
                parentItem.prop("checked", true);
            }else{
                parentItem.prop("checked", false);
            }
        }
    });
})( jQuery );