(function($) {
    'use strict';

    /************************
     * 
     * Tip amount selection
     * 
     ************************/
    $(".give-tipping-list-item").click(function() {
        $(".give-tipping-list-item").removeClass("give-default-level");
        $(this).addClass("give-default-level");
    });

})(jQuery);