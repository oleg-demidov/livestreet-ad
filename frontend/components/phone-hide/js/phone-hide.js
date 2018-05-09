(function($) {
    "use strict";

    $.widget( "freelancer.phoneHide", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Редактор к которому привязано текущее окно
            editor: $(),

            // Ссылки
            urls: {
                // Вставка файла
                load: aRouter.ajax + 'phone-show'
            },

            // Селекторы
            selectors: {
            },

            uploader_options: {},

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
            this._on( this.element, { click: 'loadPhone' } );

        },

        /**
         * 
         */
        loadPhone: function( event ) {
            this._load( 'load', {}, "onLoad");
            return false;
        },

        /**
         * 
         */
        onLoad: function( res ) {
            if(res.phone){
                this.element.parent().html(res.phone);
            }
        }

    });
})(jQuery);