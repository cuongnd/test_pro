// jQuery Plugin for SprFlat admin template
// Control options and basic function of template
// version 1.0, 28.02.2013
// by SuggeElson www.suggeelson.com

(function ($) {

    // here we go!
    $.ui_locationpicker = function (element, options) {




        // plugin's default options
        var defaults = {
        }

        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element
        // the "constructor" method that gets called when the object is created
        var element_id=$element.attr('id');
        var map_style=$element.data('map_style');
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);


            if(map_style=='style1') {
                var $map = $element,
                    menu = new Gmap3Menu($map),

                    current,  // current click event (used to save as start / end position)
                    m1,       // marker "from"
                    m2;       // marker "to"

                // update marker
                function updateMarker(marker, isM1) {
                    if (isM1) {
                        m1 = marker;
                    } else {
                        m2 = marker;
                    }
                    updateDirections();
                }

                // add marker and manage which one it is (A, B)
                function addMarker(isM1) {
                    // clear previous marker if set
                    var clear = {name: "marker"};
                    if (isM1 && m1) {
                        clear.tag = "from";
                        $map.gmap3({clear: clear});
                    } else if (!isM1 && m2) {
                        clear.tag = "to";
                        $map.gmap3({clear: clear});
                    }
                    // add marker and store it
                    $map.gmap3({
                        marker: {
                            latLng: current.latLng,
                            options: {
                                draggable: true,
                                icon: new google.maps.MarkerImage("http://maps.gstatic.com/mapfiles/icon_green" + (isM1 ? "A" : "B") + ".png")
                            },
                            tag: (isM1 ? "from" : "to"),
                            events: {
                                dragend: function (marker) {
                                    updateMarker(marker, isM1);
                                }
                            },
                            callback: function (marker) {
                                updateMarker(marker, isM1);
                            }
                        }
                    });
                }

                // function called to update direction is m1 and m2 are set
                function updateDirections() {
                    if (!(m1 && m2)) {
                        return;
                    }
                    $map.gmap3({
                        getroute: {
                            options: {
                                origin: m1.getPosition(),
                                destination: m2.getPosition(),
                                travelMode: google.maps.DirectionsTravelMode.DRIVING
                            },
                            callback: function (results) {
                                if (!results) return;
                                $map.gmap3({get: "directionsrenderer"}).setDirections(results);
                            }
                        }
                    });
                }

                // MENU : ITEM 1
                menu.add("Direction to here", "itemB",
                    function () {
                        menu.close();
                        addMarker(false);
                    });

                // MENU : ITEM 2
                menu.add("Direction from here", "itemA separator",
                    function () {
                        menu.close();
                        addMarker(true);
                    })

                // MENU : ITEM 3
                menu.add("Zoom in", "zoomIn",
                    function () {
                        var map = $map.gmap3("get");
                        map.setZoom(map.getZoom() + 1);
                        menu.close();
                    });

                // MENU : ITEM 4
                menu.add("Zoom out", "zoomOut",
                    function () {
                        var map = $map.gmap3("get");
                        map.setZoom(map.getZoom() - 1);
                        menu.close();
                    });

                // MENU : ITEM 5
                menu.add("Center here", "centerHere",
                    function () {
                        $map.gmap3("get").setCenter(current.latLng);
                        menu.close();
                    });

                // INITIALIZE GOOGLE MAP
                $map.gmap3({
                    map: {
                        options: {
                            center: [48.85861640881589, 2.3459243774414062],
                            zoom: 5
                        },
                        events: {
                            rightclick: function (map, event) {
                                current = event;
                                menu.open(current);
                            },
                            click: function () {
                                menu.close();
                            },
                            dragstart: function () {
                                menu.close();
                            },
                            zoom_changed: function () {
                                menu.close();
                            }
                        }
                    },
                    // add direction renderer to configure options (else, automatically created with default options)
                    directionsrenderer: {
                        divId: "directions",
                        options: {
                            preserveViewport: true,
                            markerOptions: {
                                visible: false
                            }
                        }
                    }
                });

            }else{
                var content_target=$element.data('target');
                var $content_target=$('#'+content_target);
                var $address=$element.find('.address');
                var $radius=$element.find('.radius');
                var $lat=$element.find('.lat');
                var $lon=$element.find('.lon');
                var width=300;
                var height=300;
                $address.webuiPopover({
                    width:width,
                    height:height,
                    padding:false,
                    trigger:'focus',
                    animation:'pop',
                    title: 'Map',
                    content:'loading',
                    closeable: true,
                    onShow:function($target){
                        var webuiPopover=$content_target.data('webuiPopover');
                        if(!webuiPopover) {
                            $content_target.data('webuiPopover', true);
                            $content_target.css({
                                position:"relative",
                                visibility:"visible"
                            });
                            var popover_content_element = $target.find('.webui-popover-content');
                            popover_content_element.empty();
                            popover_content_element.append($content_target);


                        }

                    }
                });

                $content_target.locationpicker({
                    location: {latitude: 46.15242437752303, longitude: 2.7470703125},
                    radius: 20,
                    inputBinding: {
                        locationNameInput: $address,
                        latitudeInput: $lat,
                        longitudeInput: $lon,
                        radiusInput: $radius,
                    },
                    enableAutocomplete: true
                });

                //$('#'+target).remove();


            }


        }
        plugin.example_function = function () {

        }
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.ui_locationpicker = function (options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function () {
            // if plugin has not already been attached to the element
            if (undefined == $(this).data('ui_locationpicker')) {
                var plugin = new $.ui_locationpicker(this, options);
                $(this).data('ui_locationpicker', plugin);

            }

        });

    }

})(jQuery);

