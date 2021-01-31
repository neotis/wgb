var progressArrayCount = [];
function progressLoader(lengthComputable, position, total, loaded, counter) {
    var percent = 0;
    var position = loaded || position;

    if (event.lengthComputable) {
        percent = Math.ceil(position / total * 100);
    }

    if(progressArrayCount[counter] == undefined){
        str = '<div class="process loader-count-'+counter+'"><div class="load" style="width: '+percent+'%;">'+percent+'%</div></div>';
        $('neo-component-processloader').append(str);
        $('neo-component-processloader').show();
        progressArrayCount[counter] = true;
    }else{
        $('.loader-count-'+counter).find('.load').html(percent+'%');
        $('.loader-count-'+counter).find('.load').css('width', percent+'%');
    }

    if(percent >= 100){
        $('.loader-count-'+counter).remove();
    }
}
