function materialSelectBoxIntegraid() {
    count = $('neo-component-material-select').length;
    x = 0;
    while (x < count) {
        el = $('neo-component-material-select').eq(x);
        data_variable = el.attr('data-variable');
        selectBoxMaker(el, window[data_variable]);
        x++;
    }
}

function selectBoxChecker(ele, value, change = false) {
    if (change) {
        $('#' + ele).val(value).change();
    } else {
        $('#' + ele).val(value);
    }
    fValue = $('#' + ele).find('*[value=' + value + ']').html();
    $('#' + ele).parents('neo-component-material-select').addClass('focus');
    $('#' + ele).parents('neo-component-material-select').find('label').html(fValue);
}

function makeSelectBoxByElement(id) {
    el = $('#' + id).parents('neo-component-material-select');
    data_variable = el.attr('data-variable');
    selectBoxMaker(el, window[data_variable]);
}

function selectBoxMaker(el, data) {
    selectValue = el.find('select').attr('value');
    str = '';
    strOption = '';
    newData = [];
    tx = 0;
    for (inVal in data) {
        newData[tx] = [inVal, data[inVal]];
        tx++;
    }
    newData.sort(function (a, b) {
        if (a[1] < b[1]) {
            return -1;
        }
        if (a[1] > b[1]) {
            return 1;
        }
        return 0;
    });

    for (val in newData) {
        value = newData[val][1];
        _index = newData[val][0];
        if (selectValue == _index) {
            el.find('label').html(value);
            el.addClass('focus');
            str += '            <li value="' + _index + '" class="active">' +
                '                <span class="prefix"></span>' +
                '                <p>' + value + '</p>' +
                '                <span class="postfix">' +
                '                    <i class="icon neo-navigation-checked"></i>' +
                '                </span>' +
                '            </li>';
        } else {
            str += '            <li value="' + _index + '">' +
                '                <span class="prefix"></span>' +
                '                <p>' + value + '</p>' +
                '                <span class="postfix">' +
                '                    <i class="icon neo-navigation-checked"></i>' +
                '                </span>' +
                '            </li>';
        }
    }
    for (val in data) {
        value = data[val];
        if (selectValue == val) {
            strOption += '<option value="' + val + '" selected>' + value + '</option>';
        } else {
            strOption += '<option value="' + val + '">' + value + '</option>';
        }
    }
    el.find('select').html(strOption);
    el.find('.list').find('ul').html(str);
}


function doUpdateChangeSelect(data) {
    return data;
}

function doUpdateChangeSelectRes(res, el, config) {
    if (res.result) {
        alert(res.message, 'success');
    } else {
        alert(res.message, 'danger');
    }
}
