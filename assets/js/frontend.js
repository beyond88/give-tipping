(function($) {
    'use strict';

    /************************
     * 
     * Tip amount selection
     * 
     ************************/
    $(".give-tipping-list-item").click(function() {
        $(".give-tipping-list-item").removeClass("give-tip-default-level");
        $(this).addClass("give-tip-default-level");
    });

})(jQuery);