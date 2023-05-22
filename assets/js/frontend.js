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

    var $body = $('body');

    $body
    .on('change', '.give_tip_mode_checkbox', function () {
        // Update donation total when document is loaded.
        var form = $(this).closest('form.give-form'),
            check_option = $('.give_fee_mode_checkbox').is(':checked'),
            gateway = form.find('input.give-gateway:radio:checked').val(),
            give_total = parseFloat(form.find('input[name="give-amount"]').val());
        
        var tip_type = $('.give-tip-mode').val(),
            tip_check_option = $(this).is(':checked');

        // This scenario is for "Edit Amount" or "Update Payment Method" stability.
        if (typeof gateway === 'undefined') {
            gateway = form.attr('data-gateway');
        }
        
        let tip_amount = 0;
        $(".give-tipping-list-item").each(function(index, item) {
            if( $(this).hasClass('give-tip-default-level') ){
                tip_amount = $(this).val();
                return tip_amount; 
            }
        });

        if(tip_check_option) {

            if( tip_type === "percentage" ) {
                tip_amount = parseFloat(percentCalculation(give_total, tip_amount));
            }
            var new_total_amount = give_total + tip_amount;
            window.Give_Fee_Recovery.give_fee_update(form, false, new_total_amount, gateway);
        } else {

            if( tip_type === "percentage" ) {
                let give_total = 0;
                $(".give-donation-level-btn").each(function(index, item) {
                    if( $(this).hasClass('give-default-level') ){
                        give_total = $(this).val();
                        return give_total; 
                    }
                });

                tip_amount = parseFloat(percentCalculation(give_total, tip_amount));
            }

            var calculated_total = parseFloat($('.give-final-total-amount').data('total'));
            var new_total_amount = calculated_total - tip_amount;
            window.Give_Fee_Recovery.give_fee_update(form, false, new_total_amount, gateway);
        }

    })
    .change();

    function percentCalculation(a, b){
        var c = (parseFloat(a)*parseFloat(b))/100;
        return parseFloat(c);
    }
    
})(jQuery);