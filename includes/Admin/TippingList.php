<?php

namespace Give_Tipping\Admin;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Directory Listings Class to extend WP_List_Table
 */
if ( ! class_exists( 'TippingList' ) ) :
class TippingList extends \WP_List_Table {

    public $searchColumn = 'search_id';

    /**
    * Constructor, we override the parent to pass our own arguments
    * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
    */
    function __construct() {

        parent::__construct( array(
            'singular'  => 'singular_name',     //singular name of the listed records
            'plural'    => 'plural_name',    //plural name of the listed records
            'ajax'      => false 
 
        ) );
 
    }

    /**
     * Collect all tips
     *
     * @return void
     */
    private function query_tippings() {      
        global $wpdb;
        $tableName = $wpdb->prefix . 'give_donationmeta';
        $data = [];
        
        $sql = "SELECT * FROM {$tableName} WHERE {$tableName}.meta_key ='_give_tip_amount'";
        $data = $wpdb->get_results( $sql, ARRAY_A );
        return $data;
    }

    /**
    * Get a list of columns.
    *
    * @return array
    */
    public function get_columns() {

        return array(
            'cb' => '<input type="checkbox" />',
            'campaign' => wp_strip_all_tags( __( 'Campaign' ) ),
            'donation' => wp_strip_all_tags( __( 'Donation' ) ),
            'tip_amount' => wp_strip_all_tags( __( 'Tip Amount' ) ),
            'donor' => wp_strip_all_tags( __( 'Donor' ) ),
            'date' => wp_strip_all_tags( __( 'Date' ) ),
        );

    }

    /**
     * @param object $item
     */
    public function column_cb($item)
    {
        $id = $item['meta_id'];
        ?>
        <input type="checkbox" name="<?php isset($name) ? _e($name) : _e('delete_listing[]') ?>" value="<?php echo esc_attr( $id ); ?>"/>
        <?php
    }

    /**
    * Prepares the list of items for displaying.
    */
    public function prepare_items() {
        
        global $wpdb;  
        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();
        $hidden = $this->get_hidden_columns();
        $this->process_bulk_action();
        $data = $this->query_tippings();
        
        $totalitems = count($data);
        $user = get_current_user_id();
        $screen = get_current_screen();
        $option = $screen->get_option('per_page', 'option'); 
        $perpage = 20;
        $this->_column_headers = array($columns,$hidden,$sortable);
        if ( empty ( $per_page) || $per_page < 1 ) {
        
          $per_page = $screen->get_option( 'per_page', 'default' ); 
        }

        usort($data, function($a, $b){
            $orderby = ( !empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'meta_id'; //If no sort, default to title
            $order = ( !empty( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp( $a[$orderby], $b[$orderby] ); //Determine sort order
            return ( $order==='asc' ) ? $result : -$result; //Send final sort direction to usort
        });
        $totalpages = ceil($totalitems/$perpage); 
        $currentPage = $this->get_pagenum();
        
        $data = array_slice($data,(($currentPage-1)*$perpage),$perpage);
        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ) );
            
        $this->items = $data;

    }

    private function get_listing_url( $id ) {
        
        if( $id ) {
            $site_url = get_admin_url();
            $page_slug = '/edit.php?post_type=give_forms&page=give-payment-history&view=view-payment-details';
            $listing_id = '&id='.$id;
            $url = $site_url.$page_slug.$listing_id;
            return $url; 
        }

        return get_admin_url('admin.php');

    }

    /**
    * Generates content for a single row of the table.
    *
    * @param object $item The current item.
    * @param string $column_name The current column name.
    */
    protected function column_default( $item, $column_name ) {

        $donation_id = $item['donation_id'];
        $form_id = give_get_meta( $donation_id, '_give_payment_form_id', true ); 
        $name = give_get_meta( $donation_id, '_give_donor_billing_first_name', true ) .' '. give_get_meta( $donation_id, '_give_donor_billing_last_name', true );
        $payment_total = (float) give_get_meta( $donation_id, '_give_payment_total', true );
        $tip_total = (float) give_get_meta( $donation_id, '_give_tip_amount', true );
        $donation = 0;
        if( is_numeric( $payment_total ) && is_numeric( $tip_total ) ){
            $donation = $payment_total - $tip_total;
        }

        // $donation = give_get_meta( $donation_id, '_give_payment_total', true ) - give_get_meta( $donation_id, '_give_tip_amount', true );
        $donation = $this->get_amount_with_currency_symbol( $form_id, $donation );
        $tip_amount = $this->get_amount_with_currency_symbol( $form_id, give_get_meta( $donation_id, '_give_tip_amount', true ) );
        $date = give_get_meta( $donation_id, '_give_completed_date', true );

        switch ( $column_name ) {
            case 'campaign':
                    return '<strong><a href="'.esc_url($this->get_listing_url($donation_id)).'">'.esc_html( give_get_meta( $donation_id, '_give_payment_form_title', true ) ).'</a></strong>';
            case 'donation':
                return $donation;
            case 'tip_amount':
                return $tip_amount;
            case 'donor':
                return esc_html( $name );
            case 'date':
                return esc_html( $date );
            return 'Unknown';
        }
    }

    protected function get_user_name( $user_id ){
        $user = get_user_by( 'id', $user_id );
        return $user;
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        $sortable_columns = [
            'title' => [ 'title',true ],
            'content' => [ 'content',true ],
            'author' => [ 'author',true ],
            'date' => [ 'date',true ],
        ]; 
        return $sortable_columns;
    }

    /**
    * Setup Hidden columns and return them
    *
    * @return array
    */
    public function get_hidden_columns()
    {
        return array();
    }

	/**
	 * Retrieves the list of bulk actions available for this table.
	 *
	 * @return array
	 */
	// public function get_bulk_actions() {
	// 	$selected_status = $this->order_query_args['status'] ?? false;

	// 	if ( array( 'trash' ) === $selected_status ) {
	// 		$actions = array(
	// 			'untrash' => __( 'Restore', 'give-tipping' ),
	// 			'delete'  => __( 'Delete permanently', 'give-tipping' ),
	// 		);
	// 	} else {
	// 		$actions = array(
	// 			'trash'           => __( 'Move to Trash', 'give-tipping' ),
	// 		);
	// 	}

	// 	return $actions;
	// }

    /**
    * Generates custom table navigation to prevent conflicting nonces.
    *
    * @param string $which The location of the bulk actions: 'top' or 'bottom'.
    */
    protected function display_tablenav( $which ) {
    ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            <div class="alignleft actions bulkactions">
                <?php $this->bulk_actions( $which ); ?>
            </div>
            <?php
                $this->extra_tablenav( $which );
                $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
    <?php
    }

    /**
    * Generates content for a single row of the table.
    *
    * @param object $item The current item.
    */
    public function single_row( $item ) {
        echo '<tr>';
            $this->single_row_columns( $item );
        echo '</tr>';
    }

    /**
     * @return string|false
     */
    public function current_action()
    {
        return parent::current_action();
    }

    /**
     * Process Bulk Action
     *
     * @return void
     */
    public function process_bulk_action()
    {
        $doaction = $this->current_action();
        if ( $doaction ) {
            $comment_ids = $_REQUEST['delete_listing'];

            $approved   = 0;
            $unapproved = 0;
            $spammed    = 0;
            $unspammed  = 0;
            $trashed    = 0;
            $untrashed  = 0;
            $deleted    = 0;
            $redirect_to = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );

            wp_safe_redirect( $redirect_to );
            exit;
        }

    }

