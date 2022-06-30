<div id="product_tab_content" class="panel woocommerce_options_panel product_sync_form">
    <div id="universal-message-container" class="wc-metaboxes-wrapper">
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
            ], [
                'type' => 'checkbox',
                'label' => 'Price',
                'id' => 'set_regular_price',
                'name' => 'set_regular_price',
            ], [
                'type' => 'checkbox',
                'label' => 'Quantity',
                'id' => 'set_stock_quantity',
                'name' => 'set_stock_quantity',
            ], [
                'type' => 'checkbox',
                'label' => 'Enable stock (if disabled)',
                'id' => 'set_manage_stock',
                'name' => 'set_manage_stock',
            ], [
                'type' => 'hidden',
                'name' => 'sync_action',
                'value' => 'dkPlus_sync_products_one',
            ], [
                'type' => 'hidden',
                'name' => 'product_id',
                'value' => get_the_ID(),
            ],
        ]; ?>

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
            <button type="button" class="button button-primary">Product synchronization</button>
        </div>
    </div>
</div>