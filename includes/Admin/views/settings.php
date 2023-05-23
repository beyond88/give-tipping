<?php settings_errors(); ?>

<div class="wrap">
    <h1 id="give-subscription-list-h1" class="wp-heading-inline">
        <?php echo __('Tipping','give-tipping'); ?>
    </h1>

    <div class="nav-tab-wrapper give-nav-tab-wrapper">
        <form method="post" id="dpgw-settings-form" action="options.php" novalidate="novalidate">
            <?php settings_fields( $this->_optionGroup ); ?>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="">
                                <?php echo __('Tipping Type:'); ?>
                            </label>
                        </th>
                        <td class="">
                            <?php $type = $settings['tipping_type']; ?>
                            <select name="gt_settings[tipping_type]" id="give_tipping_type" class="">
                                <option value="fixed" <?php if( $type == 'amount'){ echo "selected"; } ?>><?php echo __('Fixed Amount', 'give-tipping'); ?></option>
                                <option value="percentage" <?php if( $type == 'percentage'){ echo "selected"; } ?>><?php echo __('Percentage', 'give-tipping'); ?></option>
                            </select>
                            <div class="give-field-description"></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="success_page">
                                &nbsp;
                            </label>
                        </th>
                        <td class="">
                            <div class="give-tipping-control-wrapper">
                                <table class="form-table">
                                    <tbody id="gt-append_body" class="ui-sortable">
                                        <?php
                                            $amount = [];
                                            if( isset( $settings['give_tipping_amount'] ) ) {
                                                $amount = $settings['give_tipping_amount'];
                                            }
                                            echo $this->get_amount_markup($amount); 
                                        ?>
                                    </tbody>
                                </table>
                                <table class="form-table">
                                    <tbody>
                                        <tr valign="top">
                                            <td class="middle-align">
                                                <div class="gt-click-area-wrapper" >
                                                    <div class="gt-click-overlay" id="gt-click-overlay">
                                                        <label for="gt-add-field" class="gt-group-field-add">
                                                            <span class="dashicons dashicons-plus-alt gt_add_field_icon"></span>
                                                            <span class="gt_add_field_label"><?php echo __('Add','give-tipping'); ?></span>
                                                            <input type="button" name="gt-add-field" id="gt-add-field" class="gt-add-field" value="">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php do_settings_fields( $this->_optionGroup, 'default' ); ?>
            <?php do_settings_sections( $this->_optionGroup, 'default' ); ?>
            <?php submit_button('Save Settings', 'btn-settings gt-settings-button'); ?>
        </form>
    </div>    
</div>