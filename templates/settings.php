<?php
$main = \woo_bookkeeping\App\Core\Main::getInstance();
$dkPlus = !empty($main['dkPlus']) ? $main['dkPlus'] : false;
$dkPlus_schedule = !empty($dkPlus['schedule']) ? $dkPlus['schedule'] : false;

$syncParams = [
    [
        'type' => 'checkbox', //require
        'label' => __('Description', PLUGIN_SLUG), //require
        'id' => 'description', //require
        'name' => 'sync_params[]', //require
        'value' => 'description',
    ], [
        'type' => 'checkbox',
        'label' => __('Price', PLUGIN_SLUG),
        'id' => 'regular_price',
        'name' => 'sync_params[]',
        'value' => 'regular_price',
    ], [
        'type' => 'checkbox',
        'label' => __('Quantity', PLUGIN_SLUG),
        'id' => 'stock_quantity',
        'name' => 'sync_params[]',
        'value' => 'stock_quantity',
    ], [
        'type' => 'checkbox',
        'label' => __('Enable stock (if disabled)', PLUGIN_SLUG),
        'id' => 'manage_stock',
        'name' => 'sync_params[]',
        'value' => 'manage_stock',
    ], [
        'type' => 'checkbox',
        'label' => __('Data modified', PLUGIN_SLUG),
        'id' => 'date_modified',
        'name' => 'sync_params[]',
        'value' => 'date_modified',
    ],
];
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="woocoo_tabs">
        <ul class="tabs_list">
            <li><a href="#account-settings"><?php echo __('Account settings', PLUGIN_SLUG); ?></a></li>
            <li><a href="#service_dkplus"><?php echo __('dkPlus', PLUGIN_SLUG); ?></a></li>
        </ul>

        <div class="tabs_content">
            <div id="account-settings">
                <form method="post" action="<?php echo 'options.php'; ?>" class="woo_save_account">

                    <div id="universal-message-container">
                        <h2><?php echo __('Account settings dkPlus', PLUGIN_SLUG); ?></h2>

                        <div class="options">
                            <p>
                                <label><?php echo __('Please enter your dkPlus account details', PLUGIN_SLUG); ?></label>
                                <br>
                            </p>
                        </div>

                        <table class="form-table" role="presentation">
                            <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="dkPlus_login"><?php echo __('Login', PLUGIN_SLUG); ?></label>
                                </th>
                                <td>
                                    <input type="text"
                                           id="dkPlus_login"
                                           name="<?php echo PLUGIN_SLUG . '[dkPlus][login]'; ?>"
                                           value="<?php echo !empty($dkPlus['login']) ? $dkPlus['login'] : ''; ?>"
                                           placeholder="<?php echo __('Login', PLUGIN_SLUG); ?>"
                                    >
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="dkPlus_password"><?php echo __('Password', PLUGIN_SLUG); ?></label>
                                </th>
                                <td>
                                    <input type="password"
                                           id="dkPlus_password"
                                           name="<?php echo PLUGIN_SLUG . '[dkPlus][password]'; ?>"
                                           value="<?php echo !empty($dkPlus['password']) ? $dkPlus['password'] : ''; ?>"
                                           placeholder="<?php echo __('Password', PLUGIN_SLUG); ?>"
                                    >
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <?php
                        submit_button();
                        wp_nonce_field();
                        ?>
                    </div>

                    <input type="hidden" name="action" value="woo_save_account">
                </form>
            </div>

            <div id="service_dkplus">
                <form method="post" action="" class="dkPlus_sync">
                    <div id="universal-message-container">
                        <h2><?php echo esc_html(__('Product synchronization', PLUGIN_SLUG)); ?></h2>

                        <div class="options">
                            <p>
                                <label><?php echo __('Select options to sync', PLUGIN_SLUG); ?></label>
                                <br/>
                            <table class="form-table" role="presentation">
                                <tbody>
                                <?php foreach ($syncParams as $param): ?>
                                    <?php if ($param['type'] === 'hidden'): ?>
                                        <input type="hidden" name="<?php echo $param['name']; ?>"
                                               value="<?php echo $param['value']; ?>">
                                    <?php else: ?>
                                        <tr>
                                            <th scope="row">
                                                <label for="<?php echo $param['id']; ?>"><?php echo $param['label']; ?></label>
                                            </th>
                                            <td>
                                                <input type="<?php echo $param['type']; ?>"
                                                       id="<?php echo $param['id']; ?>"
                                                       name="<?php echo $param['name']; ?>"
                                                       <?php if (isset($param['value'])): ?>value="<?php echo $param['value']; ?>"<?php endif; ?>
                                                    <?php if (isset($dkPlus_schedule['params']) && in_array($param['id'], $dkPlus_schedule['params'])) echo 'checked'; ?>>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <th scope="row" valign="top">
                                        <label for="crontrol_schedule"><?php echo __('Recurrence', PLUGIN_SLUG); ?></label>
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
                            <input type="submit" name="dkPlus_save" id="dkPlus_save" class="button button-primary"
                                   value="<?php echo __('dkPlus save', PLUGIN_SLUG); ?>" data-action="dkPlus_save">
                            <input type="submit" name="dkPlus_save_and_sync" id="dkPlus_save_and_sync" class="button button-primary"
                                   value="<?php echo __('dkPlus save and sync', PLUGIN_SLUG); ?>"
                                   data-action="dkPlus_save_and_sync">
                        </p>
                    </div>
                </form>

                <form method="post" action="" class="dkPlus_import">
                    <div id="universal-message-container">
                        <h2><?php echo esc_html(__('Import products from dkPlus', PLUGIN_SLUG)); ?></h2>
                        <div class="options">
                            <p>
                                <label><?php echo __('Select options to import and sync', PLUGIN_SLUG); ?></label>
                                <br/>
                            <table class="form-table" role="presentation">
                                <tbody>
                                <?php foreach ($syncParams as $param): ?>
                                    <?php if ($param['type'] === 'hidden'): ?>
                                        <input type="hidden" name="<?php echo $param['name']; ?>"
                                               value="<?php echo $param['value']; ?>">
                                    <?php else: ?>
                                        <tr>
                                            <th scope="row">
                                                <label for="<?php echo $param['id']; ?>"><?php echo $param['label']; ?></label>
                                            </th>
                                            <td>
                                                <input type="<?php echo $param['type']; ?>"
                                                       id="<?php echo $param['id']; ?>"
                                                       name="<?php echo $param['name']; ?>"
                                                       <?php if (isset($param['value'])): ?>value="<?php echo $param['value']; ?>"<?php endif; ?>
                                                       <?php if (isset($dkPlus_schedule['params']) && in_array($param['id'], $dkPlus_schedule['params'])) echo 'checked'; ?>>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            </p>
                        </div>
                        <p>
                            <input type="submit" name="dkPlus_import" id="dkPlus_import" class="button button-primary"
                                   value="<?php echo __('dkPlus import', PLUGIN_SLUG); ?>" data-action="dkPlus_import">
                        </p>
                    </div>
                    <input type="hidden" name="action" value="dkPlus_import">
                </form>
            </div>
        </div>
    </div>

</div><!-- .wrap -->