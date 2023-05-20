<div class="wrap">
    <h1 id="give-subscription-list-h1" class="wp-heading-inline">
        <?php echo __('Tipping',''); ?>
    </h1>

<div class="nav-tab-wrapper give-nav-tab-wrapper">
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="success_page">
                        <?php echo __('Tipping type:'); ?>
                    </label>
                </th>
                <td class="">
                    <select name="success_page" id="success_page" class="">
                        <option value="amount"><?php echo __('Fixed Amount', 'give-tipping'); ?></option>
                        <option value="percentage"><?php echo __('Percentage', 'give-tipping'); ?></option>
                    </select>
                    <div class="give-field-description"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>    
</div>