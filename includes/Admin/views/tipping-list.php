<div class="wrap">
    <h1 id="give-subscription-list-h1" class="wp-heading-inline">
        <?php echo __('Tipping','give-tipping'); ?>
    </h1>
	<?php if( ! isset( $listing ) ) { return; } ?>
	<?php $listing->prepare_items(); ?>
	<form id="give-payments-advanced-filter" method="get" action="<?php echo admin_url( 'edit.php?post_type=give_forms&page=give-tipping' ); ?>">
		<input type="hidden" name="post_type" value="give_forms" />
		<input type="hidden" name="page" value="give-tipping" />
		<?php $listing->advanced_filters(); ?>
	</form>

	<form id="give-payments-filter" method="get" action="<?php echo admin_url( 'edit.php?post_type=give_forms&page=give-tipping' ); ?>">
		<input type="hidden" name="post_type" value="give_forms" />
		<input type="hidden" name="page" value="give-tipping" />
		<?php $listing->display(); ?>
	</form>
</div>