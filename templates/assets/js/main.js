(function ($) {
    /** Tabs */
    $('.woocoo_tabs').tabs();

    /**
     * Save data account
     */
    $('.woo_save_account').on('submit', function (e) {
        e.preventDefault()
        var form = $(this),
            button = $(this).find('input[type="submit"]')

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: form.serializeArray(),
            beforeSend: function () {
                button.prop('disabled', 1)
            },
            success: function (data) {
                var response = $.parseJSON(data)
                button.prop('disabled', 0)

                alert(response.message)
            }
        })
    })

    /**
     * Save sync settings on plugin settings page
     */
    $('.dkPlus_sync input[type="submit"]').click(function (e) {
        e.preventDefault()

        var button = $(this),
            form = button.closest('.dkPlus_sync'),
            action = button.data('action'),
            buttons = form.find('input[type="submit"]'),
            formData = form.serializeArray()

        formData.push({name: 'action', value: action})

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: formData,

            beforeSend: function () {
                propButtons(buttons, 'disabled', 1)
            },

            success: function (data) {
                var response = $.parseJSON(data)
                propButtons(buttons, 'disabled', 0)

                alert(response.message)
            }
        })
    })

    /**
     * Sync dk single product page
     */
    $('button[data-action="dkPlus_sync_product_one"], button[data-action="send_to_dkPlus"]').click(function (e) {
        e.preventDefault()

        var form = $('.product_sync_form'),
            inputs = form.find('input'),
            buttons = $('button.service_dkplus'),
            action = $(this).data('action'),
            formData = {'sync_params': {}},
            itemName, itemVal,
            sku = $('input[name="_sku"]'),
            button = $(this)

        if (!sku.val()) {
            $('a[href="#inventory_product_data"]').trigger('click')
            sku.focus()
            alert('Please, enter the SKU of the product')
            return;
        }

        formData['sku'] = sku.val()

        inputs.each(function (e) {
            itemName = $(this).prop('name')
            itemVal = $(this).val()

            if ($(this).prop('type') === 'checkbox') {
                if (!$(this).prop('checked')) return;

                formData['sync_params'][e] = itemName
            }

            if ($(this).prop('type') === 'hidden') {
                formData[itemName] = itemVal
            }
        })

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: {
                'action': action,
                'data': formData,
            },
            beforeSend: function () {
                propButtons(buttons, 'disabled', 1)
            },
            success: function (data) {
                var response = $.parseJSON(data)

                propButtons(buttons, 'disabled', 0)
                alert(response.message)

                if (button.data('action') === 'dkPlus_sync_product_one') {
                    window.location.href = window.location.href + '&message=1'
                }
            }
        })
    })

    /**
     * Import products
     */
    $('form.dkPlus_import').on('submit', function (e) {
        e.preventDefault()

        var form = $(this),
            button = $(this).find('input[type="submit"]')

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: form.serializeArray(),

            beforeSend: function () {
                button.prop('disabled', 1)
            },

            success: function (data) {
                var response = $.parseJSON(data)

                setProgressbar('.dkPlus_import_progress', response.completed_percent)
                switch (response.status) {
                    case 'prolong':
                        prolongImport(button)
                        break
                    case 'success':
                        button.prop('disabled', 0)
                        alert(response.message)
                        break
                    default:
                        alert('not valid response status')
                }
            }
        })
    })

    /**
     * Setting progress bar
     * @param tag response from backend DOM tag
     * @param value value percent
     */
    function setProgressbar(tag, value) {
        let progress_block = $(tag)
        let progress_bar = progress_block.find('progress')
        let progress_title = progress_block.find('p')
        let width = value < 4 ? '100px' : value + '%'
        let data_value = value === 100 ? 'completed' : value

        progress_block.css('display', 'block')
        progress_title.width(width).attr('data-value', data_value)
        progress_bar.val(value)
    }
    function prolongImport(button) {
        console.log('load function prolong')
        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: {
                'action': 'dkPlus_prolong_import',
            },
            success: function (data) {
                var response = $.parseJSON(data)

                setProgressbar('.dkPlus_import_progress', response.completed_percent)
                switch (response.status) {
                    case 'prolong':
                        prolongImport(button)
                        break
                    case 'success':
                        button.prop('disabled', 0)
                        alert(response.message)
                        break
                    default:
                        alert('not valid response status')
                }
            }
        })
    }


    function propButtons(buttons, name, value) {
        buttons.each(function () {
            $(this).prop(name, value)
        })
    }

})(jQuery)
