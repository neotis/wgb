/**
 * Store pages url and page id
 * @type {*[]}
 */
let neo_request_pages = [];

/**
 * Page id of requested url
 * @type {number}
 */
let neo_request_pageId = 0;

/**
 * Status of request page to rely request
 * @type {boolean}
 */
let neo_request_status = false;

/**
 * Store pages history types
 * @type {*[]}
 */
let neo_request_page_history = [];

/**
 * Let ajax complete to run script
 * @type {boolean}
 */
let ajaxCompleteStatus = 'page';

/**
 * Request queue
 * @type {boolean}
 */
let ajaxRequestQueue = true;

/**
 * Upload progress counter for make new progress element or not
 * @type {number}
 */
let uploadCounterDetect = 0;



function swipeLeft(el, func) {
    document.getElementById(el).addEventListener('touchstart', handleTouchStart, false);
    document.getElementById(el).addEventListener('touchmove', handleTouchMove, false);

    var xDown = null;
    var yDown = null;

    function getTouches(evt) {
        return evt.touches ||             // browser API
            evt.originalEvent.touches; // jQuery
    }

    function handleTouchStart(evt) {
        const firstTouch = getTouches(evt)[0];
        xDown = firstTouch.clientX;
        yDown = firstTouch.clientY;
    };

    function handleTouchMove(evt) {
        if ( ! xDown || ! yDown ) {
            return;
        }

        var xUp = evt.touches[0].clientX;
        var yUp = evt.touches[0].clientY;

        var xDiff = xDown - xUp;
        var yDiff = yDown - yUp;

        if ( Math.abs( xDiff ) > Math.abs( yDiff ) ) {/*most significant*/
            if ( xDiff > 0 ) {
                console.log('left');
            } else {
                console.log('right');
            }
        } else {
            if ( yDiff > 0 ) {
                /* up swipe */
            } else {
                /* down swipe */
            }
        }
        /* reset values */
        xDown = null;
        yDown = null;
    };
}


/**
 * Replace string by array
 * @param find
 * @param replace
 * @returns {String}
 */
String.prototype.replaceArray = function (find, replace) {
    var replaceString = this;
    var regex;
    for (var i = 0; i < find.length; i++) {
        regex = new RegExp(find[i], "g");
        replaceString = replaceString.replace(regex, replace[i]);
    }
    return replaceString;
};

function loadQueryString() {
    var parameters = {};
    var searchString = location.search.substr(1);
    var pairs = searchString.split("&");
    var parts;
    for (i = 0; i < pairs.length; i++) {
        parts = pairs[i].split("=");
        var name = parts[0];
        var data = decodeURI(parts[1]);
        parameters[name] = data;
    }
    return parameters;
}

/**
 * All function after document ready
 */
$(document).ready(function () {
    _url = location.pathname + location.search;
    neo_request_pageId++;
    neo_request_pages[_url] = [];
    neo_request_pages[_url]['id'] = neo_request_pageId;
    addState(_url, neo_request_pages[_url]['id'], 'عنوان');

    /* Change status of "a" Element */
    $("body").on('click', 'a', function (e) {
        neo_request_status = true;
        setTimeout(function () {
            neo_request_status = false;
        }, 1000);
    });

    /* Request page with selected element link */
    $("body").on('click', '*[request=dynamic]', function (e) {
        e.preventDefault();
        $(this).neotis().getPage();
        neo_request_status = true;
        setTimeout(function () {
            neo_request_status = false;
        }, 2000);
    });

    /* Request page with href link */
    $("body").on('submit', 'form[type=neotis]', function (e) {
        e.preventDefault();
        $(this).neotis().sendForm();
    });

    /* Display tool tip */
    $("body").on('mouseenter', '*[tool-tip=true]', function (e) {
        $(this).css('position', 'relative');

        _top = $(this).offset().top;
        _left = $(this).offset().left;
        _width = $(this).width();
        _height = $(this).height();

        _bg_color = $(this).attr('tool-tip-bgcolor');
        _color = $(this).attr('tool-tip-color');

        _message = $(this).attr('tool-tip-message');
        _str = '<div class="tool-tip">\n' +
            '    <div class="holder">\n' +
            '        <span></span>\n' +
            '        <p>' + _message + '</p>\n' +
            '    </div>\n' +
            '</div>';
        $('body').append(_str);
        _tooltip_width = ($('.tool-tip').width() / 2);
        $('.tool-tip').css('top', _top + _height + 'px');
        $('.tool-tip').css('left', (_left - _tooltip_width) + 'px');
        if (_bg_color != undefined) {
            $('.tool-tip').css('background-color', _bg_color);
            $('.tool-tip span').css('border-bottom-color', _bg_color);
        }

        if (_color != undefined) {
            $('.tool-tip').css('color', _color);
        }
    });
    /* Display tool tip */
    $("body").on('click', '*', function (e) {
        $('.tool-tip').remove();
    });

    /* Remove tool tip */
    $("body").on('mouseleave', '*[tool-tip=true]', function (e) {
        $('.tool-tip').remove();
    });
});

