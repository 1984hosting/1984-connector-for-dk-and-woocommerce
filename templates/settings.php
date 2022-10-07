<?php
$main = woo_bookkeeping\App\Core\Main::getInstance();
$dkPlus = !empty($main['dkPlus']) ? $main['dkPlus'] : false;
$dkPlus_schedule = !empty($dkPlus['schedule']) ? $dkPlus['schedule'] : false;
$import_status = woo_bookkeeping\App\Modules\dkPlus\Page::incompleteImport();
$sync_status = woo_bookkeeping\App\Modules\dkPlus\Page::incompleteSync();

$syncParams = [
    [
        'type' => 'checkbox',
        'label' => __('Product name', PLUGIN_SLUG),
        'id' => 'name',
        'name' => 'sync_params[]',
        'value' => 'name',
    ], [
        'type' => 'checkbox',
        'label' => __('Description', PLUGIN_SLUG),
        'id' => 'description',
        'name' => 'sync_params[]',
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
                <li><a href="#dkPlus_service"><?php echo __('dkPlus', PLUGIN_SLUG); ?></a></li>
            </ul>

            <div class="tabs_content">
                <div id="account-settings">
                    <form method="post" action="<?php echo 'options.php'; ?>" class="dkPlus_save_account">

                        <div id="universal-message-container">
                            <h2><?php echo __('Account settings', PLUGIN_SLUG); ?></h2>

                            <div class="options">
                                <p>
                                    <label><?php echo __('Please enter your account details', PLUGIN_SLUG); ?></label>
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

                <div id="dkPlus_service">
                    <form method="post" action="" class="dkPlus_sync">
                        <div id="universal-message-container">
                            <h2><?php echo esc_html(__('Automatic product synchronization', PLUGIN_SLUG)); ?></h2>

                            <div class="options">
                                <p>
                                    <label><?php echo __('Select options to sync', PLUGIN_SLUG); ?></label>
                                    <br/>
                                </p>
                                <div class="form-table" role="presentation">
                                    <?php foreach ($syncParams as $param): ?>
                                        <?php if ($param['type'] === 'hidden'): ?>
                                            <input type="hidden" name="<?php echo $param['name']; ?>" value="<?php echo $param['value']; ?>">
                                        <?php else: ?>
                                            <div class="form-table-item bg-color">
                                                <div class="th" scope="row">
                                                    <label for="<?php echo $param['id']; ?>"><?php echo $param['label']; ?></label>
                                                </div>
                                                <div class="td">
                                                    <input type="<?php echo $param['type']; ?>"
                                                           id="<?php echo $param['id']; ?>"
                                                           name="<?php echo $param['name']; ?>"
                                                           <?php if (isset($param['value'])): ?>value="<?php echo $param['value']; ?>"<?php endif; ?>
                                                        <?php if (isset($dkPlus_schedule['params']) && in_array($param['id'], $dkPlus_schedule['params'])) echo 'checked'; ?>>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <div class="form-table-item select">
                                    <div class="th" scope="row" valign="top">
                                        <label for="crontrol_schedule"><?php echo __('Recurrence', PLUGIN_SLUG); ?></label>
                                    </div>
                                    <div class="td">
                                        <?php $variations = woo_bookkeeping\App\Modules\dkPlus\Events::getVariations(); ?>
                                        <select class="postform" name="woocoo_schedule" id="woocoo_schedule" required>
                                            <?php foreach ($variations as $key => $variation): ?>
                                                <option value="<?php echo $key; ?>" <?php if (isset($dkPlus_schedule['name']) && $dkPlus_schedule['name'] === $key) echo 'selected'; ?>>
                                                    <?php echo $variation['display']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <p>
                                <input type="button"
                                       name="dkPlus_save"
                                       id="dkPlus_save"
                                       class="button button-primary"
                                       value="<?php echo __('Save', PLUGIN_SLUG); ?>"
                                        data-action="dkPlus_save">
                                <input type="button"
                                       name="dkPlus_sync"
                                       id="dkPlus_sync"
                                       class="button button-primary"
                                       value="<?php echo __('Manual start sync', PLUGIN_SLUG); ?>"
                                       data-action="dkPlus_sync"
                                       <?php if (isset($sync_status['completed_percent']) && $sync_status['completed_percent'] < 100) echo 'disabled'; ?>>
                            </p>
                            <div class="dkPlus_sync_progress woo_progress" <?php if (!isset($sync_status['completed_percent']) || $sync_status['completed_percent'] == 100): ?>style="display: none"<?php endif; ?>>
                                <p <?php if (isset($sync_status['completed_percent'])): ?> style="width: <?php echo $sync_status['completed_percent'] > 10 ? $sync_status['completed_percent'] . '%' : '100px'; ?>" data-value="<?php echo $sync_status['completed_percent']; ?>"<?php endif; ?>><?php echo __('Progress', PLUGIN_SLUG); ?></p>
                                <progress class="progress_sync" max="100" value="<?php echo $sync_status['completed_percent'] ?? 0; ?>"></progress>
                            </div>
                        </div>
                    </form>

                    <form method="post" action="" class="dkPlus_import">
                        <div id="universal-message-container">
                            <h2><?php echo esc_html(__('Import products from dkPlus', PLUGIN_SLUG)); ?></h2>
                            <div class="options">
                                <p>
                                    <label><?php echo __('Select options to import new products', PLUGIN_SLUG); ?></label>
                                    <br/>
                                <div class="form-table" role="presentation">
                                    <?php foreach ($syncParams as $param): ?>
                                        <?php if ($param['type'] === 'hidden'): ?>
                                            <input type="hidden" name="<?php echo $param['name']; ?>"
                                                   value="<?php echo $param['value']; ?>">
                                        <?php else: ?>
                                            <div class="form-table-item bg-color">
                                                <div class="th" scope="row">
                                                    <label for="<?php echo $param['id']; ?>_import"><?php echo $param['label']; ?></label>
                                                </div>
                                                <div class="td">
                                                    <input type="<?php echo $param['type']; ?>"
                                                           id="<?php echo $param['id']; ?>_import"
                                                           name="<?php echo $param['name']; ?>"
                                                           <?php if (isset($param['value'])): ?>value="<?php echo $param['value']; ?>"<?php endif; ?>
                                                        <?php if (isset($dkPlus_schedule['params']) && in_array($param['id'], $dkPlus_schedule['params'])) echo 'checked'; ?>>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                </p>
                            </div>
                            <p>
                                <?php if (isset($import_status['completed_percent']) && $import_status['completed_percent'] < 100): ?>
                                    <input type="button" name="dkPlus_import_prolong" id="dkPlus_import_prolong"
                                           class="button button-primary"
                                           value="<?php echo __('Prolong import', PLUGIN_SLUG); ?>"
                                           data-action="dkPlus_import">
                                    <input type="submit" name="dkPlus_import" id="dkPlus_import"
                                           class="button button-danger"
                                           value="<?php echo __('Start a new import', PLUGIN_SLUG); ?>"
                                           data-action="dkPlus_import">
                                <?php else: ?>
                                    <input type="submit" name="dkPlus_import" id="dkPlus_import"
                                           class="button button-primary"
                                           value="<?php echo __('Import', PLUGIN_SLUG); ?>" data-action="dkPlus_import">
                                <?php endif; ?>
                            </p>

                            <div class="dkPlus_import_progress woo_progress" <?php if (!isset($import_status['completed_percent']) || $import_status['completed_percent'] == 100): ?>style="display: none"<?php endif; ?>>
                                <p <?php if (isset($import_status['completed_percent'])): ?> style="width: <?php echo $import_status['completed_percent'] > 10 ? $import_status['completed_percent'] . '%' : '100px'; ?>" data-value="<?php echo $import_status['completed_percent']; ?>"<?php endif; ?>><?php echo __('Progress', PLUGIN_SLUG); ?></p>
                                <progress class="progress_import" max="100" value="<?php echo $import_status['completed_percent'] ?? 0; ?>"></progress>
                            </div>

                        </div>
                        <input type="hidden" name="action" value="dkPlus_import">
                    </form>
                </div>
            </div>
        </div>

    </div><!-- .wrap -->
<?php if ($dkPlus): ?>
    <script>
        jQuery(document).ready(function () {
            jQuery('.woocoo_tabs').tabs({active: 1})
        })
    </script>
<?php endif; ?>



    <script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready( function($) {
            //jQuery selector to point to
            $('#menu-dashboard').pointer({
                content: '<h3>WordPress Answers</h3>',
                position: 'top',
                close: function() {
                    // This function is fired when you click the close button
                }
            }).pointer('open');
        });
        //]]>
    </script>
