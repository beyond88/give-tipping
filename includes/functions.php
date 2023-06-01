<?php
/**
 * Display tip amount in donation confirmation page
 * 
 * @param array, integer, integer
 * @return array
 */
function gt_payment_receipt( $args, $donation_id, $form_id ) {

    // Get the donation currency.
    $payment_currency = give_get_payment_currency_code( $donation_id );

    $tip_amount = give_fee_format_amount( give_maybe_sanitize_amount( give_get_meta( $donation_id, '_give_tip_amount', true ) ),
        array(
            'donation_id' => $donation_id,
            'currency'    => $payment_currency,
        )
	);

    $donation_amount = give_fee_format_amount( give_maybe_sanitize_amount( give_get_meta( $donation_id, '_give_fee_donation_amount', true ) ),
        array(
            'donation_id' => $donation_id,
            'currency'    => $payment_currency,
        )
    );

    if ( isset( $tip_amount ) && give_maybe_sanitize_amount( $tip_amount ) > 0 ) {

        $donation_amount = $donation_amount - $tip_amount; 
        $donation_amount = give_currency_filter( $donation_amount,
                            array(
                                'currency_code' => $payment_currency,
                                'form_id'       => $form_id,
                            )
                        );

        $args['donation_total_with_fee']['value'] = $donation_amount;

        
        // Add new item to the donation receipt.
        $row_1 = array(
            'name'    => __( 'Tip Amount', 'give-tipping' ),
            'value'   => give_currency_filter( $tip_amount,
                array(
                    'currency_code' => $payment_currency,
                    'form_id'       => $form_id,
                )
            ),
            'display' => true,// true or false | whether you need to display the new item in donation receipt or not.
        );

        $args = give_fee_recovery_array_insert_before( 'total_donation', $args, 'donation_tip_amount', $row_1 );
    }

    return $args;

}

/**
 * Donation receipt hook
 * 
 * @param array|integer|integer
 * @return array
 * @package give-tipping
 */
add_action( 'give_donation_receipt_args', 'gt_payment_receipt', PHP_INT_MAX, 3 );