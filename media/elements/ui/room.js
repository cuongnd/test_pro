jQuery(document).ready(function($){

    element_ui_room={
        list_passenger_room:{},
        init_ui_room:function(){

        },
        update_person_select:function(list_passenger){
            $('select.passenger_room').empty();
            $('select.passenger_room').append('<option value="0">select passenger</option>');
            $.each(list_passenger, function( index, passenger ) {
                $('select.passenger_room').append('<option value="'+index.toString()+'">'+(passenger.first_name+' '+passenger.last_name)+'</option>');
            });

        },
        add_more_room:function(self) {
             last_room_item=$('.room-item:last');
            last_room_item.clone(true).insertBefore(last_room_item).slideDown();
            element_ui_room.update_view();
        },
        remove_room:function(self) {
            room_item=$(self).closest('.room-item');
            if(room_item.length>1)
            {
                room_item.fadeOut(400, function(){
                    $(this).remove();
                });
            }
            element_ui_room.update_view();

        },
        change_room_type:function(self){
            room_item=$(self).closest('.room-item');
            room_total_avaible=room_item.find('select.passenger_room').length;

            from=room_total_avaible;
            rooms=parseInt($(self).val());
            to=rooms;
            if(rooms<room_total_avaible)
            {
                from=rooms;
                to=room_total_avaible;
            }
            for(i=from;i<to;i++)
            {
                passenger_room=room_item.find('select.passenger_room:last');
                if(rooms>room_total_avaible)
                {
                    passenger_room.clone(true).insertBefore(passenger_room).slideDown();;
                }else{

                    passenger_room.fadeOut(400, function(){
                        $(this).remove();
                    });
                }

            }
        },
        update_view:function(){
            $('.room-item').each(function(index){
                $(this).find('.room_order').html('Room '+(index+1));
                room_type=$(this).find('input[data-name="room_type"]');
                name= room_type.attr('data-name');
                room_type.attr('name',name+(index+1));
            });
        },
        get_passengers_room:function(){
            list_passenger_room={};
            block=$('.block-item.block-item-room');
            block.find('.room-item').each(function(index){
                room_item=$(this);
                index_room= index+1;
                list_passenger_room[index_room]={};
                room_type=room_item.find('input[data-name="room_type"]:checked').attr('data-room-type');
                room_item.find('.passenger_room').each(function(index_passenger_room){
                    passenger_room=$(this);
                    list_passenger_room[index_room][passenger_room.val()]=room_type ;
                });


            });
            element_ui_room.list_passenger_room=list_passenger_room;
           return element_ui_room.list_passenger_room;
        },
        update_other_element:function(){
            list_passenger_room=element_ui_room.get_passengers_room();
            element_ui_booking_summary.update_room_select(list_passenger_room);
        }

    };

    $('.block-item.block-item-room').find(':input').change(function(){
        element_ui_room.update_other_element();
    });





});