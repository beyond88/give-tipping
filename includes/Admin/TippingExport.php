<?php 
namespace Give_Tipping\Admin;
use Give_Tipping\Helpers;

class TippingExport {

    /**
     * Initialize the class
     */
    public function __construct() {
        add_action( 'give_tipping_export_button', [ $this, 'give_tipping_export_button' ], PHP_INT_MAX );
        add_action( 'admin_init', [ $this, 'export_tipping_list' ] );
    }

    /**
     * Render tipping list export button
     * 
     * @param none
     * @return void
     */
    public function give_tipping_export_button( $url ) {
    ?>
        <div class="give-filter">
            <?php submit_button( __( 'Export', 'give-tipping' ), 'secondary', 'give-tipping-export', false ); ?>
        </div>
    <?php
    }

    /**
     * Export tipping list
     * 
     * @param none
     * @return void
     */
    public function export_tipping_list() {

        if( isset( $_GET['give-tipping-export'] ) ) {
            
            $tips = Helpers::get_tipping_list();
            if ( $tips ) {

                header('Content-type: text/csv');
                header('Content-Disposition: attachment; filename="give-tipping-lists.csv"');
                header('Pragma: no-cache');
                header('Expires: 0');
      
                $file = fopen('php://output', 'w');
      
                fputcsv($file, ['Donation Id', 'Campaign', 'Donation', 'Tip Amount', 'Donor', 'Date']);
      
                foreach ($tips as $tip) {

                    $donation_id = $tip['donation_id'];
                    $currency_code   = give_get_payment_currency_code( $donation_id );
                    $campaign = give_get_meta( $donation_id, '_give_payment_form_title', true );
                    $donor = give_get_meta( $donation_id, '_give_donor_billing_first_name', true ) .' '. give_get_meta( $donation_id, '_give_donor_billing_last_name', true );
                    $payment_total = (float) give_get_meta( $donation_id, '_give_payment_total', true );
                    $tip_total = (float) give_get_meta( $donation_id, '_give_tip_amount', true );
                    
                    $donation = 0;
                    if( is_numeric( $payment_total ) && is_numeric( $tip_total ) ) {
                        $donation = $payment_total - $tip_total;
                    }
                    $date = give_get_meta( $donation_id, '_give_completed_date', true );
                    $donation = $currency_code . number_format($donation, 2);
                    $tip_total = $currency_code . number_format($tip_total, 2);

                    fputcsv($file, [$donation_id, $campaign, $donation, $tip_total, $donor, $date]);
                }
      
                exit();
            }
        }
    }

}