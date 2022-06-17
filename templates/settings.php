<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form method="post" action="<?php
    //echo esc_html(admin_url('admin-post.php'));
    echo 'options.php';
    ?>">

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
            <?php
            /*
             * wp_posts:
             * id
             * post_content
             * post_title
             * post_status (draft, publish)
             * post_modified, post_modified_gmt
             *
             * product_id
             * sku
             * min_price
             * max_price
             * onsale(продается?)
             * stock_quantity
             * stock_status
              */
            ?>
            <?php $syncParams = [
                [
                    'type' => 'checkbox', //require
                    'label' => 'Description', //require
                    'id' => 'set_description', //require
                    'name' => 'set_description', //require
                    /*
                     * Description
                     */
                    'checked' => true, //optional
                ],
                [
                    'type' => 'checkbox',
                    'label' => 'Price',
                    'id' => 'set_price',
                    'name' => 'set_price',
                    /*
                     * todo: ??? UnitPrice1WithTax
                     */
                    'checked' => true,
                ],
                [
                    'type' => 'checkbox',
                    'label' => 'Quantity',
                    'id' => 'set_stock_quantity',
                    'name' => 'set_stock_quantity',
                    /*
                     * todo: ??? TotalQuantityInWarehouse
                     */
                    'checked' => true,
                ],
                [
                    'type' => 'checkbox',
                    'label' => 'Data modified',
                    'id' => 'set_date_modified',
                    'name' => 'set_date_modified',
                    'checked' => true,
                ],
                [
                    'type' => 'hidden',
                    'name' => 'action',
                    'value' => 'dkPlus_sync_products_all',
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
                                        <input type="<?php echo $param['type']; ?>" id="<?php echo $param['id']; ?>" name="<?php echo $param['name']; ?>" <?php if (!empty($param['checked'])) echo 'checked'; ?>>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </p>
            </div>
            <?php
            submit_button(
                __('Product synchronization', PLUGIN_SLUG),
                'primary',
                'dkPlus_sync',
            );
            ?>
        </div>
        <input type="hidden" name="action" value="dkPlus_sync">
    </form>


</div><!-- .wrap -->