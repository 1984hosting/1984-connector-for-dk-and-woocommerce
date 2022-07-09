<?php
$main = \woo_bookkeeping\App\Core\Main::getInstance();
$dkPlus = !empty($main['dkPlus']) ? $main['dkPlus'] : false;
$dkPlus_schedule = !empty($dkPlus['schedule']) ? $dkPlus['schedule'] : false;
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form method="post" action="<?php echo 'options.php'; ?>">
        <div id="universal-message-container">
            <h2><?php echo __('Account settings', PLUGIN_SLUG); ?></h2>

            <div class="options">
                <p>
                    <label><?php echo __('Please enter your dkPlus account details', PLUGIN_SLUG); ?></label>
                    <br/>
                    <?php settings_fields(PLUGIN_SLUG); // settings group name; ?>
                </p>
            </div>

            <?php
            do_settings_sections( PLUGIN_SLUG );
            //wp_nonce_field('dkPlus_settings_save', 'dkPlus_settings_save');
            submit_button();
            ?>
        </div>
    </form>

    <form method="post" action="" class="dkPlus_sync">
        <div id="universal-message-container">
            <h2><?php echo esc_html(__('Product synchronization', PLUGIN_SLUG)); ?></h2>
            <?php $syncParams = [
                [
                    'type' => 'checkbox', //require
                    'label' => 'Description', //require
                    'id' => 'set_description', //require
                    'name' => 'description', //require
                ], [
                    'type' => 'checkbox',
                    'label' => 'Price',
                    'id' => 'set_regular_price',
                    'name' => 'regular_price',
                ], [
                    'type' => 'checkbox',
                    'label' => 'Quantity',
                    'id' => 'set_stock_quantity',
                    'name' => 'stock_quantity',
                ], [
                    'type' => 'checkbox',
                    'label' => 'Enable stock (if disabled)',
                    'id' => 'set_manage_stock',
                    'name' => 'manage_stock',
                ], [
                    'type' => 'checkbox',
                    'label' => 'Data modified',
                    'id' => 'set_date_modified',
                    'name' => 'date_modified',
                ],
            ]; ?>

            <div class="options">
                <p>
                    <label><?php echo __('Select options to sync', PLUGIN_SLUG); ?></label>
                    <br/>
                <table class="form-table" role="presentation">
                    <tbody>
                        <?php foreach ($syncParams as $param): ?>
                            <?php if ($param['type'] === 'hidden'): ?>
                                <input type="hidden" name="<?php echo $param['name']; ?>" value="<?php echo $param['value']; ?>">
                            <?php else: ?>
                                <tr>
                                    <th scope="row">
                                        <label for="<?php echo $param['id']; ?>"><?php echo $param['label']; ?></label>
                                    </th>
                                    <td>
                                        <input type="<?php echo $param['type']; ?>" id="<?php echo $param['id']; ?>" name="<?php echo $param['name']; ?>" <?php if (isset($dkPlus_schedule['params']) && in_array($param['name'], $dkPlus_schedule['params'])) echo 'checked'; ?>>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <tr>
                            <th scope="row" valign="top">
                                <label for="crontrol_schedule">Recurrence</label>
                            </th>
                            <td>
                                <?php $variations = \woo_bookkeeping\App\Modules\dkPlus\Events::getVariations(); ?>
                                <select class="postform" name="woocoo_schedule" id="woocoo_schedule" required="">
                                    <?php foreach ($variations as $key => $variation): ?>
                                        <option value="<?php echo $key; ?>" <?php if (isset($dkPlus_schedule['name']) && $dkPlus_schedule['name'] === $key) echo 'selected'; ?>><?php echo $variation['display']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </p>
            </div>
            <p>
                <input type="submit" name="dkPlus_save" id="dkPlus_save" class="button button-primary" value="<?php echo __('dkPlus save', PLUGIN_SLUG); ?>" data-action="dkPlus_save">
                <input type="submit" name="dkPlus_save_and_sync" id="dkPlus_save_and_sync" class="button button-primary" value="<?php echo __('dkPlus save and sync', PLUGIN_SLUG); ?>" data-action="dkPlus_save_and_sync">
            </p>
        </div>
    </form>

</div><!-- .wrap -->