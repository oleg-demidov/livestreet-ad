
jQuery(document).ready(function($){
    
    
    /*
     * category-tabs
     */
    
    var flCategoryTabs = $('.fl-category-tabs');
    if(flCategoryTabs.length){
        flCategoryTabs.categoryTabs({
            height:800,
            
        });
    }
    
    $('.js-topic-category').lsTabs({
        tabbeforeactivate:function(e, data){
            data.element.find('.ls-tab-inner').removeClass('ls-button--danger');
        }
    });
    
    $('.js-form-validate-ad').parsley().on('field:validate', function(inst){
        if( !inst.isValid({group: 'category', force: true}) ){
            let el = $('.ls-tab.category-tab .ls-tab-inner');
            $('html, body').animate({ scrollTop: (el.offset().top - 50) }, 500);
            el.addClass('ls-button--danger');
        }
        
    })
    
});