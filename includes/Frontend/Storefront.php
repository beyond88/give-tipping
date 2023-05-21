<?php 
namespace Give_Tipping\Frontend;
use Give\Receipt\DonationReceipt;
use GiveFeeRecovery\Receipt\UpdateDonationReceipt;
use GiveFeeRecovery\Repositories\Settings;
use Give_Tipping\Helpers; 

class Storefront {

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
        // add_action( 'give_pre_form_output', array( $this, 'pre_form_output' ), 0, 1 ); // Display Give Fee label.
		// add_action( 'give_insert_payment', array( $this, 'insert_payment' ), 10, 2 ); // Add Fee on meta at the time of Donation payment.
		// add_action( 'give_donation_receipt_args', array( $this, 'payment_receipt' ), 10, 3 ); // Add actual Donation total and Recovery Fee on Payment receipt.
		// add_action( 'give_new_receipt', array( $this, 'addReceiptItems' ), 10, 2 ); // Add actual Donation total and Recovery Fee on Payment receipt.
		add_filter( 'give_form_level_output', [ $this, 'output' ], PHP_INT_MAX, 2);
		
    }

	public function output($output, $form_id) {

		$settings = Helpers::get_settings();
		$tip_type = '';
		$tipping_amount = '';
		if( isset( $settings ) ) {
			$tip_type = $settings['tipping_type'];
			$tipping_amount = $settings['give_tipping_amount'];
			
			$output .= '<ul id="give-donation-tip-level-button-wrap" class="give-donation-levels-wrap give-list-inline">';

			$is_default = false;

			foreach ( $tipping_amount as $key => $amount ) {
				if($key == 1) {
					$is_default = true; 
				}

				$level_text    = $amount;
				$level_classes = apply_filters( 'give_form_level_classes', 'give-donation-level-btn give-btn give-btn-level-' . $key . ' ' . ( $is_default ? 'give-default-level' : '' ), $form_id, $amount );

				$formatted_amount = give_format_amount(
					$amount,
					[
						'sanitize' => false,
						'currency' => give_get_currency( $form_id ),
					]
				);

				$output .= sprintf(
						'<li><button type="button" data-price-id="%1$s" class="%2$s" value="%3$s" data-default="%4$s">%5$s</button></li>',
						esc_attr($key),
						esc_attr($level_classes),
						esc_attr($formatted_amount),
						$is_default ? 1 : 0,
						esc_html($level_text)
					);
			}

			$output .= '</ul>';
		}

		return apply_filters( 'give_recurring_admin_defined_explanation_output', $output );
	}

    /**
	 * Display tip amount
	 *
	 * @since    1.0.0
	 * @access   public
	 */
    public function pre_form_output( $form_id ) {
        echo "Hello World";
    }

	/**
     * Store fee amount of total donation into post meta.
     *
     * @unreleased remove renewal conditional because it will never get called since there is a separate action for that
     * @since  1.0.0
     * @access public
     *
     * @param  int  $payment_id  Newly created payment ID.
     */
    public function insert_payment($payment_id)
    {
        // $fee_mode_enabled = isset($_POST['give-fee-mode-enable']) ? filter_var(
        //     $_POST['give-fee-mode-enable'],
        //     FILTER_VALIDATE_BOOLEAN
        // ) : false;
        // $give_fee_status = !empty($_POST['give-fee-status']) ? $_POST['give-fee-status'] : 'disabled';
        // $fee_status = '';

        // // Set Give Fee donation status based on enabled/disabled.
        // if ('enabled' === $give_fee_status && true === $fee_mode_enabled) {
        //     $fee_status = 'accepted';
        // } elseif ('enabled' === $give_fee_status && false === $fee_mode_enabled) {
        //     $fee_status = 'rejected';
        // } elseif ('disabled' === $give_fee_status) {
        //     $fee_status = 'disabled';
        // }

        // // If donor did not opt-on only store the status.
        // if (!$fee_mode_enabled) {
        //     // Update Give Fee Status.
        //     give_update_payment_meta($payment_id, '_give_fee_status', $fee_status);

        //     return;
        // }

        // // Get payment data by payment ID.
        // $payment_data = new Give_Payment($payment_id);
        // $total_donation = $payment_data->total;

        // // Get Fee amount.
        // $fee_amount = isset($_POST['give-fee-amount']) ? give_sanitize_amount_for_db(
        //     give_clean($_POST['give-fee-amount'])
        // ) : 0;

        // // Get actual donation amount.
        // $donation_amount = $total_donation - $fee_amount;
        // $donation_amount = give_sanitize_amount_for_db($donation_amount);

        // // Store Donation amount.
        // give_update_payment_meta($payment_id, '_give_fee_donation_amount', $donation_amount);

        // // Store total fee amount.
        // give_update_payment_meta($payment_id, '_give_fee_amount', $fee_amount);
    }

	/**
	 * Fires in the payment receipt short-code, after the receipt last item.
	 *
	 * Allows you to add new <td> elements after the receipt last item.
	 *
	 * @since  1.1.2
	 * @access public
	 *
	 * @param array $args
	 * @param int   $donation_id
	 * @param int   $form_id
	 *
	 * @return array
	 */
	public function payment_receipt( $args, $donation_id, $form_id ) {

		// // Get the donation currency.
		// $payment_currency = give_get_payment_currency_code( $donation_id );

		// // Get total Donation amount.
		// $total_donation = give_fee_format_amount( give_maybe_sanitize_amount( give_get_meta( $donation_id, '_give_payment_total', true ) ),
		// 	array(
		// 		'donation_id' => $donation_id,
		// 		'currency'    => $payment_currency,
		// 	)
		// );

		// // Get donation amount.
		// $donation_amount = give_fee_format_amount( give_maybe_sanitize_amount( give_get_meta( $donation_id, '_give_fee_donation_amount', true ) ),
		// 	array(
		// 		'donation_id' => $donation_id,
		// 		'currency'    => $payment_currency,
		// 	)
		// );

		// // Get Fee amount.
		// $fee_amount = give_fee_format_amount( give_maybe_sanitize_amount( give_get_meta( $donation_id, '_give_fee_amount', true ) ),
		// 	array(
		// 		'donation_id' => $donation_id,
		// 		'currency'    => $payment_currency,
		// 	)
		// );

		// if ( isset( $fee_amount ) && give_maybe_sanitize_amount( $fee_amount ) > 0 ) {
		// 	// Add new item to the donation receipt.
		// 	$row_2 = array(
		// 		'name'    => __( 'Donation Fee', 'give-fee-recovery' ),
		// 		'value'   => give_currency_filter( $fee_amount,
		// 			array(
		// 				'currency_code' => $payment_currency,
		// 				'form_id'       => $form_id,
		// 			)
		// 		),
		// 		'display' => true,// true or false | whether you need to display the new item in donation receipt or not.
		// 	);

		// 	$args = give_fee_recovery_array_insert_before( 'total_donation', $args, 'donation_fee_total', $row_2 );
		// }

		// if ( isset( $total_donation )
		//      && isset( $donation_amount )
		//      && ( $donation_amount !== $total_donation )
		//      && give_maybe_sanitize_amount( $donation_amount ) > 0
		// ) {
		// 	// Add new item to the donation receipt.
		// 	$row_1 = array(
		// 		'name'    => __( 'Donation Amount1', 'give-fee-recovery' ),
		// 		'value'   => give_currency_filter( $donation_amount,
		// 			array(
		// 				'currency_code' => $payment_currency,
		// 				'form_id'       => $form_id,
		// 			)
		// 		),
		// 		'display' => true,// true or false | whether you need to display the new item in donation receipt or not.
		// 	);

		// 	$args = give_fee_recovery_array_insert_before( 'donation_fee_total', $args, 'donation_total_with_fee', $row_1 );
		// }

		return $args;

	}

	/**
	 * Add fee related line items to donation receipt.
	 *
	 * @param DonationReceipt $receipt
	 * @since 1.7.9
	 */
	public function addReceiptItems( $receipt ){
		// $updateDonationReceipt = new UpdateDonationReceipt( $receipt );
		// $updateDonationReceipt->apply();
	}
}