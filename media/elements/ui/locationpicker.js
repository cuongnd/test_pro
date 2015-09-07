jQuery(document).ready(function($){

    element_ui_locationpicker={
        init_ui_locationpicker:function(){
            var menu = new Gmap3Menu($("#test")),
                current,  // current click event (used to save as start / end position)
                m1,       // marker "from"
                m2;       // marker "to"

// update marker
            function updateMarker(marker, isM1){
                if (isM1){
                    m1 = marker;
                } else {
                    m2 = marker;
                }
                updateDirections();
            }

// add marker and manage which one it is (A, B)
            function addMarker(isM1){
                // clear previous marker if set
                var clear = {name:"marker"};
                if (isM1 && m1) {
                    clear.tag = "from";
                    $("#test").gmap3({clear:clear});
                } else if (!isM1 && m2){
                    clear.tag = "to";
                    $("#test").gmap3({clear:clear});
                }
                // add marker and store it
                $("#test").gmap3({
                    marker:{
                        latLng:current.latLng,
                        options:{
                            draggable:true,
                            icon:new google.maps.MarkerImage("http://maps.gstatic.com/mapfiles/icon_green" + (isM1 ? "A" : "B") + ".png")
                        },
                        tag: (isM1 ? "from" : "to"),
                        events: {
                            dragend: function(marker){
                                updateMarker(marker, isM1);
                            }
                        },
                        callback: function(marker){
                            updateMarker(marker, isM1);
                        }
                    }
                });
            }

// function called to update direction is m1 and m2 are set
            function updateDirections(){
                if (!(m1 && m2)){
                    return;
                }
                $("#test").gmap3({
                    getroute:{
                        options:{
                            origin:m1.getPosition(),
                            destination:m2.getPosition(),
                            travelMode: google.maps.DirectionsTravelMode.DRIVING
                        },
                        callback: function(results){
                            if (!results) return;
                            $("#test").gmap3({get:"directionrenderer"}).setDirections(results);
                        }
                    }
                });
            }

// MENU : ITEM 1
            menu.add("Direction to here", "itemB",
                function(){
                    menu.close();
                    addMarker(false);
                });

// MENU : ITEM 2
            menu.add("Direction from here", "itemA separator",
                function(){
                    menu.close();
                    addMarker(true);
                })

// MENU : ITEM 3
            menu.add("Zoom in", "zoomIn",
                function(){
                    var map = $("#test").gmap3("get");
                    map.setZoom(map.getZoom() + 1);
                    menu.close();
                });

// MENU : ITEM 4
            menu.add("Zoom out", "zoomOut",
                function(){
                    var map = $("#test").gmap3("get");
                    map.setZoom(map.getZoom() - 1);
                    menu.close();
                });

// MENU : ITEM 5
            menu.add("Center here", "centerHere",
                function(){
                    $("#test").gmap3("get").setCenter(current.latLng);
                    menu.close();
                });

// INITIALIZE GOOGLE MAP
            $("#test").gmap3({
                map:{
                    options:{
                        center:[48.85861640881589, 2.3459243774414062],
                        zoom: 5
                    },
                    events:{
                        rightclick:function(map, event){
                            current = event;
                            menu.open(current);
                        },
                        click: function(){
                            menu.close();
                        },
                        dragstart: function(){
                            menu.close();
                        },
                        zoom_changed: function(){
                            menu.close();
                        }
                    }
                },
                // add direction renderer to configure options (else, automatically created with default options)
                directionsrenderer:{
                    divId:"directions",
                    options:{
                        preserveViewport: true,
                        markerOptions:{
                            visible: false
                        }
                    }
                }
            });
        }

    };



});