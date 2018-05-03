(function($) {
    "use strict";

    $.widget( "ad.topicAdTags", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            text_field:null,
            selectors: {
                item:".ls-tag-cloud-item a"
            },

            params : {}
        },
        _create: function () {
            this._super();
            
            this.elements.item.on('click', function(e){
                $(this.option('text_field')).val($(e.currentTarget).text()).keyup();
                return false;
            }.bind(this));
            
        }
    });
})( jQuery );