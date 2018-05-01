
jQuery(document).ready(function($){
    
    
    /*
     * category-tabs
     */
    
    var flCategoryTabs = $('.fl-category-tabs');
    if(flCategoryTabs.length){
        flCategoryTabs.categoryTabs({
            checkboxchange: function(e,data){
                $(e.currentTarget).parsley().validate();
            }
        });
    }
    
    $('.js-topic-category').lsTabs();
    
    
    $('.js-form-validate-ad').topicAdForm();
    
    $('.js-category-tree').flFieldCategoryTree(); 
    
    
    
    
    /*
     * Ajax поиск обьявлений
     */
    
    $( '.js-search-ajax-ads' ).lsSearchAjax({
        urls: {
            search: aRouter[ls.registry.get('url_search_ad')] + 'ajax-search'
        },
        i18n: {
            title: ls.lang.get( 'plugin.ad.ad.search_form.count_results' )
        },
        selectors: {
            list: '.js-search-ad-results',
//            more: '.js-more-search',
            title: '@.js-search-ad-results-count',
            button :".js-search-ajax-button"
        },
        filters : [
            {
                type: 'text',
                name: 'text',
                selector: '.topic-ad-search-form-text input'
            },
            {
                type: 'select',
                name: 'category',
                selector: '.js-field-category'
            },
            {
                type: 'select',
                name: 'categories[]',
                selector: '.appended-category-id'
            },
            
            {
                type: 'select',
                name: 'geo[country]',
                selector: '.js-field-geo-country'
            },
            {
                type: 'select',
                name: 'geo[region]',
                selector: '.js-field-geo-region'
            },
            {
                type: 'select',
                name: 'geo[city]',
                selector: '.js-field-geo-city'
            }
        ],
        afterupdate: function ( event, data ) {
            $('.js-pagination-topics-ad').lsPaginationAjax();
            //data.context.getElement( 'more' ).lsMore( 'option', 'params.next_page', 2 );
        }
    });
    
    /*
     * Ajax пагинация
     */
    $('.js-pagination-topics-ad').lsPaginationAjax({
        pagechanged: function(data, pageNumber){ console.log(data, pageNumber);
            $( '.js-search-ajax-ads' ).lsSearchAjax('update', {page:pageNumber});
        }
    });
    
});