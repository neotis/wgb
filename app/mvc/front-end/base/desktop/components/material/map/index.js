$(document).ready(function () {
    $(document).on('focus', 'neo-component-material-text', function (event) {
        $(this).addClass('focus');
        $(this).find('>div').addClass('active');
    });
    $(document).on('focusout', 'neo-component-material-text input', function (event) {
        $(this).parent().removeClass('active');
        value = $(this).val();
        if (value.length == 0) {
            $(this).parents('neo-component-material-text').removeClass('focus');
        }
    });
});