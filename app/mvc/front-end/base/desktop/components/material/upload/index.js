function onceload() {
    dragEl = 'neo-component-material-upload>.before';
    $(document).on('dragenter', dragEl, function (event) {
        $(this).parents('neo-component-material-upload').addClass('ondragenter');
    });
    $(document).on('dragleave', dragEl, function (event) {
        $(this).parents('neo-component-material-upload').removeClass('ondragenter');
    });
    $(document).on('drop', dragEl, function (event) {
        event.preventDefault();
        event.stopPropagation();
        files = event.originalEvent.dataTransfer.files;
        for (index in files) {
            //console.log(files[index]);
        }

        //$(this).neotis().sendForm(data);
    });
    $(document).on('dragover', dragEl, function (event) {
        event.preventDefault();
        event.stopPropagation();
    });

    $(document).on('click', dragEl, function (event) {
        $(this).next().trigger('click');
    });
}
