$.ajaxSetup({
    type: 'POST',
    data: {
        csrf_name: csrf_name,
        csrf_value: csrf_value
    }
});

$(document).ajaxSend(function () {
    $('html').css('cursor', 'wait');
}).ajaxSuccess(function (e, xhr) {
    if (syd.cookie('debug') == '1') {
        console.log(xhr.responseText)
    }
    if (xhr.getResponseHeader('Content-Type') == 'application/json') {
        var response = $.parseJSON(xhr.responseText);

        if ('notify' in response) {
            syd.notify(response.notify.message, response.notify.status)
        }

        if ('reload' in response) {
            window.location.reload()
        }

        if ('redirect' in response) {
            location.href = response.redirect
        }
    }
}).ajaxError(function () {
    $('html').css('cursor', 'auto');
    syd.notify('AJAX 404 (Not Found)', 'danger')
}).ajaxComplete(function () {
    $('html').css('cursor', 'auto')
});
