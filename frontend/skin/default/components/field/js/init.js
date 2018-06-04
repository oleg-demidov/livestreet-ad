$(document).ready(function ($) {
    $('.fl-market-modal').lsModal({
        aftershow:function(e){
            $(e.target).css('margin-top','50px');  
        }
    });
    $('.js-fl-market-modal-toggle').lsModalToggle();
    $('.js-fl-market-modal-toggle').flMarket();
    
});