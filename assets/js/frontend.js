(function($) {
    'use strict';

    /************************
     * 
     * Click on amount and get selected
     * 
     ************************/
    $(".give-tipping-list-item").click(function() {
        $(".give-tipping-list-item").removeClass("give-tip-default-level");
        $(this).addClass("give-tip-default-level");
    });

    var $body = $('body');

    /************************
     * 
     * Checked and unchecked for giving tips
     * 
     ************************/
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
                    give_total = parseFloat($('.give-default-level').val());
                tip_amount = parseFloat(percentCalculation(give_total, tip_amount));
                console.log("Give total==>", give_total);
                console.log("Tip amount==>", tip_amount);
            }

            let calculated_total = parseFloat($('.give-final-total-amount').data('total'));
            let new_total_amount = calculated_total - tip_amount;
            if(new_total_amount < give_total ){
                new_total_amount = give_total;
            }
            window.Give_Fee_Recovery.give_fee_update(form, false, new_total_amount, gateway);
        }

    })
    .change();

    /************************
     * 
     * Click on amount and make changes
     * 
     ************************/
    $(".give-tipping-list-item").click(function() {
        
        // Update donation total when document is loaded.
        let tip_amount = $(this).val();
        var form = $(this).closest('form.give-form'),
        check_option = $('.give_fee_mode_checkbox').is(':checked'),
        gateway = form.find('input.give-gateway:radio:checked').val(),
        give_total = parseFloat(form.find('input[name="give-amount"]').val());

        var tip_type = $('.give-tip-mode').val(),
        tip_check_option = $('.give_tip_mode_checkbox').is(':checked');

        // This scenario is for "Edit Amount" or "Update Payment Method" stability.
        if (typeof gateway === 'undefined') {
            gateway = form.attr('data-gateway');
        }

        if(tip_check_option) {

            if( tip_type === "percentage" ) {
                tip_amount = parseFloat(percentCalculation(give_total, tip_amount));
            }
            var new_total_amount = give_total + tip_amount;
            window.Give_Fee_Recovery.give_fee_update(form, false, new_total_amount, gateway);

        } else {

            let give_total = 0;
            if( tip_type === "percentage" ) {
                    give_total = parseFloat($('.give-default-level').val());
                tip_amount = parseFloat(percentCalculation(give_total, tip_amount));
            }

            let calculated_total = parseFloat($('.give-final-total-amount').data('total'));
            let new_total_amount = calculated_total - tip_amount;
            if(new_total_amount < give_total ){
                new_total_amount = give_total;
            }
            window.Give_Fee_Recovery.give_fee_update(form, false, new_total_amount, gateway);
        }
    });

    function percentCalculation(a, b){
        var c = (parseFloat(a)*parseFloat(b))/100;
        return parseFloat(c);
    }
    
})(jQuery);