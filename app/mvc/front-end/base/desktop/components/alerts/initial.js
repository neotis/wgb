/**
 * Custom alert
 */
var alertItemCounter = 0;
window.alert = function (message, type = 'danger', position = 'top-left', delay = 6000) {
    type = (type === undefined) ? 'danger' : type;
    if (Array.isArray(message)) {
        alertItemCounter++;
        string = '<div class="danger-two-section neo-alert-' + alertItemCounter + '">' +
            '<div class="alert alert-' + type + ' hidden" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '<h6 class="alert-heading">' + message[0] + '</h6>' +
            '<hr>' +
            '<p class="mb-0">' + message[1] + '</p>' +
            '</div>' +
            '</div>';
        $('neo-component-alerts').append(string);
        $('neo-component-alerts').addClass(position);
        ele = $('.neo-alert-' + alertItemCounter);
        ele.delay(delay).fadeOut(500, function () {
            this.remove();
        });
    } else {
        alertItemCounter++;
        string = '<div class="alert alert-' + type + ' neo-alert-' + alertItemCounter + '" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            message +
            '</div>';
        string = '<div class="alert alert-' + type + ' alert-dismissible fade show neo-alert-' + alertItemCounter + '" role="alert">' +
            message +
            '    <button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '        <span aria-hidden="true">×</span>' +
            '    </button>' +
            '</div>';
        $('neo-component-alerts').append(string);
        $('neo-component-alerts').addClass(position);
        ele = $('.neo-alert-' + alertItemCounter);
        ele.delay(delay).fadeOut(500, function () {
            this.remove();
        });
        $('neo-component-alerts').show();
    }
};

/**
 * General event for login page
 */
    $(document).ready(function () {
    /**
     * Close alert box
     */
    $("body").on('click', "neo-component-alerts .close", function () {
        $(this).parents('.danger-two-section').remove();
    });
});
