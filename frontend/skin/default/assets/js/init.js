
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
    
    $('.js-block-ad-tags').topicAdTags({
        text_field:".topic-ad-search-form-text input"
    });
    
    
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
                type: 'text',
                name: 'price_from',
                selector: '.js-field-price .field-diapazon-input-from'
            },
            {
                type: 'text',
                name: 'price_to',
                selector: '.js-field-price .field-diapazon-input-to'
            },
            {
                type: 'select',
                name: 'category',
                selector: '.js-field-category'
            },
            {
                type: 'text',
                name: 'category_url_full',
                selector: '.appended-category-url'
            },
            {
                type: 'select',
                name: 'categories[]',
                selector: '.appended-category-id'
            },
            
            {
                type: 'text',
                name: 'geo[country]',
                selector: '.js-field-geo-country'
            },
            {
                type: 'text',
                name: 'geo[region]',
                selector: '.js-field-geo-region'
            },
            {
                type: 'text',
                name: 'geo[city]',
                selector: '.js-field-geo-city'
            },
            {
                type: 'sort',
                name: 'sort_by',
                selector: '.js-search-sort-menu li'
            }
        ],
        afterupdate: function ( event, data ) {
            $( '.js-topic' ).lsTopic();
            paginationAjax(); 
            $('.js-category-ad-breadcrumbs').remove();
            $('.layout-content').prepend( data.response.breadcrumbs_html );
        }
    });
    
    //console.log(ls.registry.get('ymapsOptions'))
    
    /*
     * Ajax пагинация
     */
    function paginationAjax(){
        $('.js-pagination-topics-ad').on('click', function(e){
            $( '.js-search-ajax-ads' ).lsSearchAjax('update', {page:$(e.target).html()});
            return false;
        })
    }
    paginationAjax();
    
    $('.js-topic-ad-search-form-order').lsDropdown({
        selectable:true
    });
    /*
     * Topic ad
     */
    $('.fl-phone-hide-linkshow').phoneHide();
});