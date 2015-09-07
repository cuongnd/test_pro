jQuery(document).ready(function($){

    element_ui_booking_summary={
        init_ui_booking_summary:function(){

        },
        update_person_select:function(list_passenger)
        {
            bookingsummary=$('.block-item.block-item-bookingsummary');
            ul_list_passenger=bookingsummary.find('.list_passenger');
            ul_list_passenger.empty();
            total_person=0;
            $.each(list_passenger, function( index, passenger ) {
                total_person++;
                ul_list_passenger.append('<li >'+index+'.'+(passenger.first_name+' '+passenger.last_name)+'(adult 34 years old)'+'</li>');
            });

            bookingsummary.find('.total_person').html(total_person+' pers.');
        },
        update_room_select:function(list_passenger_room){
            list_passenger=element_ui_passengers.get_passengers();
            item_room_passenger=$('.item-room-passenger:first');
            item_room_passenger.addClass('template');
            $('.item-room-passenger:not(.template)').remove();
            $('.item-room-passenger').removeClass('template');
            item_room_passenger=$('.item-room-passenger');
            $.each(list_passenger_room, function( index, passenger_room) {
                item_room_passenger_clone=item_room_passenger.clone(true);
                item_room_passenger_clone.insertBefore(item_room_passenger);

                ul_list_passenger_for_room=item_room_passenger_clone.find('.list_passenger_for_room');
                ul_list_passenger_for_room.empty();
                    i=1;
                    $.each(passenger_room, function( index_passenger_room, room_type) {
                        if(index_passenger_room!=0) {
                            item_passenger = list_passenger[index_passenger_room];
                            if (i == 1) {
                                item_room_passenger_clone.find('.room_type').html(index + '.' + room_type);
                            }

                            ul_list_passenger_for_room.append('<li >' + (++i) + '.' + item_passenger.first_name + ' ' + item_passenger.last_name + '</li>');
                        }
                    });
            });
            $('.item-room-passenger:last').remove();
            element_ui_booking_summary.update_view_passenger_room();
            console.log(list_passenger_room);
        },
        update_view_passenger_room:function(){

        }

    };



});