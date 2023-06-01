<?php

namespace Give_Tipping;

use Give\Helpers\Hooks;
use Give_Tipping\Service\AddFeeToDonationAmount;

/**
 * Class ServiceProvider
 * @package GiveFeeRecovery
 * @since 1.9.1
 */
class ServiceProvider implements \Give\ServiceProviders\ServiceProvider {
	/**
	 * @inheritDoc
	 * @since 1.9.1
	 */
	public function register() {}

	/**
	 * @inheritDoc
	 * @since 1.9.1
	 */
	public function boot() {
		Hooks::addFilter( 'give_donation_total', AddFeeToDonationAmount::class );
	}
}
