function onceload() {
    selectBoxChecker('neo-filter_stock', 1);
    $(document).ready(function () {
        let selectboxeventstatus = [];
        let selectBoxCounter = 0;
        let scrollBodyStatus = true;
        let scrollPositionSelectBox = 0;
        let scrollPositionSelectBoxCount = 0;
        let selectBoxDataTitle = [];
        let multiple = false;
        function selectBoxTitleMaker(el) {
            _name = el.attr('id');
            title = '';
            prefix = '';
            for (var key in selectBoxDataTitle[_name]) {
                title += prefix + selectBoxDataTitle[_name][key];
                prefix = ' , ';
            }
            el.parents('neo-component-material-select').find('label').html(title);
        }

        function selectboxlistmaker(el, display) {
            $(el).parent().find('li').removeClass('focus');
            $('neo-component-material-select .list').hide();
            width = $(el).width() + 10;
            $(el).parent().find('.list').css('width', width + 'px');
            $(el).parent().find('input').focus();
            if (display === 'show') {
                scrollBodyStatus = false;
                selectBoxCounter = 0;
                scrollPositionSelectBox = 0;
                scrollPositionSelectBoxCount = 0;
                $(el).parent().find('.list').show();
            } else {
                scrollBodyStatus = true;
                selectBoxCounter = 0;
                scrollPositionSelectBox = 0;
                scrollPositionSelectBoxCount = 0;
                $(el).parent().find('.list').hide();
            }
        }

        function selectBoxTitlesMaker(value, title) {
            if (multiple) {
                if (Array.isArray(selectBoxDataTitle['neo-' + selectid])) {
                    selectBoxDataTitle['neo-' + selectid][value] = title;
                } else {
                    selectBoxDataTitle['neo-' + selectid] = [];
                    selectBoxDataTitle['neo-' + selectid][value] = title;
                }
            } else {
                selectBoxDataTitle['neo-' + selectid] = [];
                selectBoxDataTitle['neo-' + selectid][value] = title;
            }
        }

        function selectBoxItemSelector(el, type) {
            selectid = $(el).parents('neo-component-material-select').find('select').attr('id');
            if (type === 'focus') {
                value = $(el).parents('neo-component-material-select').find('.list').find('li.focus').attr('value');
                $(el).parents('neo-component-material-select').find('.list').find('li').removeClass('active');
                $(el).parents('neo-component-material-select').find('.list').find('li.focus').addClass('active');
                title = $(el).parents('neo-component-material-select').find('.list').find('li.focus').find('p').html();
                $(el).parents('neo-component-material-select').find('.list').hide(0);
            } else if (type === 'click') {
                value = $(el).attr('value');
                $(el).parents('neo-component-material-select').find('.list').find('li').removeClass('active');
                $(el).addClass('active');
                title = $(el).find('p').html();
                $(el).parents('neo-component-material-select').find('.list').hide(0);
            }
            $(el).parents('neo-component-material-select').addClass('focus');
            $(el).parents('neo-component-material-select').find('label').html(title);
            selectBoxTitlesMaker(value, title);
            $('#' + selectid).val(value).change();
            selectBoxTitleMaker($('#neo-' + selectid));
        }

        //Search in array
        function selectBoxSearchArray(Arr, Val) {
            var searchResult = [];
            Val = Val.toLowerCase();
            for (var key in Arr) {
                string = Arr[key];
                if (string !== null) {
                    string = string.toString();
                    string = string.toLowerCase();
                    if (string.search(Val) >= 0) {
                        searchResult[key] = Arr[key];
                    }
                }
            }
            return searchResult;
        }

        function selectBoxDataMaker(el, array) {
            $(el).parents('neo-component-material-select').find('ul').find('li').hide();
            for (var key in array) {
                $(el).parents('neo-component-material-select').find('ul').find('li[value=' + key + ']').show();
            }
        }

        $(document).click(function (event) {
            scrollBodyStatus = true;
            $('neo-component-material-select .list').hide();
        });

        $(document).on('keydown', 'body', function (event) {
            if (scrollBodyStatus === false && (event.which === 40 || event.which === 38)) {
                event.preventDefault();
            }
        });

        $(document).on('focusin', 'neo-component-material-select .text-input-holder', function (event) {
            id = $(this).parent().attr('id');
            if (selectboxeventstatus[id] === undefined || selectboxeventstatus[id] === false) {
                width = $(this).width();
                width = width + 10;
                selectboxeventstatus[id] = true;
                $(this).parent().addClass('focus');
                setTimeout(function () {
                    selectboxeventstatus[id] = false;
                }, 100);
            }
        });

        $(document).on('click', 'neo-component-material-select .list, neo-component-material-select .text-input-holder', function (event) {
            event.stopPropagation();
        });

        $(document).on('click', 'neo-component-material-select .text-input-holder', function (event) {
            selectboxlistmaker(this, 'show')
        });

        $(document).on('keydown', 'neo-component-material-select .text-input-holder', function (event) {
            if (event.which === 40) {
                selectboxlistmaker(this, 'show')
            }
        });

        $(document).on('keydown', 'neo-component-material-select .list input', function (event) {
            if (event.which === 40) {
                $(this).parent().find('li:first-child').focusin();
            }
        });

        $(document).on('keyup', 'neo-component-material-select .list input', function (event) {
            value = $(this).val();
            _name = $(this).parents('neo-component-material-select').attr('data-variable');
            newarray = selectBoxSearchArray(window[_name], value);
            selectBoxDataMaker(this, newarray);
        });


        $(document).on('keyup', 'neo-component-material-select .list ul', function (event) {
            if (event.which === 13) {
                selectBoxItemSelector(this, 'focus');
            }
        });

        $(document).on('click', 'neo-component-material-select .list ul li', function (event) {
            selectBoxItemSelector(this, 'click');
        });

        $(document).on('focusout', 'neo-component-material-select .text-input-holder', function (event) {
            if (selectboxeventstatus) {
                width = $(this).width();
                selectboxeventstatus = false;
                $(this).parent().removeClass('focus');
                setTimeout(function () {
                    selectboxeventstatus = true;
                }, 100);
            }
        });

        $(document).on('keydown', 'neo-component-material-select .list ul', function (event) {
            uiPos = $(this).offset().top;
            uiHeight = $(this).height();
            liHeight = $(this).find('li').height();
            if (event.which === 40) {
                scrollPositionSelectBoxCount++;

                if ((scrollPositionSelectBoxCount * liHeight) > (uiHeight - 80)) {
                    scrollPositionSelectBox += 40;
                }

                if ((scrollPositionSelectBoxCount * liHeight) > $(this).find('li').length * liHeight) {
                    scrollPositionSelectBox -= 40;
                    scrollPositionSelectBoxCount--;
                }

                $(this).scrollTop(scrollPositionSelectBox);

                $(this).find('li').removeClass('focus');
                $(this).find('li').eq(selectBoxCounter).addClass('focus');

                selectBoxCounter++;
                if (selectBoxCounter > ($(this).find('li').length - 1)) {
                    selectBoxCounter = $(this).find('li').length - 1;
                }

            } else if (event.which === 38) {
                scrollPositionSelectBoxCount--;
                if (scrollPositionSelectBox > 0) {
                    scrollPositionSelectBox -= 40;
                }

                if (scrollPositionSelectBoxCount < 0) {
                    scrollPositionSelectBox = 0;
                    scrollPositionSelectBoxCount = 0;
                }

                $(this).scrollTop(scrollPositionSelectBox);

                selectBoxCounter--;
                if (selectBoxCounter < 0) {
                    selectBoxCounter = 0;
                }

                $(this).find('li').removeClass('focus');
                $(this).find('li').eq(selectBoxCounter).addClass('focus');
            }
        });



        $(document).on('change', 'select[change-type=true]', function (event) {
            path = $(this).attr('change-path');
            _name = $(this).attr('name');
            _value = $(this).val();
            object = [];
            object['name'] = _name;
            object['value'] = _value;
            config = [];
            config['method'] = 'PUT';
            config['action'] = path;
            config['name'] = 'doUpdateChangeSelect';
            config['response'] = 'json';
            config['el'] = el;
            $(this).neotis().sendFormIndependently(config, object);
        });
    });
}
materialSelectBoxIntegraid();
