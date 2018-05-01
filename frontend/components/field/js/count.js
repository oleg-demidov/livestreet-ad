

(function($) {
    "use strict";

    $.widget( "livestreet.flFieldCount", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            

            // Селекторы
            selectors: {
                field:          '.fl-field-count',
                input:  "input",
                min: ".fl-field-count-min",
                plus: ".fl-field-count-plus"
            },

            afterchange: null,

            params: {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();  
            this.elements.min.on('click', this.min.bind(this));
            this.elements.plus.on('click', this.plus.bind(this));
        },
        min: function(){
            var count = parseInt(this.elements.input.val())-1;
            this.elements.input.val(count).change();
            this._trigger('afterchange', null, count);
        },
        plus: function(){
            var count = parseInt(this.elements.input.val())+1;
            this.elements.input.val(count).change();
            this._trigger('afterchange', null, count);
        }
    });
})(jQuery);

