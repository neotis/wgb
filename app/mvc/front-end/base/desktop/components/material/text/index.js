$(document).ready(function () {
    /**
     * Make active elements bu value
     * @type {number}
     */
    x = 0;
    length = $('neo-component-material-text').parents('body').find('neo-component-material-text').length;
    while(x < length){
        value = $('body').find('neo-component-material-text').eq(x).find('input').val();
        if(value.length > 0){
            $('body').find('neo-component-material-text').eq(x).addClass('focus');
            $('body').find('neo-component-material-text').eq(x).find('>div').addClass('active');
        }
        x++;
    }

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
