
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
    
    $('.js-topic-category').lsTabs();
    
    $('.fl-category-tabs input').change(function(){
        $(this).parsley().validate();
    })
    
    $('.js-form-validate-ad').parsley().on('field:validate', function(inst){
        
        let tabs = ['category', 'form', 'contacts'];
        
        tabs.forEach(function(value, key){
            let el = $('.ls-tab.'+value+'-tab .ls-tab-inner'); 
        
            if( !(inst.isValid({group: value}) === true)){            
                el.addClass('ls-button--danger');
            }
            
            if( inst.isValid({group: value} === true)){     
                console.log(el)
                el.removeClass('ls-button--danger');
            }
        });         
        
    }); 
    
    
});