(function ($) {
    $.fn.styleulli = function (options) {

        // plugin's default options
        var plugin = this;
        var defaults = {
            dropdownMenu: {
                animation: true, //animation effect for dropdown
                openEffect: 'flipInY',//open effect for menu see http://daneden.github.io/animate.css/
            }

        }

        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);
            //activate quicksearch plugin

        }


        plugin.init();
        var nav =plugin.settings.ulname.ulname;
        var navSub = $(nav).find('li>ul.sub');
        var navLink = $(nav).find('a');

        if(plugin.settings.params.showtotal != 'never') {
            if(plugin.settings.params.showtotal == 'always') {
                navSub.each(function(){
                    subItems = $(this).find('li').length;
                    if(!$(this).prev('a').find('span.mynotification').length){
                        $(this).prev('a').append('<span class="mynotification red">' + subItems + '</span>');
                    }
                })
            } else {
                navSub.each(function(){
                    subItems = $(this).find('li').length;
                    if(!$(this).prev('a').find('span.mynotification').length){
                        $(this).prev('a').append('<span class="mynotification onhover red">' + subItems + '</span>');
                    }
                })
            }
        }



        navLink.on("click", function(e){

            var _this = $(this);
            if(_this.hasClass('notExpand')) {
                e.preventDefault();
                //expand ul and change class to expand
                //_this.next('ul').slideDown(plugin.settings.sideNav.subOpenSpeed, plugin.settings.sideNav.animationEasing);
                _this.next('ul').addClass('show');
                _this.addClass('expand').removeClass('notExpand');
                if(plugin.settings.params.showArrows=='yes') {
                    _this.find('.moduleMenu-arrow').transition({rotate: '-180deg'});
                }
            } else if (_this.hasClass('expand')) {
                e.preventDefault();
                //collapse ul and change class to notExpand
                _this.next('ul').removeClass('show');
                //_this.next('ul').slideUp(plugin.settings.sideNav.subCloseSpeed, plugin.settings.sideNav.animationEasing);
                _this.addClass('notExpand').removeClass('expand');
                if(plugin.settings.params.showArrows=='yes') {
                    _this.find('.moduleMenu-arrow').transition({rotate: '0deg'});
                }
            }
        });

        if(plugin.settings.params.showArrows=='yes') {
            if(!navSub.prev('a').find('i.sideNav-arrow').length) {
                navSub.prev('a').prepend('<i class="en-arrow-down5 moduleMenu-arrow"></i>');
            }
        }

        //quick search pluign
        plugin.quickSearch = function () {

            //quick search on sideNav
            if ($('.moduleMenu-top-search input').length) {
                $('.moduleMenu-top-search input').val('').quicksearch(plugin.settings.ulname.ulname+' li a', {
                    'onBefore': function () {
                        if($(this).val() != '') {
                            plugin.expandSideBarNav();
                        }
                    },
                    'onAfter': function () {
                        if($(this).val() == '') {
                            plugin.collapseSideBarNav();
                        }
                    },
                });
            }

        }

        //expand all nav ul element
        plugin.expandSideBarNav = function () {
            var nav =plugin.settings.ulname.ulname;
            nava = $(nav).find('a.notExpand');
            nava.next('ul').slideDown("300", "linear");
            nava.next('ul').addClass('show');
            nava.addClass('expand').removeClass('notExpand');
            if(plugin.settings.params.showArrows) {
                nava.find('.sideNav-arrow').transition({rotate: '-180deg'});
            }
        }
        //collapse all nav ul elements except current
        plugin.collapseSideBarNav = function () {
            var nav =plugin.settings.ulname.ulname;
            nava = $(nav).find('a.expand');
            nava.next('ul').slideUp("300", "linear");
            nava.next('ul').removeClass('show');
            nava.addClass('notExpand').removeClass('expand');
            if(plugin.settings.params.showArrows) {
                nava.find('.sideNav-arrow').transition({rotate: '0deg'});
            }
        }

        plugin.quickSearch();


    };
}(jQuery));