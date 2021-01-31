$(document).ready(function () {
    ckeditoreleCount = $('neo-component-material-ckeditor textarea').length;
    ckeditorcounter = 0;
    while (ckeditorcounter < ckeditoreleCount) {
        eleid = $('neo-component-material-ckeditor textarea').eq(ckeditorcounter).attr('id');
        ckeditorcreator(eleid);
        content = '';
        //CKEDITOR.instances[eleid].setData();
        ckeditorcounter++;
    }
});
