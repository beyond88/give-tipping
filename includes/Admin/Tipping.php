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

        // Override Give Email tags.
		add_action( 'give_add_email_tags', array( $this, 'email_tags' ), 99999999 );

        // Modify amount tag for Preview when Give core >= 2.0 .
		add_filter( 'give_email_tag_amount', array( $this, 'preview_amount_tag' ), PHP_INT_MAX, 2 );

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
            /* #give-donation-overview .inside .column-container div:nth-child(3) p:nth-child(4){
                display: none; 
            } */
        </style>
        <?php
    }

	/**
	 * Checks if the Recurring Addon is active or not.
	 *
	 * @since 1.7
	 *
	 * @return boolean
	 */
	public function is_recurring_active() {
		return defined( 'GIVE_RECURRING_VERSION' );
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

		if( $donation_amount == 0 ) {
			$donation_amount = $total_donation;
		}

		$subscription_id = give_get_meta( $payment_id, 'subscription_id', true );

		if ( ! empty( $subscription_id ) && $this->is_recurring_active() ) {

			$subscription      = new \Give_Subscription( $subscription_id );
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
        if ( isset($donation_amount) || isset($tip_amount) ) {
            ?>
            <p style="display:block;">
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

					echo "
						<style>
							#give-donation-overview .inside .column-container div:nth-child(3) p:nth-child(4){
								display: none; 
							}
						</style>
					";
                }

                // Display Donation fee if set.
                if (isset($give_fee_amount) && give_maybe_sanitize_amount($give_fee_amount) > 0) {
                    echo ' ' . sprintf(
                            __('+ %s for fees', 'give-tipping'),
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

						echo "
							<style>
								#give-donation-overview .inside .column-container div:nth-child(3) p:nth-child(4){
									display: none; 
								}
							</style>
						";
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

	/**
	 * Override Give Email tags
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function email_tags() {

		// Remove amount email tag.
		give_remove_email_tag( 'amount' );

		// Add amount email tag with custom function.
		give_add_email_tag(
			'amount', __( 'The total donation amount with currency sign.', 'give-tipping' ), array(
				$this,
				'give_fee_email_tag_amount',
			)
		);
	}

	/**
	 * Add Custom call back function for {amount} tag.
	 *
	 * @since  1.1.0
	 *
	 * @param int|array $tag_args give payment id.
	 *
	 * @return string
	 */
	function give_fee_email_tag_amount( $tag_args ) {

		// Backward compatibility.
		$payment_id = $tag_args;
		if ( isset( $tag_args ) && is_array( $tag_args ) ) {
			$payment_id = $tag_args['payment_id'];
		}

		// Get Fee Recovery amount string.
		$give_amount = $this->fee_amount_string( $payment_id );

		return html_entity_decode( $give_amount, ENT_COMPAT, 'UTF-8' );

	}

    /**
	 * Get Fee Recovery amount string.
	 *
     * @param int $donation_id Donation ID.
     *
	 * @since 1.3.7
	 *
	 * @return string
	 */
	function fee_amount_string( $donation_id ) {

		// Define required variables.
		$currency_code   = give_get_payment_currency_code( $donation_id );
		$donation_total  = give_donation_amount( $donation_id );
		$fee_amount      = give_get_meta( $donation_id, '_give_fee_amount', true );
		$donation_amount = give_get_meta( $donation_id, '_give_fee_donation_amount', true );
        $tip_amount      = give_get_meta( $donation_id, '_give_tip_amount', true );

		// Default Donation Amount Output.
		$final_donation_amount = give_currency_filter(
			give_fee_format_amount( $donation_total,
				array(
					'donation_id' => $donation_id,
					'currency'    => $currency_code,
				)
			),
			array(
				'currency_code' => $currency_code,
			)
		);

		if ( ! empty( $fee_amount ) && ! empty( $tip_amount ) ) {

			$donation_amount = ($donation_amount - $tip_amount); 

			$donation_amount =
				give_currency_filter(
					give_fee_format_amount(
						$donation_amount,
						array(
							'donation_id' => $donation_id,
							'currency'    => $currency_code,
						)
					), array(
						'currency_code' => $currency_code,
					)
				);

			// Format fees amount.
			$fee_amount =
				give_currency_filter(
					give_fee_format_amount(
						$fee_amount,
						array(
							'donation_id' => $donation_id,
							'currency'    => $currency_code,
						)
					), array(
						'currency_code' => $currency_code,
					)
				);

			// Format fees amount.
			$tip_amount =
				give_currency_filter(
					give_fee_format_amount(
						$tip_amount,
						array(
							'donation_id' => $donation_id,
							'currency'    => $currency_code,
						)
					), array(
						'currency_code' => $currency_code,
					)
				);
				
			$final_donation_amount = sprintf(
				__( '%1$s (%2$s donation + %3$s for tips + %4$s for fees)', 'give-tipping' ),
				$final_donation_amount,
				$donation_amount,
				$tip_amount,
				$fee_amount
			);

		} else if( ! empty( $fee_amount ) && empty( $tip_amount ) ) {

			$donation_amount =
				give_currency_filter(
					give_fee_format_amount(
						$donation_amount,
						array(
							'donation_id' => $donation_id,
							'currency'    => $currency_code,
						)
					), array(
						'currency_code' => $currency_code,
					)
				);

			// Format fees amount.
			$fee_amount =
				give_currency_filter(
					give_fee_format_amount(
						$fee_amount,
						array(
							'donation_id' => $donation_id,
							'currency'    => $currency_code,
						)
					), array(
						'currency_code' => $currency_code,
					)
				);

			// Format tips amount.
			$tip_amount =
				give_currency_filter(
					give_fee_format_amount(
						$tip_amount,
						array(
							'donation_id' => $donation_id,
							'currency'    => $currency_code,
						)
					), array(
						'currency_code' => $currency_code,
					)
				);

			// Update final donation amount with fees breakdown.
			$final_donation_amount = sprintf(
				__( '%1$s (%2$s donation + %3$s for fees)', 'give-fee-recovery' ),
				$final_donation_amount,
				$donation_amount,
				$fee_amount
			);

		} else if( empty( $fee_amount ) && ! empty( $tip_amount ) ) {

			$donation_amount = ($donation_total - $tip_amount);

			$donation_amount =
				give_currency_filter(
					give_fee_format_amount(
						$donation_amount,
						array(
							'donation_id' => $donation_id,
							'currency'    => $currency_code,
						)
					), array(
						'currency_code' => $currency_code,
					)
				);

			// Format tips amount.
			$tip_amount =
				give_currency_filter(
					give_fee_format_amount(
						$tip_amount,
						array(
							'donation_id' => $donation_id,
							'currency'    => $currency_code,
						)
					), array(
						'currency_code' => $currency_code,
					)
				);

			// Update final donation amount with fees breakdown.
			$final_donation_amount = sprintf(
				__( '%1$s (%2$s donation + %3$s for tips)', 'give-fee-recovery' ),
				$final_donation_amount,
				$donation_amount,
				$tip_amount
			);

		} else {
			// Default Donation Amount Output.
			$final_donation_amount = give_currency_filter(
				give_fee_format_amount( $donation_total,
					array(
						'donation_id' => $donation_id,
						'currency'    => $currency_code,
					)
				),
				array(
					'currency_code' => $currency_code,
				)
			);
		}

		// Output final donation amount information with fees breakdown, if fees added.
		return $final_donation_amount;
	}

	/**
	 * Modify {amount} tag for the Preview Purpose in Give 2.0
	 *
	 * @since  1.1.0
	 * @update 1.3.7
	 *
	 * @access public
	 *
	 * @param string $amount
	 * @param array  $tag_args
	 *
	 * @return string
	 */
	public function preview_amount_tag( $amount, $tag_args ) {

		if ( isset( $tag_args['payment_id'] ) && ! empty( $tag_args['payment_id'] ) ) {
			$payment_id = $tag_args['payment_id'];

			$give_amount = $this->fee_amount_string( $payment_id );

			$amount = html_entity_decode( $give_amount, ENT_COMPAT, 'UTF-8' );
		}

		/**
		 * Filter the {amount} email template tag output.
		 *
		 * @since 1.3.6
		 *
		 * @param string $amount
		 * @param array  $tag_args
		 */
		$amount = apply_filters( 'give_fee_recovery_email_tag_amount', $amount, $tag_args );

		return $amount;

	}

}