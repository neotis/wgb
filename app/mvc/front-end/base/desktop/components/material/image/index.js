function onceload() {
    $(document).on('click', 'neo-component-material-image .uploader-image', function (event) {
        $(this).next().trigger('click');
    });
    $(document).on('change', 'neo-component-material-image input[type=file]', function (event) {
        $(this).neotis().upload(this);
    });
}
