jQuery(document).ready(function ($) {
    $('.readmore').click(function () {
        id = $(this).attr('data');
        $('tr.readmore_' + id).toggle();
    });

    //$('select[name="room"]').val(1);
    //$('select[name="children[]"]').val(0);
    //$('select[name="adult[]"]').val(1);
    $(document).on('change', 'select[name="room"]', function () {
        setavailbleroom($(this));
    });
    //setavailbleroom($('.reflection select[name="room"]'));

    function setavailbleroom($this) {
        $('select[name="children[]"]').val(0);
        $rooms = $this.val();

        $('.adut-child .wapper3.reflected').remove();
        if($rooms==1)
        {
            $('.adut-child .wapper3.reflection').css({
                'display':'none'
            });
        }else
        {
            $('.adut-child .wapper3.reflection').css({
                'display':'block'
            });
        }
        for ($i = 0; $i < $rooms - 2; $i++) {
            $('.adut-child .wapper3.reflection').css({'display':'block'}).after(function () {
                $object = $(this).clone().toggleClass('reflection reflected');
                $object.find('.title-room span.room-number').html($rooms-$i);
                $object.find('select.age_children').attr('name','age_children['+($rooms-$i-1)+'][]');
                return $object;
            });
        }
        $('.wapper-children .wapper1-children.reflected').remove();
        for ($i = 0; $i < $rooms - 1; $i++) {
            $('.wapper-children .wapper1-children.reflection').after(function () {
                $object = $(this).clone().toggleClass('reflection reflected');
                $object.find('.control-group.age-children.reflected').remove();
                $object.find('.title-room span.room-number').html($rooms-$i);
                $object.find('select.age_children').attr('name','age_children['+($rooms-$i-1)+'][]');
                return $object;
            });
        }
        $('.wapper-children .wapper1-children').css({
            'display':'none'
        });

    }


    $(document).on('change', 'select[name="children[]"]', function () {
        showcontrolage_children($(this));
    });
    $('select[name="children[]"]').each(function () {

        showcontrolage_children($(this));
    });


    function showcontrolage_children($this) {

        children = $this.val();
        $index_of=$('select[name="children[]"]').index($this);
        $age_child_index=$('.wapper-children .wapper1-children:eq('+$index_of+')');
        if(children>0)
        {
            $age_child_index.css({
                'display':'block'
            });
        }
        else
        {
            $age_child_index.css({
                'display':'none'
            });
        }


        $age_child_index.find('.age-children.reflected').remove();
        $age_children_reflection=$age_child_index.find('.age-children.reflection')

        for ($i = 0; $i < children - 1; $i++) {
            $age_children_reflection.css({'display': 'block'}).after(function () {
                $object = $(this).clone().toggleClass('reflection reflected');
                $object.find('span.children-number').html(children-$i);
                $object.css({
                    'display':'block'
                });
                return $object;
            });
        }


    }
    $("#checkin").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        minDate: new Date(),

        onSelect: function () {

            var selected = $('#checkin').datepicker('getDate');
            selected.setDate(selected.getDate() + 1);
            $('#checkout').datepicker('setDate', selected);
            $("#checkout").datepicker("option", {
                minDate: selected
            });
        }
    });
    $("#checkout").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        minDate: new Date()
    });

    $('#destinationstring').select2({
        minimumInputLength: 3,
        ajax: {
            url: "index.php",
            dataType: 'json',
            quietMillis: 100,
            data: function (term, page) {
                return {
                    keyword: term,
                    page_limit: 10,
                    page: page,
                    option: 'com_bookpro',
                    controller: 'expediahotel',
                    task: 'ajax_getJsonListString'
                };
            },
            results: function (data) {
                var results = [];
                $.each(data, function(index, items){
                    results.push({
                        text: index,
                        children: items
                    });
                });
                return {
                    results: results
                };
            }


        },
        createSearchChoice:function(term, data) {
            if ( $(data).filter( function() {
                return this.text.localeCompare(term)===0;
            }).length===0) {
                return {id:term, text:term};
            }
        }

    });
    $("#SubNavChangeSearchLink").click(function (a) {
        console.log(a);
        a.preventDefault();
        a = $("#SubNavOverlay");
        a.is(":hidden") ? ($(this).find(".smallIcon").html("A")) : ($(this).find(".smallIcon").html("D"))
    });
    function sethtmlfortag($respone_array) {
        $respone_array = $.parseJSON($respone_array);
        $.each($respone_array, function ($index, $respone) {

            $($respone.key.toString()).html($respone.contents);
        });
    }
    $( "#price-slider-range" ).slider({
        range: true,
        min: 0,
        max: 500,
        values: [ 75, 300 ],
        slide: function( event, ui ) {
            $( "#price-slider-range-amount" ).html( currency+' ' + ui.values[ 0 ] + " - "+currency+' ' + ui.values[ 1 ] );


        },

        stop: function( event, ui ) {
            console.log('hello');
        },
        change: function (event, ui) {
            console.log('hello');
            var minRate = ui.values[0];
            var maxRate = ui.values[1];
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'expediahotel',
                        task: 'ajaxGetListHotel',
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
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag($result);
                }
            });
        }
    });



    $( "#start-slider-range" ).slider({
        range: true,
        min: 1,
        max: 5,
        values: [ 1, 5 ],
        slide: function( event, ui ) {
            $( "#start-slider-range-amount" ).html('From '+ ui.values[ 0 ] + " to " + ui.values[ 1 ] );

        },
        stop: function( event, ui ) {
            console.log('hello stop');
        },
        change: function (event, ui) {
            console.log('hello change');
            var minStart = ui.values[0];
            var maxStart = ui.values[1];
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'expediahotel',
                        task: 'ajaxGetListHotel',
                        minStart:minStart,
                        maxStart:maxStart
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
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag($result);
                }
            });
        }
    });




    function loadDetailhotel(hotel_oder) {
        hotel_id = $('input[name="hotelid"]:eq(' + hotel_oder + ')').val();
        if (hotel_id != '') {
            $.ajax({
                url: 'index.php?option=com_bookpro&controller=expediahotel&layout=item&task=gethotelinfo&hotel_id=' + hotel_id,
                beforeSend: function () {
                    jQuery("#roomlist").html('<div align="center"><img src="components/com_bookpro/assets/images/loader.gif" /><div>');
                },
                success: function ($result) {
                    $result = $.parseJSON($result);
                    $result[0].key = $result[0].key + ':eq(' + hotel_oder + ')';
                    $result = JSON.stringify($result);
                    console.log($result);
                    sethtmlfortag($result);
                    //loadDetailhotel(hotel_oder+1);

                }
            });
        }


    }

    $(document).on('shown', '.googlemap.modal', function () {
        var map = new GMaps({
            el: '#googlemap_body',
            lat: -12.043333,
            lng: -77.028333
        });
        //locations request
        map.addLayer('places', {
            location: new google.maps.LatLng(-12.043333, -77.028333),
            radius: 500,
            types: ['store']
        });


    });


});
