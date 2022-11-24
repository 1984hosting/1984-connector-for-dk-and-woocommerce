(function ($) {
    /** Tabs */
    $('.woocoo_tabs').tabs()

    /**
     * Save data account
     */
    $('.dkPlus_save_account').submit(function (e) {
        e.preventDefault()
        var button = $(this).find('input[type="submit"]')

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function () {
                button.prop('disabled', 1)
            },
            success: function (data) {
                unsetProgressbar('.woo_progress')

                $.ajax({
                    url: ajax.url,
                    type: 'POST',
                    data: {
                        'action': 'woo_get_token'
                    },
                    success: function (data) {
                        if (!isJson(data)) return false
                        var response = $.parseJSON(data)
                        button.prop('disabled', 0)
                        alert(response.message)
                        window.location.reload()
                    }
                })
            }
        })
    })

    /**
     * Product Sync Settings
     */
    $('.dkPlus_sync input[type="button"]').click(function (e) {
        e.preventDefault()
        var button = $(this),
            action = button.data('action'),
            form = button.closest('form'),
            form_buttons = form.find('input[type="button"]'),
            form_data = new FormData(form[0])

        form_data.append('action', action)

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: form_data,
            processData: false,
            contentType: false,
            beforeSend: function () {
                propButtons(form_buttons, 'disabled', 1)
                if (form_data.get('action') === 'dkPlus_sync') {
                    manual_start = true
                }
            },
            success: function (data) {
                if (!isJson(data)) return false
                var response = $.parseJSON(data)
                if (response.status === 'error' || response.status === 'success' || response.status === 'empty') {
                    propButtons(form_buttons, 'disabled', 0)
                }
                //propButtons(form_buttons, 'disabled', 0)
                updateProgress()
                alert(response.message)
            }
        })
    })

    /**
     * Sync dk single product page
     */
    $('button[data-action="dkPlus_sync_product_one"], button[data-action="dkPlus_send_to"]').click(function (e) {
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
                if (!isJson(data)) return false
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
    let import_progress_tag = '.dkPlus_import_progress'

    $('.dkPlus_import').submit(function (e) {
        e.preventDefault()

        var form = $(this),
            button = $(this).find('input[type="submit"]'),
            button_prolong = form.find('input[name="dkPlus_import_prolong"]')

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function () {
                button.prop('disabled', 1)
                button_prolong.prop('disabled', 1)
                setProgressbar(import_progress_tag, 0)
                manual_start = true
            },
            success: function (data) {
                if (!isJson(data)) return false
                var response = $.parseJSON(data)

                //setProgressbar(import_progress_tag, response.completed_percent)
                switch (response.status) {
                    case 'prolong':
                        alert(response.message)
                        productsImportProlong(button)
                        break
                    case 'success':
                        button.prop('disabled', 0)
                        button_prolong.remove()
                        updateProgress()
                        //alert(response.message)
                        //unsetProgressbar(import_progress_tag)
                        break
                    case 'empty':
                    default:
                        alert(response.message ?? 'not valid response status')
                        button.prop('disabled', 0)
                        button_prolong.prop('disabled', 0)
                        unsetProgressbar(import_progress_tag)
                }
            }
        })
    })

    $('form input[name="dkPlus_import_prolong"]').on('click', function (e) {
        e.preventDefault()
        var form = $(this).closest('form')
        var button = form.find('input[name="dkPlus_import"]')
        var button_prolong = $(this)

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: {
                'action': 'dkPlus_import_refresh',
            },
            beforeSend: function () {
                button.prop('disabled', 1)
                button_prolong.prop('disabled', 1)
            },
            success: function (data) {
                if (!isJson(data)) return false
                var response = $.parseJSON(data)

                //setProgressbar('.dkPlus_import_progress', response.completed_percent)
                switch (response.status) {
                    case 'prolong':
                        productsImportProlong(button)
                        break
                    case 'success':
                        button.prop('disabled', 0)
                        button_prolong.remove()
                        updateProgress()
                        alert(response.message)
                        break
                    default:
                        alert('not valid response status')
                }
            }
        })
    })
    $('#dkPlus_logs_clear').on('click', function (e) {
        e.preventDefault()

        let button = $(this),
            action = $(this).data('action')

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: {
                'action': action,
            },
            beforeSend: function () {
                button.prop('disabled', 1)
            },
            success: function () {
                button.prop('disabled', 0)
                updateProgress()
            }
        })
    })


    function productsImportProlong(button) {
        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: {
                'action': 'dkPlus_import_prolong',
            },
            success: function (data) {
                if (!isJson(data)) return false
                var response = $.parseJSON(data)

                //setProgressbar('.dkPlus_import_progress', response.completed_percent)
                updateProgress()
                switch (response.status) {
                    case 'prolong':
                        productsImportProlong(button)
                        break
                    case 'success':
                        button.prop('disabled', 0)
                        //alert(response.message)
                        break
                    default:
                        alert('not valid response status')
                }
            }
        })
    }

})(jQuery)

var $ = jQuery

let manual_start = false
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

    progress_block.show('fast')
    progress_title.width(width).attr('data-value', data_value)
    progress_bar.val(value)
}

function unsetProgressbar(tag) {
    $(tag).hide('fast')
}

function updateProgress() {
    console.log(manual_start)
    $.ajax({
        url: ajax.url,
        type: 'POST',
        data: {
            'action': 'dkPlus_status',
        },
        success: function (data) {
            if (data.length === 0 || !isJson(data)) return false
            let response = $.parseJSON(data)

            $.each(response, function (index, value) {
                //response tag - class form class name
                if (value === false) {
                    return true
                }

                if (typeof value !== 'string') {
                    let tag = '.' + index + ' .woo_progress'
                    if (value.length === 0) {
                        unsetProgressbar(tag)
                        return true
                    }

                    setProgressbar(tag, value.completed_percent)
                    if (value.completed_percent == 100) {
                        form_buttons = $('.' + index).closest('form').find('input[type="button"]'),
                        propButtons(form_buttons, 'disabled', 0)
                        if (manual_start) {
                            manual_start = false
                            alert(value.message)
                        }
                        unsetProgressbar(tag)
                    }
                } else {
                    if (value.length === 0) {
                        return true
                    }
                    $('.' + index).html(value)
                }
            })
        }
    })
}
function propButtons(buttons, name, value) {
    buttons.each(function () {
        $(this).prop(name, value)
    })
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}