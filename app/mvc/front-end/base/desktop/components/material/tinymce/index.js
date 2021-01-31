type = typeof tinymce;
if(type !== 'undefined'){
    tinymce.remove();
}
$(document).ready(function () {
    eleCount = $('neo-component-material-tinymce textarea').length;
    counter = 0;
    while (counter < eleCount) {
        eleid = $('neo-component-material-tinymce textarea').eq(counter).attr('id');
        tinymcecreator(eleid);
        counter++;
    }
});