/**
 * Append state
 * @param url
 * @param id
 * @param title
 */
function addState(url, id, title) {
    let stateObj = {id: id};
    window.history.pushState(stateObj,
        title, url);
}

/**
 * Dynamic calling error function
 * @private
 */
function _caller(name, xhr) {
    name = name.toLowerCase();
    if (typeof window[name] != 'undefined') {
        window[name](xhr);
    }
}

/**
 * Trigger next and back browser event for change content
 */
window.addEventListener('popstate', function (event) {
    _url = location.pathname + location.search;
    if (!neo_request_status) {
        if (neo_request_page_history[_url] !== undefined) {
            if (neo_request_page_history[_url]['type'] == 'component' && neo_request_page_history[_url]['holder'] == undefined) {
                aElement = $('<a back-status="true" request-type="' + neo_request_page_history[_url]['type'] + '" request="dynamic" target-name="' + neo_request_page_history[_url]['holder'] + '" header="component" href="' + _url + '"></a>');
            } else if (neo_request_page_history[_url]['target'] !== undefined) {
                aElement = $('<a back-status="true" request-type="' + neo_request_page_history[_url]['type'] + '" request="dynamic" target-name="' + neo_request_page_history[_url]['target'] + '" header="partial" href="' + _url + '"></a>');
            } else {
                aElement = $('<a back-status="true" request-type="' + neo_request_page_history[_url]['type'] + '" request="dynamic" target-name="action" header="partial" href="' + _url + '"></a>');
            }
        } else {
            aElement = $('<a back-status="true" request-type="action" request="dynamic" target-name="action" header="partial" href="' + _url + '"></a>');
        }

        aElement.neotis().getPage();
    }
});

/**
 * Infinite page loader
 */
function infiniteLoader(type) {
    if (type === 'deActive') {
        $('neo-component-infiniteloader').removeClass('active');
        if (typeof window['customDeActiveAjaxLoader']() !== 'undefined') {
            window['customActiveAjaxLoader']();
        }
    } else {
        $('neo-component-infiniteloader').addClass('active');
        if (typeof window['customActiveAjaxLoader']() !== 'undefined') {
            window['customActiveAjaxLoader']();
        }
    }
}

/**
 * Default functions
 */
