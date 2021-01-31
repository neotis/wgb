(function ($) {

    /**
     * Store initial elements innner of orbital slider
     * @type {string}
     */
    let orbitalLiItems = [];

    /**
     * Basic config
     * @type {*[]}
     */
    $.fn.config = [];

    /**
     * Config and values after initial slide
     * @type {*[]}
     */
    $.fn.calculatedConfig = [];

    /**
     * Slide positions value
     * @type {number}
     */
    $.fn.slide = [];

    /**
     *  to create custom function in jQuery
     * @function neotis slider orbital
     * @returns this
     */
    $.fn.neoslider = function () {
        return this;
    };

    /**
     * Config and display orbital sliders
     * @param config
     */
    $.fn.neoslider().orbital = function (config) {
        myId = $(this).attr('id');
        orbitalLiItems[myId] = [];

        $(this).find('>li').css('flex-shrink', '0');
        liCount = $(this).find('>li').length;
        marginLeft = $(this).find('>li').css('marginLeft');
        marginLeft = parseInt(marginLeft);
        marginRight = $(this).find('>li').css('marginRight');
        marginRight = parseInt(marginRight);
        liWidth = $(this).find('>li').width();
        finalLiWidth = liWidth + marginLeft + marginRight;
        holderWidth = $(this).width();

        finalWidth = holderWidth / finalLiWidth;
        finalWidth = Math.floor(finalWidth);
        finalWidth = finalWidth * finalLiWidth;

        $(this).css('width', finalWidth + 'px');

        $(this).calculatedConfig[myId] = [];
        $(this).calculatedConfig[myId]['liCount'] = liCount;
        $(this).calculatedConfig[myId]['marginLeft'] = marginLeft;
        $(this).calculatedConfig[myId]['marginRight'] = marginRight;
        $(this).calculatedConfig[myId]['liWidth'] = liWidth;
        $(this).calculatedConfig[myId]['finalLiWidth'] = finalLiWidth;
        $(this).calculatedConfig[myId]['holderWidth'] = holderWidth;

        if (orbitalLiItems[myId].length < 1) {
            productsLi = $(this).html();
            orbitalLiItems[myId] = productsLi;
            $(this).html(orbitalLiItems[myId]);
        } else {
            $(this).html(orbitalLiItems[myId]);
        }

        $(this).prepend(productsLi);
        $(this).append(productsLi);

        $(this).slide['right-allow'] = true;
        $(this).slide['left-allow'] = true;

        $(this).css('overflow', 'hidden');
        $(this).find('>li').css('position', 'relative');
    };

    /**
     * Config and display orbital sliders
     * @param config
     */
    $.fn.neoslider().flat = function (config) {
        myId = $(this).attr('id');
        orbitalLiItems[myId] = [];
        $(this).find('>li').css('flex-shrink', '0');
        liCount = $(this).find('>li').length;
        marginLeft = $(this).find('>li').css('marginLeft');
        marginLeft = parseInt(marginLeft);
        marginRight = $(this).find('>li').css('marginRight');
        marginRight = parseInt(marginRight);
        liWidth = $(this).find('>li').width();
        finalLiWidth = liWidth + marginLeft + marginRight;
        holderWidth = $(this).width();

        finalWidth = holderWidth / finalLiWidth;
        finalWidth = Math.floor(finalWidth);
        finalWidth = finalWidth * finalLiWidth;

        if(typeof config === "object"){
            if(config['width'] === "full"){
                $(this).css('width', '100%');
            }
        }else{
            $(this).css('width', finalWidth + 'px');
        }

        $(this).calculatedConfig[myId] = [];
        $(this).calculatedConfig[myId]['liCount'] = liCount;
        $(this).calculatedConfig[myId]['marginLeft'] = marginLeft;
        $(this).calculatedConfig[myId]['marginRight'] = marginRight;
        $(this).calculatedConfig[myId]['liWidth'] = liWidth;
        $(this).calculatedConfig[myId]['finalLiWidth'] = finalLiWidth;
        $(this).calculatedConfig[myId]['holderWidth'] = holderWidth;

        $(this).slide['right-allow'] = true;
        $(this).slide['left-allow'] = true;

        $(this).slide[myId] = [];
        $(this).slide[myId]['left'] = 0;
        $(this).slide[myId]['left-reference'] = $(this).find('>li:first-child').position().left;

        $(this).css('overflow', 'hidden');
        $(this).find('>li').css('position', 'relative');
    };

    /**
     * Slide orbital slider to right
     * @param config
     */
    $.fn.neoslider().slideToRight = function () {
        let el = this;
        myid = $(el).attr('id');
        if ($(el).slide['right-allow']) {
            $(el).slide['right-allow'] = false;
            $(el).find('>li').animate({right: $(el).calculatedConfig[myid]['finalLiWidth'] + 'px'}, 250, function () {
                $(el).find('>li').css('right', 0);
                $(this).slide['right-allow'] = true;
            });
            setTimeout(function(){
                lastLi = $(el).find('>li:last-child').wrap('<p/>').parent().html();
                $(el).find('>p li').unwrap();
                $(el).prepend(lastLi);
                $(el).find('>li:last-child').remove();
            }, 250);
        }
    };

    /**
     * Slide orbital slider to left
     * @param config
     */
    $.fn.neoslider().slideToLeft = function () {
        let el = this;
        myid = $(el).attr('id');
        if ($(el).slide['left-allow']) {
            $(el).slide['left-allow'] = false;
            $(el).find('>li').animate({right: '-' + $(el).calculatedConfig[myid]['finalLiWidth'] + 'px'}, 250, function () {
                $(el).find('>li').css('right', 0);
                $(this).slide['left-allow'] = true;
            });
            setTimeout(function(){
                lastLi = $(el).find('>li:first-child').wrap('<p/>').parent().html();
                $(el).find('>p li').unwrap();
                $(el).append(lastLi);
                $(el).find('>li:first-child').remove();
            }, 250);
        }
    };

    /**
     * Slide orbital slider to left
     * @param config
     */
    $.fn.neoslider().slideTo = function (Step) {
        let el = this;
        myid = $(el).attr('id');
        leftPosition = $(el).find('.step-' + Step).position().left;
        if (leftPosition < $(this).slide[myid]['left-reference']) {
            change = Math.abs(leftPosition);
            $(this).slide[myid]['left'] += (change + $(this).slide[myid]['left-reference']);
            $(el).find('>li').animate({left: ($(this).slide[myid]['left']) + 'px'}, 250);
        } else {
            change = Math.abs(leftPosition);
            $(this).slide[myid]['left'] -= (change - $(this).slide[myid]['left-reference']);
            $(el).find('>li').animate({left: ($(this).slide[myid]['left']) + 'px'}, 250);
        }
        /*$(el).find('>li').animate({left: '-' + $(el).calculatedConfig[myid]['finalLiWidth'] + 'px'}, 250, function () {

        });*/
    };
}(jQuery));
