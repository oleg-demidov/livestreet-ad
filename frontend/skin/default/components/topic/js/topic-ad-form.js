(function($) {
    "use strict";

    $.widget( "ad.topicAdForm", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            selectors: {
                
            },

            params : {}
        },
        _create: function () {
            this._super();
            
            this.element.parsley().on('field:validate', function(inst){
        
                let tabs = ['category', 'form', 'contacts'];

                tabs.forEach(function(value, key){
                    let el = $('.ls-tab.'+value+'-tab .ls-tab-inner'); 

                    if( !(inst.isValid({group: value}) === true)){            
                        el.addClass('ls-button--danger');
                    }

                    if( inst.isValid({group: value} === true)){     
                        el.removeClass('ls-button--danger');
                    }
                });         

            }); 
            
        }
    });
})( jQuery );