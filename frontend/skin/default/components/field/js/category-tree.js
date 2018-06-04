

(function($) {
    "use strict";

    $.widget( "livestreet.flFieldCategoryTree", $.livestreet.lsDropdown, {
        options: {
            levelsLoad:[
                'countries',
                'regions',
                'cities'
            ],
            
            // Селекторы
            selectors: {
                prepareMenus:".preparations",
                
                toggle:".ls-field-category-input",
                input:".ls-field-category-input",
                
                menu:".js-category-tree-dropdown",
                menuFind:".js-category-find-dropdown",
                
                form:null,
                clear:".input-close-but"
            },
            params: {},
            i18n: {
                select_region: '@field.geo.select_region',
                select_city: '@field.geo.select_city'
            },
            afterchange:null
        },
        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () { 
            this._super();
            
            this.element.data('tree-menu', true);
            
            if(this.option('selectors.form') === null){
                this.elements.form = this.element;
            }else{
                this.elements.form = $(this.option('selectors.form'));
            }
            
            this.element.lsDropdown({
                selectors:{
                    menu:this.option('selectors.menuFind'),
                    toggle:this.option('selectors.toggle')
                }
            });
            
            if(this.elements.input.val()){
                this.elements.clear.css('display', 'block');
            }

            this.elements.clear.on('click', function(event){
                $(event.currentTarget).css('display', 'none');
                
                this.clearForm();
                
                this.elements.input.val('').change();
                this.elements.input.attr('title', '');                
                
                this._trigger( 'afterchange', null, { context: this } );
                //this.elements.searchAjaxUsers.lsSearchAjax("update");
                //this.show();
            }.bind(this));
            
            this.makeMenu();  
            
            this.toggleTreeMenu();
            
            this._on(this.elements.input, {keyup:"change"});
        },
        change:function(event){
            if(this.elements.input.val()){
                this.hide();
                this.toggleFindMenu();
                this.element.lsDropdown('show');
                var lis = this.elements.menu.find('li[value*="'+this.elements.input.val()+'"], li[valuelo*="'+this.elements.input.val()+'"]');
                lis = lis.clone();
                this._on(lis, {click: "choose"});
                this.elements.menuFind.empty().append(lis)
            }else{
                this.element.lsDropdown('hide');
                this.toggleTreeMenu();
                this.show();
            }
        },
        toggleFindMenu:function(){
            this.elements.toggle.off('click').on('click', function(){
                this.element.lsDropdown('show');
            }.bind(this));
        },
        toggleTreeMenu:function(){
            this.elements.toggle.off('click').on('click', this.show.bind(this));
        },
        makeMenu:function(){
            var uls = this.elements.prepareMenus.find('ul');
            
            this.elements.menu.empty();
            
            uls.each(function(i,ul){
                var jqUl = $(ul); 
                if(jqUl.data('parentId') === 0){
                    this.elements.menu.append( jqUl.children('li') );
                    return;
                }
                if(jqUl.data('parentId') !== undefined ){ 
                    var liForAppend = this.elements.menu.find('li[data-id="'+jqUl.data('parentId')+'"]');
                    liForAppend.find('ul').remove();
                    this._on(liForAppend, {click: "choose"});
                    liForAppend.append(jqUl);
                }
            }.bind(this));  
            
        },
        choose:function(event){ 
            this.clearForm();
            this.hide();
            this.element.lsDropdown('hide');
            
            var choosen = $(event.target).closest('li');
            this.elements.clear.css('display', 'block');
            
            this.addForm('categories[]', choosen.data('id'), 'appended-category-id');
            this.addForm('category_url_full', choosen.data('urlFull'), 'appended-category-url');
            
            this.elements.input.val(choosen.attr('value')).change();
            this.elements.input.attr('title', choosen.attr('value'));           
            
            this._trigger( 'afterchange', null, { context: this } );
            return false;
        },
        addForm:function(key, value, classes){ 
            classes = classes || '';
            var input = $(document.createElement('input'));
            input.attr({name:key, value:value, type:'hidden'}).addClass('appended-category '+classes);
            this.elements.form.append(input);
        },
        removeForm:function(key){
            this.elements.form.find('input[name='+key+']').remove();
        },
        clearForm:function(){
            this.elements.form.find('.appended-category').remove();
            /*this.elements.searchAjaxUsers.lsSearchAjax("option", "params.city", null);
            this.elements.searchAjaxUsers.lsSearchAjax("option", "params.country", null);
            this.elements.searchAjaxUsers.lsSearchAjax("option", "params.region", null);*/
        }
    });
})(jQuery);

