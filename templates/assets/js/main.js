(function ($) {
    $('.dkPlus_sync').submit(function (e) {
        e.preventDefault()

        var form = $(this)
        var button = form.find('input[type="submit"]')
        var action = form.find('input[name="action"]').val()

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: {
                'action': action,
                'data': form.serialize()
            },
            beforeSend: function () {
                button.prop('disabled', 1)
            },
            success: function () {
                button.prop('disabled', 0)
            }
        })
    })

    $('.product_sync_form button').click(function (e) {
        e.preventDefault()

        var form = $('.product_sync_form')
        var inputs = form.find('input')
        var button = $(this)
        var action = form.find('input[name="sync_action"]').val()
        var formData = []
        var itemName, itemVal
        var sku = $('input[name="_sku"]')

        if (!sku.val()) {
            $('a[href="#inventory_product_data"]').trigger('click')
            sku.focus()
            alert('Please, enter the SKU of the product')
            return;
        }

        inputs.each(function (e) {
            itemName = $(this).prop('name')
            itemVal = $(this).val()

            if (itemName === 'sync_action') return

            formData[e] = itemName + '=' + itemVal
        })

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: {
                'action': action,
                'data': formData.join('&'),
            },
            beforeSend: function () {
                button.prop('disabled', 1)
            },
            success: function (data) {
                /*var result = $.parseJSON(data)

                if (result.status === true) {
                    content_replace_items(result.content)
                }
*/
                button.prop('disabled', 0)
                window.location.href = window.location.href + '&message=1'

            }
        })
    })

    function content_replace_items(params) {
        $.each(params, function(i, item) {
            $(i).val(item.content)
        })
    }
})(jQuery)
