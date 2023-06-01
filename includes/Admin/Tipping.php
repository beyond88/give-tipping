<?php 
namespace Give_Tipping\Admin;
use Give_Tipping\Helpers;

class Tipping {

    /**
     * Initialize the class
     */
    public function __construct() {

        add_action( 'admin_footer', [ $this, 'remove_similar_messsage'] );
		add_action( 'give_donation_details_thead_after', [ $this, 'donation_detail' ], PHP_INT_MAX, 1 );
        // Hook update donation payment.
		add_action( 'give_view_donation_details_totals_after', [ $this, 'show_fee_order_detail' ], 10, 1 );

    }

    /**
     * Remove similar message
     * 
     * @param none
     * @return void
     * 
     */
    public function remove_similar_messsage() {
        ?>
        <style>
            #give-donation-overview .inside .column-container div:nth-child(3) p:nth-child(4){
                display: none; 
            }
        </style>
        <?php
    }
    
    /**
     * Display donation details with tips
     * 
     * @param integer
     * @return void
     * 
     */
    public function donation_detail( $payment_id ) {
        $payment_currency = give_get_payment_currency_code( $payment_id );
		$number_decimals  = give_get_price_decimals( $payment_currency );

		// Get total Donation amount.
		$total_donation = give_fee_format_amount(
			give_maybe_sanitize_amount( give_get_meta( $payment_id, '_give_payment_total', true ),
				array(
					'number_decimals' => $number_decimals,
					'currency'        => $payment_currency,
				)
			),
			array(
				'donation_id' => $payment_id,
				'currency'    => $payment_currency,
			)
		);
		// Get donation amount.
		$donation_amount = give_fee_format_amount(
			give_maybe_sanitize_amount(
				give_get_meta( $payment_id, '_give_fee_donation_amount', true ),
				array(
					'number_decimals' => $number_decimals,
					'currency'        => $payment_currency,
				)
			),
			array(
				'donation_id' => $payment_id,
				'currency'    => $payment_currency,
			)
		);

		$subscription_id = give_get_meta( $payment_id, 'subscription_id', true );

		if ( ! empty( $subscription_id ) && $this->is_recurring_active() ) {

			$subscription      = new Give_Subscription( $subscription_id );
			$parent_payment_id = $subscription->get_original_payment_id();
			$donation_amount = give_fee_format_amount(
                give_maybe_sanitize_amount(give_get_meta($payment_id, '_give_fee_donation_amount', true),
                    array(
                        'number_decimals' => $number_decimals,
                        'currency' => $payment_currency,
                    )
                ),
                array(
                    'donation_id' => $payment_id,
                    'currency' => $payment_currency,
                )
            );

			$give_fee_amount = give_fee_format_amount(
                give_maybe_sanitize_amount(give_get_meta($payment_id, '_give_fee_amount', true),
                    array(
                        'number_decimals' => $number_decimals,
                        'currency' => $payment_currency,
                    )
                ),
				array(
					'donation_id' => $payment_id,
					'currency'    => $payment_currency,
				)
			);

		} else {
            // Get Fee amount.
            $give_fee_amount = give_fee_format_amount(
                give_maybe_sanitize_amount(give_get_meta($payment_id, '_give_fee_amount', true),
                    array(
                        'number_decimals' => $number_decimals,
                        'currency' => $payment_currency,
                    )
                ),
                array(
                    'donation_id' => $payment_id,
                    'currency' => $payment_currency,
                )
            );
        }

        $tip_amount = give_fee_format_amount(
            give_maybe_sanitize_amount(give_get_meta($payment_id, '_give_tip_amount', true),
                array(
                    'number_decimals' => $number_decimals,
                    'currency' => $payment_currency,
                )
            ),
            array(
                'donation_id' => $payment_id,
                'currency' => $payment_currency,
            )
        );

        $donation_amount = $donation_amount - $tip_amount; 

        // Display Donation amount if total donation and donation amount not same.
        if (isset($total_donation, $donation_amount) && $donation_amount !== $total_donation && $donation_amount > 0) {
            ?>
            <p>
                <strong><?php
                    esc_html_e('Donation Amount:', 'give-fee-recovery'); ?></strong><br>
                <?php
                echo esc_html(
                    give_currency_filter(
                        $donation_amount,
                        array(
                            'currency_code' => $payment_currency,
                        )
                    )
                );

                // Display Donation fee if set.
                if (isset($tip_amount) && give_maybe_sanitize_amount($tip_amount) > 0) {
                    echo ' ' . sprintf(
                            __('+ %s for tips', 'give-fee-recovery'),
                            esc_html(
                                give_currency_filter(
                                    give_fee_format_amount(
                                        $tip_amount,
                                        array(
                                            'currency' => $payment_currency,
                                            'donation_id' => $payment_id,
                                        )
                                    ),
                                    array(
                                        'currency_code' => $payment_currency,
                                    )
                                )
                            )
                        );
                }

                // Display Donation fee if set.
                if (isset($give_fee_amount) && give_maybe_sanitize_amount($give_fee_amount) > 0) {
                    echo ' ' . sprintf(
                            __('+ %s for fees', 'give-fee-recovery'),
                            esc_html(
                                give_currency_filter(
                                    give_fee_format_amount(
                                        $give_fee_amount,
                                        array(
                                            'currency' => $payment_currency,
                                            'donation_id' => $payment_id,
                                        )
                                    ),
                                    array(
                                        'currency_code' => $payment_currency,
                                    )
                                )
                            )
                        );
                }
                ?>
            </p>
            <?php
        }
    }

    /**
	 * Fires in order details page, after the sidebar update-donation metabox.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param int $payment_id Payment id.
	 */
	public function show_fee_order_detail( $payment_id ) {
		$payment         = new \Give_Payment( $payment_id );
		$subscription_id = give_get_meta( $payment_id, 'subscription_id', true );

		if ( ! empty( $subscription_id ) && $this->is_recurring_active() ) {
			$subscription_id   = give_get_meta( $payment_id, 'subscription_id', true );
			$subscription      = new \Give_Subscription( $subscription_id );
			$parent_payment_id = $subscription->get_original_payment_id();
			$give_tip_amount   = give_fee_format_amount(
				give_get_meta( $parent_payment_id, '_give_tip_amount', true ),
				array(
					'donation_id' => $payment_id,
				)
			);
		} else {
			$give_tip_amount = give_get_meta( $payment_id, '_give_tip_amount', true );
		}

		$give_tip_amount = ! empty( $give_tip_amount ) ? give_fee_number_format( $give_tip_amount, true, $payment_id ) : 0;
		?>
		<div class="give-order-payment give-admin-box-inside">
			<p>
				<label for="give-payment-fee-amount" class="strong"><?php esc_html_e( 'Tip Amount:', 'give-fee-recovery' ); ?></label>&nbsp;
				<?php echo give_currency_symbol( $payment->currency ); ?>
				<input
						id="give-tip-amount"
						placeholder="0.00"
						name="give-tip-amount"
						type="number"
						class="small-text give-price-field"
						value="<?php echo esc_attr( give_format_decimal( $give_tip_amount ) ); ?>"
				/>
			</p>
		</div>
		<?php
	}

}