    /**
     * @return object
     */
    public function get_listings_count()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'directia';
        // $totals = (array) $wpdb->get_results("SELECT status, COUNT( * ) AS total FROM {$table} GROUP BY status", ARRAY_A );
        $totals = [];

        $listing_count = array(
            'all'             => 0,
            'trash'           => 0,
        );

        foreach ( $totals as $row ) {
            switch ( $row['status'] ) {
                case 'all':
                    $listing_count['all']        = $row['total'];
                    $listing_count['all']        += $row['total'];
                    break;                  
                case 'trash':
                    $listing_count['trash']        = $row['total'];
                    $listing_count['all']          += $row['total'];
                    break;
                default:
                    break;
            }
        }

        return (object) $listing_count;
    }

    /**
     * @global int $post_id
     * @global string $comment_status
     * @global string $comment_type
     */
    protected function get_views()
    {
        global $post_id, $listing_status;

        $status_links = array();
        $num_comments = $this->get_listings_count();
        $stati = array(
            /* translators: %s: Number of comments. */
            'all'       => _nx_noop(
                'All <span class="count">(%s)</span>',
                'All <span class="count">(%s)</span>',
                'comments',
                'directia'
            ), // Singular not used.

            /* translators: %s: Number of comments. */
            'trash' => _nx_noop(
                'Trash <span class="count">(%s)</span>',
                'Trash <span class="count">(%s)</span>',
                'comments',
                'directia'
            )
        );

        $link = admin_url('admin.php?page=directia');

        if ( ! empty( $listing_status ) && 'all' != $listing_status ) {
            $link = add_query_arg( 'listing_status', $listing_status, $link );
        }

        foreach ( $stati as $status => $label ) {
            $current_link_attributes = '';

            if ( $status === $listing_status ) {
                $current_link_attributes = ' class="current" aria-current="page"';
            }

            $link = add_query_arg( 'listing_status', $status, $link );
            if ( $post_id ) {
                $link = add_query_arg( 'p', absint( $post_id ), $link );
            }

            $status_links[ $status ] = "<a href='$link'$current_link_attributes>" . sprintf(
                    translate_nooped_plural( $label, $num_comments->$status ),
                    sprintf(
                        '<span class="%s-count">%s</span>',
                        ( 'moderated' === $status ) ? 'pending' : $status,
                        number_format_i18n( $num_comments->$status )
                    )
                ) . '</a>';
        }

        return apply_filters( 'listing_status_links', $status_links );
    }

    private function get_amount_with_currency_symbol( $form_id, $amount ) {

        $give_options        = give_get_settings();
        $currency_position   = isset( $give_options['currency_position'] ) ? $give_options['currency_position'] : 'before';
        $symbol              = give_currency_symbol( give_get_currency( $form_id, [] ) );
        $currency_output     = '<span class="give-currency-symbol give-currency-position-' . esc_attr($currency_position) . '">' . esc_html($symbol) . '</span>';

        $default_amount      = give_format_amount(
            $amount,
            [
                'sanitize' => false,
                'currency' => give_get_currency( $form_id ),
            ]
        );

        $output = '';
        if ( 'before' === $currency_position ) {
            $output .= $currency_output;
        }

        $output .= $default_amount;

        if ( 'after' === $currency_position ) {
            $output .= $currency_output;
        }

        return $output; 
    }


}
endif;