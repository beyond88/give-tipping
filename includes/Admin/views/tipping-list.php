<div class="wrap">
    <h1 id="give-subscription-list-h1" class="wp-heading-inline">
        <?php echo __('Tipping','give-tipping'); ?>
    </h1>

	<form class="" method="get">
		<?php
			if(! isset($listing)) { return; }
			$listing->process_bulk_action();
			$listing->prepare_items();
			$listing->search_box( __('Search tips', 'give-tipping'), $listing->searchColumn );
			$listing->views();
			$listing->display();
		?>
	</form>
</div>