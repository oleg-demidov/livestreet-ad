
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
    
    
});