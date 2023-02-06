<div id="product_tab_content" class="panel woocommerce_options_panel product_sync_form">
    <div id="universal-message-container" class="wc-metaboxes-wrapper">
        <h2><?php use woocoo\App\Modules\dkPlus\Main;

            echo esc_html(__('Product synchronization', PLUGIN_SLUG)); ?></h2>
        <?php

            $syncParams = [
            [
                'type' => 'checkbox', //require
                'label' => __('Product name', PLUGIN_SLUG), //require
                'id' => 'set_name', //require
                'name' => 'name', //require
                'checked' => true,
            ], [
                'type' => 'checkbox',
                'label' => 'Description',
                'id' => 'set_description',
                'name' => 'description',
                'checked' => true,
            ], [
                'type' => 'checkbox',
                'label' => __('Price', PLUGIN_SLUG),
                'id' => 'set_regular_price',
                'name' => 'regular_price',
                'checked' => true,
            ], [
                'type' => 'checkbox',
                'label' => __('Quantity', PLUGIN_SLUG),
                'id' => 'set_stock_quantity',
                'name' => 'stock_quantity',
                'checked' => true,
            ], [
                'type' => 'checkbox',
                'label' => __('Enable stock (if disabled)', PLUGIN_SLUG),
                'id' => 'set_manage_stock',
                'name' => 'manage_stock',
                'checked' => true,
            ], [
                'type' => 'hidden',
                'name' => 'product_id',
                'value' => get_the_ID(),
            ],
        ];

        $settings = Main::getInstance();
        $params = $settings[Main::$module_slug]['schedule']['params'];

        // Loading the sync options for the product, that were set in "dkPlus synchronization" product tab #30
        foreach ($syncParams as $key => $param) {
            if ($syncParams[$key]['type'] == 'checkbox') {
                $value = get_post_meta(get_the_ID(), '_woocoo_' . $syncParams[$key]['name'], true);
                if ($value) {
                    $syncParams[$key]['checked'] = ($value === 'on') ? true: false;
                } else {
                    // Loading the sync options for the product, that were set in "Woo Bookkeeping/dkPlus" settings tab #30
                    $syncParams[$key]['checked'] = (array_search($syncParams[$key]['name'], $params) !== false) ? true: false;
                }
            }
        }

            ?>

        <div class="options">
            <label><?php echo __('Select options to sync', PLUGIN_SLUG); ?></label>
            <?php foreach ($syncParams as $param): ?>
                <?php if ($param['type'] === 'hidden'): ?>
                    <input type="hidden" name="<?php echo $param['name']; ?>" value="<?php echo $param['value']; ?>">
                <?php else: ?>
                    <p class="form-field comment_status_field ">
                        <label for="<?php echo $param['id']; ?>"><?php echo $param['label']; ?></label>
                        <input type="<?php echo $param['type']; ?>" id="<?php echo $param['id']; ?>" name="<?php echo $param['name']; ?>" <?php if (!empty($param['checked'])) echo 'checked'; ?>>
                    </p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="toolbar">
            <button type="button" class="button button-primary service_dkplus" data-action="dkPlus_sync_product_one"><?php echo __('Product synchronization', PLUGIN_SLUG); ?></button>
            <button type="button" class="button button-primary service_dkplus dkPlus_send_to" data-action="dkPlus_send_to"><?php echo __('Send to dkPlus', PLUGIN_SLUG); ?></button>
        </div>
    </div>
</div>

