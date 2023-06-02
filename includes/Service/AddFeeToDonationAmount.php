<?php

namespace Give_Tipping\Service;

use GiveFeeRecovery\Helpers\Form\Form;
use GiveFeeRecovery\Repositories\Settings;

class AddFeeToDonationAmount {
	/**
	 * Add calculated fee and update total.
	 *
	 * @since 1.9.1
	 *
	 * @param  string  $donationAmount  Donation total amount.
	 *
	 * @return string
	 */
	public function __invoke( $donationAmount ) {
		
		$tipEnable = absint( $_POST['give_tip_enable_checkbox'] );
		
		if( $tipEnable == 0 ){
			return $donationAmount;
		}

		$formId = isset( $_POST['give-form-id'] ) ?
			absint( $_POST['give-form-id'] ) :
			absint( $_POST['give_form_id'] );
		$giveAmount = isset( $_POST['give-amount'] ) ? absint( $_POST['give-amount'] ) : absint( $_POST['give_amount'] );


		$tipType = isset( $_POST['give-tip-mode'] ) ?
			give_clean( $_POST['give-tip-mode'] ) : '';

		$tipAmount = isset( $_POST['give-tip-amount'] ) ?
			give_clean( $_POST['give-tip-amount'] ) : 0;
		
		if( ! empty( $giveAmount ) ){
			$donationAmount = $giveAmount;
		}

		if( $tipType == 'percentage' ) {
			$tipAmount = $this->calculateTipAmount( $donationAmount, $tipAmount );
		}

		if ( ! Form::canRecoverFee( $formId ) ) {
			return $donationAmount + $tipAmount;
		}

		$donationAmount = $donationAmount + $tipAmount;

		$form_currency      = give_get_currency( $formId );
		$selectedPaymentMethod = isset( $_POST['payment-mode'] ) ?
		give_clean( $_POST['payment-mode'] ) :
		give_clean( $_POST['give_payment_mode'] );

		$feeConfig          = Settings::getSettingsForFeeCalculation( $formId, $selectedPaymentMethod );
		if ( $feeConfig && $this->hasDonorPermissionToAddFee( $formId ) ) {
			return CalculateNetDonationAmount::get( $donationAmount, $form_currency, $feeConfig );
		}

		return $donationAmount;

	}

	/**
	 * @since 1.0.0
	 *
	 * @param  int  $formId
	 *
	 * @return bool
	 */
	private function calculateTipAmount( $donationAmount, $tipAmount ) {
		return ($donationAmount * $tipAmount)/100;
	}


	/**
	 * @since 1.9.1
	 *
	 * @param  int  $formId
	 *
	 * @return bool
	 */
	private function hasDonorPermissionToAddFee( $formId ) {
		$hasDonorGrant = isset( $_POST['give-fee-mode-enable'] ) ?
			filter_var( $_POST['give-fee-mode-enable'], FILTER_VALIDATE_BOOLEAN ) :
			false;

		if ( $hasDonorGrant ) {
			return $hasDonorGrant;
		}

		$donorFeeOptInType = '';

		switch ( Form::getFeeConfigType( $formId ) ) {
			case 'global':
				$donorFeeOptInType = give_get_option( 'give_fee_mode' );
				break;

			case 'form':
				$donorFeeOptInType = give_get_meta( $formId, '_form_give_fee_mode', true );
				break;
		}

		return give_is_setting_enabled( $donorFeeOptInType, 'forced_opt_in' );
	}

}
