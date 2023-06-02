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
		add_filter( 'give_form_level_output', [ $this, 'output' ], PHP_INT_MAX, 2);
		add_action( 'give_insert_payment', [ $this, 'insert_payment' ], PHP_INT_MAX, 2 ); // Add Fee on meta at the time of Donation payment.
		add_action( 'give_new_receipt', [ $this, 'add_receipt_items' ], PHP_INT_MAX, 2 ); // Add actual Tip Amount on Payment receipt.
    }

	/**
	 * Display tip amount on donation form
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function output( $output, $form_id ) {

		$prices = apply_filters( 'give_form_variable_prices', give_get_variable_prices( $form_id ), $form_id );
		$default_price = Helpers::get_default_price( $form_id );

		$settings = Helpers::get_settings();
		$tip_type = '';
		$tipping_amount = '';
		if( isset( $settings ) ) {
			$tip_type = $settings['tipping_type'];
			$tipping_amount = $settings['give_tipping_amount'];
			
			$output .= '<label class="give-label give-tip-amount-label" for="give-tip-amount">'.__('Tip amount', 'give-tipping').'</label>';
			$output .= '<ul id="give-donation-tip-level-button-wrap" class="give-donation-levels-wrap give-list-inline">';

			$is_default = false;

			foreach ( $tipping_amount as $key => $amount ) {
				if( $key == 1 ) {
					$is_default = true; 
				}

				if( $tip_type == 'percentage' ) {
					// $level_text	= $amount.'%';

					$level_text = Helpers::convert_percentage_into_amount($default_price, $amount);
					// $amount = $level_text;
					$level_text = give_currency_filter( give_format_amount( $level_text, [ 'sanitize' => false ] ), [ 'currency_code' => give_get_currency( $form_id ) ] );
				} else {
					$level_text = give_currency_filter( give_format_amount( $amount, [ 'sanitize' => false ] ), [ 'currency_code' => give_get_currency( $form_id ) ] );
				}
				
				$level_classes = apply_filters( 'give_form_level_classes', 'give-tipping-list-item give-btn give-btn-level-' . $key . ' ' . ( $is_default ? 'give-tip-default-level' : '' ), $form_id, $amount );

				$formatted_amount = give_format_amount(
					$amount,
					[
						'sanitize' => false,
						'currency' => give_get_currency( $form_id ),
					]
				);

				$output .= sprintf(
						'<li><button type="button" data-price-id="%1$s" class="%2$s" value="%3$s" data-default="%4$s" data-currency="%6$s">%5$s</button></li>',
						esc_attr($key),
						esc_attr($level_classes),
						esc_attr($formatted_amount),
						$is_default ? 1 : 0,
						esc_html($level_text),
						give_currency_symbol(give_get_currency( $form_id ))
					);
					$is_default = false;
			}

			$output .= '</ul>';

			$output .= '<label for="give_tip_mode_checkbox" class="give_tip-message-label" style="font-weight:normal; cursor: pointer;">
						<input name="give_tip_mode_checkbox" type="checkbox" id="give_tip_mode_checkbox" class="give_tip_mode_checkbox" value="1">
						<span class="give-tip-message-label-text">'.__('I\'d like to give some tips to the platform to support their cause to be a 100% free platform to help more people xxxxx relies on tips from people like you to continue operating as a completely 100% free platform for our fundraisers.', 'give-tipping').'</span>
					</label>';
			$output .= '<input type="hidden" name="give-tip-mode" class="give-tip-mode" id="give-tip-mode" value="'.esc_attr($tip_type).'"/>';
			$output .= '<input type="hidden" name="give-tip-amount" class="give-tip-amount" id="give-tip-amount" value=""/>';
		}

		return apply_filters( 'give_recurring_admin_defined_explanation_output', $output );
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
    public function insert_payment( $payment_id ) {
		
		$checked = $_POST['give_tip_mode_checkbox'];
		if( isset( $checked ) && $checked == 1 ) {
			
			// Get Fee amount.
			$tip_type = isset($_POST['give-tip-mode']) ? sanitize_text_field(
				give_clean($_POST['give-tip-mode'])
			) : 'fixed';
			$tip_amount = isset($_POST['give-tip-amount']) ? give_sanitize_amount_for_db(
				give_clean($_POST['give-tip-amount'])
			) : 0;

			// Store total fee amount.
			give_update_payment_meta($payment_id, '_give_tip_type', $tip_type);
			give_update_payment_meta($payment_id, '_give_tip_amount', $tip_amount);
		}
        
    }

	/**
	 * Add fee related line items to donation receipt.
	 *
	 * @param DonationReceipt $receipt
	 * @since 1.7.9
	 */
	public function add_receipt_items( $receipt ) {
		$updateDonationReceipt = new UpdateDonationReceipt( $receipt );
		$updateDonationReceipt->apply();
	}
}