(function ($) {

    /**
     *  to create custom function in jQuery
     * @function neoForm
     * @returns this
     */
    $.fn.neotis = function () {
        return this;
    };

    /**
     * Define default page for display to user by current event
     * @type {{}}
     */
    $.fn.neotis.defaultPages = {
        '401': 'Forbidden!',
        '403': 'Access denied!',
        '404': 'Not found!',
        '500': 'Error!'
    };

    /**
     * Fetch data from form section and calculate data
     */
    $.fn.neotis.formData = function (ele) {
        data = {};
        // language=JQuery-CSS
        count = ele.find('input').length;
        x = 0;
        while (x < count) {
            data[x] = {};
            // language=JQuery-CSS
            type = ele.find('input').eq(x).attr('type');
            if (type === 'checkbox') {
                if (ele.find('input').eq(x).is(':checked')) {
                    // language=JQuery-CSS
                    inName = ele.find('input').eq(x).attr('name');

                    // language=JQuery-CSS
                    value = ele.find('input').eq(x).val();
                    if (value.length > 0) {
                        data[x][inName] = value;
                    }
                }
            } else if (type === 'text' || type === 'password' || type === 'hidden' || type === 'date' || type === 'email' || type === 'tel') {
                // language=JQuery-CSS
                inName = ele.find('input').eq(x).attr('name');

                // language=JQuery-CSS
                value = ele.find('input').eq(x).val();
                if (value.length > 0) {
                    data[x][inName] = value;
                }
            }
            x++;
        }

        // language=JQuery-CSS
        count = ele.find('textarea').length;
        t = 0;
        while (t < count) {
            data[x] = {};
            inName = ele.find('textarea').eq(t).attr('name');
            value = ele.find('textarea').eq(t).val();
            type = ele.find('textarea').eq(t).attr('type');
            id = ele.find('textarea').eq(t).attr('id');
            if (type === 'tinymce') {
                content = tinymce.get(id).getContent();
                if (content.length > 0) {
                    data[t][inName] = encodeURIComponent(content);
                }
            } else if (type === 'ckeditor') {
                content = CKEDITOR.instances[id].getData();
                data[t][inName] = encodeURIComponent(content);
            } else {
                if (value.length > 0) {
                    data[t][inName] = value;
                }
            }
            t++;
            x++;
        }

        // language=JQuery-CSS
        count = ele.find('select').length;
        y = 0;
        while (y < count) {
            data[x] = {};
            // language=JQuery-CSS
            // language=JQuery-CSS
            inName = ele.find('select').eq(y).attr('name');

            // language=JQuery-CSS
            value = ele.find('select').eq(y).val();
            if (value != null && value != '0' && value != '-10000') {
                data[x][inName] = value;
            }
            y++;
            x++;
        }

        finalString = '';
        prefix = '';
        for (i in data) {
            for (t in data[i]) {
                finalString += prefix + t + '=' + data[i][t];
                prefix = '&';
            }
        }
        return finalString;
    };

    /**
     * Get single page with ajax request
     * @param url
     */
    $.fn.neotis().getPage = function (config = '') {
        if (ajaxRequestQueue) {
            ajaxRequestQueue = false;
            ajaxCompleteStatus = 'page';
            url = $(this).attr('href');
            holder = $(this).attr('target-name');
            type = $(this).attr('request-type');
            backStatus = $(this).attr('back-status');

            if (config['href'] !== undefined) {
                url = config['href'];
                holder = config['target-name'];
                type = config['request-type'];
                backStatus = config['back-status'];
            }

            neo_request_page_history[url] = {};
            neo_request_page_history[url]['type'] = type;
            neo_request_page_history[url]['holder'] = holder;

            if (type === 'action') {
                neo_request_pageId++;
                neo_request_pages[url] = [];
                neo_request_pages[url]['id'] = neo_request_pageId;
            } else if (type === 'component') {
                neo_request_pageId++;
                neo_request_pages[url] = [];
                neo_request_pages[url]['id'] = neo_request_pageId;
            }

            requestHeader = $(this).attr('header');
            callBack = $(this).attr('callback');

            if (config['header'] !== undefined) {
                requestHeader = config['header'];
                callBack = config['callback'];
            }

            headers = {};
            if (requestHeader !== undefined) {
                headers = {
                    'Request-Type': requestHeader
                };
            }

            $.ajax({
                type: 'GET',
                url: url,
                data: [],
                datatype: "html",
                headers: headers,
                statusCode: {
                    200: function () {
                        ajaxRequestQueue = true;
                        ajaxCompleteStatus = 'page';
                    },
                    404: function (xhr) {
                        ajaxRequestQueue = true;
                        infiniteLoader('deActive');
                        _caller('get_404', xhr);
                        alert([
                            $(this).neotis.defaultPages['404'], 'The requested page is not found in this package!'
                        ]);
                    },
                    401: function (xhr) {
                        ajaxRequestQueue = true;
                        infiniteLoader('deActive');
                        _caller('get_401', xhr);
                        alert([
                            $(this).neotis.defaultPages['401'], 'The request has not been applied because it lacks valid authentication credentials for the target resource.'
                        ]);
                    },
                    403: function (xhr) {
                        ajaxRequestQueue = true;
                        infiniteLoader('deActive');
                        _caller('get_403', xhr);
                        alert([
                            $(this).neotis.defaultPages['403'], 'You don\'t have right access to this page'
                        ])
                    },
                    500: function (xhr) {
                        ajaxRequestQueue = true;
                        infiniteLoader('deActive');
                        _caller('get_500', xhr);
                        alert([
                            $(this).neotis.defaultPages['500'], 'Internal Server Error'
                        ]);
                        window['getError500'](url);
                    }
                },
                xhr: function () {
                    var xhr = $.ajaxSettings.xhr();
                    infiniteLoader('active');
                    return xhr;
                },
                success: function (response, textStatus, request) {
                    ajaxRequestQueue = true;
                    ajaxCompleteStatus = 'page';
                    if (backStatus === undefined) {
                        addState(url, neo_request_pages[url]['id'], request.getResponseHeader('Page-Title'));
                    }

                    window.scrollTo(0, 0);

                    infiniteLoader('deActive');
                    if (holder === 'action') {
                        $('*[type=action]').html(response);
                        $('title').html(decodeURIComponent(escape(request.getResponseHeader('Page-Title'))));
                    } else {
                        $('#' + holder).html(response);
                    }

                    if (callBack !== undefined) {
                        window[callBack](response, textStatus, request);
                    }
                }
            });
        } else {
            var el = this;
            var configRe = data;
            setTimeout(function () {
                $(el).neotis().formData(configRe);
            }, 10);
        }
    };

    /**
     * Send information with ajax
     * @param data
     */
    $.fn.neotis().sendForm = function (data = '') {
        if (ajaxRequestQueue) {
            ajaxRequestQueue = false;
            method = $(this).attr('method');
            action = $(this).attr('action');
            sectionName = $(this).attr('name');
            response = $(this).attr('response');
            requestType = $(this).attr('request-type');
            htmlRequest = $(this).attr('html-request');
            target = $(this).attr('target');
            section = $(this).parents('*[type=component]').attr('section');

            if (data.length < 1) {
                data = $(this).neotis.formData($(this));
            }

            data = window[sectionName](data, this);
            if (method.toLowerCase() === 'get') {
                url = action + '?' + data;
                neo_request_page_history[url] = {};
                neo_request_page_history[url]['type'] = 'partial';
                neo_request_page_history[url]['target'] = target;
                neo_request_pageId++;
                neo_request_pages[url] = [];
                neo_request_pages[url]['id'] = neo_request_pageId;
            }
            if (data || data === '') {
                headers = {};
                if (requestType === 'partial') {
                    headers['Request-Type'] = 'partial';
                }
                if (requestType === 'component') {
                    headers['Request-Type'] = 'component';
                }
                if (htmlRequest === 'pure') {
                    headers['Html-Request'] = 'pure';
                }
                if (response === 'json') {
                    headers['Response-Type'] = 'application/json';
                }

                $.ajax({
                    url: action,          // Url to which the request is send
                    type: method,             // Type of request to be send, called as method
                    async: true,
                    data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    headers: headers,
                    processData: false,        // To send DOMDocument or non processed data file it is set to false
                    statusCode: {
                        404: function (xhr) {
                            ajaxRequestQueue = true;
                            infiniteLoader('deActive');
                            _caller(method + '_404', xhr);
                            alert([
                                $(this).neotis.defaultPages['404'], 'The requested page is not found in this package!'
                            ]);
                        },
                        401: function (xhr) {
                            ajaxRequestQueue = true;
                            infiniteLoader('deActive');
                            _caller(method + '_401', xhr);
                            alert([
                                $(this).neotis.defaultPages['401'], 'The request has not been applied because it lacks valid authentication credentials for the target resource.'
                            ]);
                        },
                        403: function (xhr) {
                            ajaxRequestQueue = true;
                            infiniteLoader('deActive');
                            _caller(method + '_403', xhr);
                            alert([
                                $(this).neotis.defaultPages['403'], 'You don\'t have right access to this page'
                            ]);
                        },
                        500: function (xhr) {
                            ajaxRequestQueue = true;
                            infiniteLoader('deActive');
                            _caller(method + '_500', xhr);
                            alert([
                                $(this).neotis.defaultPages['500'], 'Internal Server Error'
                            ]);
                        }
                    },
                    xhr: function () {
                        var xhr = $.ajaxSettings.xhr();
                        infiniteLoader('active');
                        return xhr;
                    },
                    success: function (response, textStatus, request) {
                        ajaxRequestQueue = true;
                        ajaxCompleteStatus = 'information';
                        infiniteLoader('deActive');
                        if (method.toLowerCase() === 'get' && target !== undefined) {
                            addState(url, neo_request_pages[url]['id'], request.getResponseHeader('Page-Title'));
                        }
                        window[sectionName + 'Res'](response, this, request)
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText
                        console.log('Error - ' + errorMessage);
                    }
                });
            }
        } else {
            var el = this;
            var dataRe = data;
            setTimeout(function () {
                $(el).neotis().sendForm(dataRe);
            }, 10);
        }
    };

    /**
     * Send information with ajax
     * @param config
     * @param data
     */
    $.fn.neotis().sendFormIndependently = function (config, data) {
        if (ajaxRequestQueue) {
            ajaxRequestQueue = false;
            var out = [];
            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    out.push(key + '=' + encodeURIComponent(data[key]));
                }
            }

            method = config['method'];
            action = config['action'];
            sectionName = config['name'];
            response = config['response'];
            requestType = config['request-type'];
            htmlRequest = config['html-request'];
            loaderStatus = config['loader-status'];
            target = config['target'];
            data = out.join('&');
            data = window[sectionName](data, this);
            /*if (method.toLowerCase() === 'get') {
                url = action + '?' + data;
                neo_request_page_history[url] = {};
                neo_request_page_history[url]['type'] = 'partial';
                neo_request_page_history[url]['target'] = target;
                neo_request_pageId++;
                neo_request_pages[url] = [];
                neo_request_pages[url]['id'] = neo_request_pageId;
            }*/
            if (data) {
                headers = {};
                if (requestType === 'partial') {
                    headers['Request-Type'] = 'partial';
                }
                if (requestType === 'component') {
                    headers['Request-Type'] = 'component';
                }
                if (htmlRequest === 'pure') {
                    headers['Html-Request'] = 'pure';
                }
                if (response === 'json') {
                    headers['Response-Type'] = 'application/json';
                }

                $.ajax({
                    url: action,          // Url to which the request is send
                    type: method,             // Type of request to be send, called as method
                    async: true,
                    data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    headers: headers,
                    processData: false,        // To send DOMDocument or non processed data file it is set to false
                    statusCode: {
                        404: function (xhr) {
                            ajaxRequestQueue = true;
                            infiniteLoader('deActive');
                            _caller(method + '_404', xhr);
                            alert([
                                $(this).neotis.defaultPages['404'], 'The requested page is not found in this package!'
                            ]);
                        },
                        401: function (xhr) {
                            ajaxRequestQueue = true;
                            infiniteLoader('deActive');
                            _caller(method + '_401', xhr);
                            alert([
                                $(this).neotis.defaultPages['401'], 'The request has not been applied because it lacks valid authentication credentials for the target resource.'
                            ]);
                        },
                        403: function (xhr) {
                            ajaxRequestQueue = true;
                            infiniteLoader('deActive');
                            _caller(method + '_403', xhr);
                            alert([
                                $(this).neotis.defaultPages['403'], 'You don\'t have right access to this page'
                            ]);
                        },
                        500: function (xhr) {
                            ajaxRequestQueue = true;
                            infiniteLoader('deActive');
                            _caller(method + '_500', xhr);
                            alert([
                                $(this).neotis.defaultPages['500'], 'Internal Server Error'
                            ]);
                        }
                    },
                    xhr: function () {
                        var xhr = $.ajaxSettings.xhr();
                        if (loaderStatus != 'false') {
                            infiniteLoader('active');
                        }
                        return xhr;
                    },
                    success: function (response, textStatus, request) {
                        ajaxRequestQueue = true;
                        ajaxCompleteStatus = 'information';
                        infiniteLoader('deActive');
                        /*if (method.toLowerCase() === 'get') {
                            addState(url, neo_request_pages[url]['id'], request.getResponseHeader('Page-Title'));
                        }*/
                        window[sectionName + 'Res'](response, this, config);
                    }
                });
            }
        } else {
            var el = this;
            var configRe = config;
            var dataRe = data;
            setTimeout(function () {
                $(el).neotis().sendFormIndependently(configRe, dataRe);
            }, 10);
        }
    };

    /**
     * Upload form elements or other single upload elements
     */
    $.fn.neotis().upload = function (el) {
        uploadCounterDetect++;
        ajaxCompleteStatus = 'information';
        var formData = new FormData();
        elName = $(el).attr('name');
        path = $(el).attr('path');
        progress = $(el).attr('progress');
        responser = $(el).attr('response');

        formData.append(elName, $(el)[0].files[0]);

        $.ajax({
            url: path,
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            mimeType: "multipart/form-data",
            headers: {"Response-Type": "application/json"},
            statusCode: {
                404: function () {
                    infiniteLoader('deActive');
                    alert([
                        $(this).neotis.defaultPages['404'], 'The requested page is not found in this package!'
                    ]);
                },
                401: function () {
                    infiniteLoader('deActive');
                    alert([
                        $(this).neotis.defaultPages['401'], 'The request has not been applied because it lacks valid authentication credentials for the target resource.'
                    ]);
                },
                403: function () {
                    infiniteLoader('deActive');
                    alert([
                        $(this).neotis.defaultPages['403'], 'You don\'t have right access to this page'
                    ]);
                },
                500: function () {
                    infiniteLoader('deActive');
                    alert([
                        $(this).neotis.defaultPages['500'], 'Internal Server Error'
                    ]);
                }
            },
            xhr: function () {
                //upload Progress
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function (event) {
                        window[progress + 'Progress'](event.loaded, event.position, event.total, event.lengthComputable, uploadCounterDetect);
                    }, true);
                }
                return xhr;
            },
            success: function (data) {
                var obj = JSON.parse(data);
                window[responser](el, obj);
            }
        })
    }
}(jQuery));
