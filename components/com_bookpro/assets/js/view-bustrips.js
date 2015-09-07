/**
 * Created by cuongnd on 9/9/14.
 */

jQuery(document).ready(function($){

    $('.table_car').footable();

    $(".popular-car-rental").tablesorter({
        // sort on the first column and third column, order asc
        sortList: [[0,0],[2,0]]
    });

    $('#best-deals').nooSliderLite({
        auto:0
        ,vertical:true
        ,btnPrev: '#btn_prev'
        ,btnNext: '#btn_next'
    });
    $('#events_in_region').nooSliderLite({
        auto:0
        ,vertical:false
        ,btnPrev: '#btn_prev_event'
        ,btnNext: '#btn_next_event'
        ,visible: 3
    });
    $('#car-rental-routes').nooSliderLite({
        auto:0
        ,vertical:true
        ,scroll: 5
        ,'image_slider':100
        ,'thumbHeight':300
        ,btnPrev: '#btn_prev_car_rental_routes'
        ,btnNext: '#btn_next_car_rental_routes'
        ,visible: 5
    });


    $(document).on('click','.icon-down',function(){
        $('.control-top').addClass('icon-top');
        last_tr_visable=$('#search-car-rentals tbody tr:visible:last').attr('data-i');
        last_tr_visable=last_tr_visable.toInt();
        $('#search-car-rentals tbody tr').each(function(){

            data_i=$(this).attr('data-i');

            if(data_i<=last_tr_visable)
            {
                $(this).hide();
            }
            need_last_show=last_tr_visable+numberItemOnOnePage+1;
            if(data_i>last_tr_visable && data_i<need_last_show)
            {
                $(this).show();
            }
            total_item=$('#search-car-rentals tbody tr:last').attr('data-i');

        })


        if(need_last_show+numberItemOnOnePage>=total_item)
        {
            $(this).removeClass('icon-down');
        }
;
    });
    $(document).on('click','.icon-top',function(){
        $('.control-down').addClass('icon-down');
        first_tr_visable=$('#search-car-rentals tbody tr:visible:first').attr('data-i');
        first_tr_visable=first_tr_visable.toInt();

        $('#search-car-rentals tbody tr').each(function(){
            data_i=$(this).attr('data-i');
            if(data_i>=(first_tr_visable-numberItemOnOnePage) && data_i<first_tr_visable)
            {
                $(this).show();
            }
            if(data_i>=first_tr_visable)
            {
                $(this).hide();
            }
        });

        if(first_tr_visable-numberItemOnOnePage<=0)
        {
            $(this).removeClass('icon-top');
        }

    });
    $(document).on('click','a.prev-day',function(){
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                $data = {
                    option: 'com_bookpro'
                    ,controller: 'bustrips'
                    ,task: 'ajaxGetDataBusTrip'
                    ,prev_day:1
                }
                return $data;
            })(),
            beforeSend: function() {
                $('.widgetbookpro-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.widgetbookpro-loading').css({
                    display: "none"
                });
                sethtmlfortag(result);
            }
        })
    });
    $(document).on('click','input.select-trip',function(){
         roundtrip=$(this).val();
        getListBusTrips(roundtrip);
    });

    function getListBusTrips(type,value_type)
    {
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                $data = {
                    option: 'com_bookpro',
                    controller: 'bustrips',
                    task: 'ajaxGetListBustrips',
                    roundtrip:roundtrip
                }
                return $data;
            })(),
            beforeSend: function() {
                $('.widgetbookpro-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.widgetbookpro-loading').css({
                    display: "none"
                });
                sethtmlfortag(result);
            }
        })

    }


    function getDataBusTrip()
    {
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                $data = {
                    option: 'com_bookpro'
                    ,controller: 'bustrips'
                    ,task: 'ajaxGetDataBusTrip'
                }
                return $data;
            })(),
            beforeSend: function() {
                $('.widgetbookpro-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.widgetbookpro-loading').css({
                    display: "none"
                });
                sethtmlfortag(result);
            }
        })
    }
    function sethtmlfortag(respone_array)
    {
        respone_array = $.parseJSON(respone_array);
        $.each(respone_array, function(index, respone) {

            $(respone.key.toString()).html(respone.contents);
        });
    }
    $(document).on('click','a.next-day',function(){
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                $data = {
                    option: 'com_bookpro'
                    ,controller: 'bustrips'
                    ,task: 'ajaxGetDataBusTrip'
                    ,next_day:1
                }
                return $data;
            })(),
            beforeSend: function() {
                $('.widgetbookpro-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.widgetbookpro-loading').css({
                    display: "none"
                });
                sethtmlfortag(result);
            }
        })
    });

    var  currency='USD';
    $( "#price-slider-range" ).slider({
        range: true,
        min: 0,
        max: 500,
        values: [ 75, 300 ],
        slide: function( event, ui ) {
            $( "#price-slider-range-amount" ).html( currency+' ' + ui.values[ 0 ] + " - "+currency+' ' + ui.values[ 1 ] );


        },

        stop: function( event, ui ) {
        },
        change: function (event, ui) {
            var minRate = ui.values[0];
            var maxRate = ui.values[1];
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'bustrips',
                        task: 'ajaxGetListBustrips',
                        minRate:minRate,
                        maxRate:maxRate
                    }
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "block",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function(result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag(result);
                }
            });
        }
    })


    $(document).on('click','.select-all-vehicle',function(){
        $('.input-vehicle').prop("checked", true);
    });
    $(document).on('click','.select-clear-all-vehicle',function(){
        $('.input-vehicle').prop("checked", false);
    });
    $(document).on('click','.input-vehicle,.select-all-vehicle,.select-clear-all-vehicle',function(){
        vehicles=[];
        $('.input-vehicle').each(function(){
            if($(this).is(':checked'))
            {
                vehicles.push($(this).val());
            }

        });
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                $data = {
                    option: 'com_bookpro',
                    controller: 'bustrips',
                    task: 'ajaxGetListBustrips',
                    vehicles:vehicles
                }
                return $data;
            })(),
            beforeSend: function() {
                $('.widgetbookpro-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('.widgetbookpro-loading').css({
                    display: "none"
                });
                sethtmlfortag(result);
            }
        });

    });
});
