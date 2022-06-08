(function ($) {
    $(document).on('submit', '.dkPlus_sync', function (e) {
        e.preventDefault()

        var form = $(this)
        var button = form.find('input[type="submit"]')

        $.ajax({
            url: ajax.url,
            type: 'POST',
            data: {
                'action': 'dkPlus_sync_products_all',
                'data': form.serialize()
            },
            beforeSend: function () {
                button.prop('disabled', 1)
            },
            success: function () {
                button.prop('disabled', 0)
                //$.ajax($fragment_refresh);
            }
        })
    })
})(jQuery)
