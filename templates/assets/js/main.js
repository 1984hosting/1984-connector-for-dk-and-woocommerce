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
})(jQuery)
