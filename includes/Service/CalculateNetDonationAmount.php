<?php

namespace Give_Tipping\Service;

/**
 * Class CalculateNetDonationAmount
 *
 * @package Give_Tipping\Service
 * @since 1.9.1
 */
class CalculateNetDonationAmount {
	/**
	 * @since 1.9.1
	 *
	 * @since 1.9.2 Add maximum fee to donation amount only if
	 *             1. maximum fee exist
	 *             2. Less then nornal fee
	 *
	 * @return float
	 */
	public static function get( $donationAmount, $donationCurrency, $feeConfig ) {
		$maximumFeeAmount    = (float) $feeConfig['maximum_fee_amount'];
		$hasMaximumFeeAmount = (bool) $maximumFeeAmount;

		$fee = give_fee_calculate(
			$feeConfig['fee_percentage'],
			$feeConfig['additional_fee_amount'],
			$donationAmount,
			false
		);

		$fee = $hasMaximumFeeAmount && ( $fee > $maximumFeeAmount ) ? $maximumFeeAmount : $fee;

		$fee = ( new static() )->floatVal( $fee, $donationCurrency );

		return $donationAmount + $fee;
	}

	/**
	 * @since 1.9.1
	 *
	 * @param  string  $fee
	 * @param  string  $donationCurrency
	 *
	 * @return float
	 */
	private function floatVal( $fee, $donationCurrency ) {
		$decimals = give_get_price_decimals( $donationCurrency );

		// Get correct number of decimal based on currency is zero based or not.
		if ( 1 >= $decimals && ! give_is_zero_based_currency( $donationCurrency ) ) {
			$decimals = 2;
		}

		if ( give_is_zero_based_currency( $donationCurrency ) ) {
			$decimals = 0;
		}

		return (float) give_sanitize_amount(
			$fee,
			[
				'number_decimals' => $decimals,
				'currency'        => $donationCurrency,
			]
		);
	}
}
