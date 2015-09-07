console.log('hello passenger');
jQuery(document).ready(function($){

    element_ui_passengers={
        database_local:'asianventure',
        table_passenger_name:'passenger',
        db:null,
        list_passenger:{},
        datepicker_option:{
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            maxDate: new Date(),
            buttonImageOnly: true,
            buttonImage: this_host+'components/com_bookpro/assets/images/callender.png',
            onSelect: function(selected) {
            }
        },
        passengers:{
            adult:{
                min_age:18,
                max_age:100,
                min_selected:1,
                max_selected:10,
                set_selected:1,
                title:function(){
                    min_age=element_ui_passengers.passengers.adult.min_age;
                    max_age=element_ui_passengers.passengers.adult.max_age;
                    return 'Adult('+min_age+'-'+max_age+')';
                },
                text:'Adult'
            },
            teener:{
                min_age:12,
                max_age:17,
                min_selected:1,
                max_selected:10,
                set_selected:1,
                title:function(){
                    min_age=element_ui_passengers.passengers.teener.min_age;
                    max_age=element_ui_passengers.passengers.teener.max_age;
                    return 'Teener('+min_age+'-'+max_age+')';
                },
                text:'Teener'
            },
            child:{
                min_age:3,
                max_age:11,
                min_selected:1,
                max_selected:10,
                set_selected:1,
                title:function(){
                    min_age=element_ui_passengers.passengers.child.min_age;
                    max_age=element_ui_passengers.passengers.child.max_age;
                    return 'Child('+min_age+'-'+max_age+')';
                },
                text:'Child'
            }
        },
        init_ui_passengers:function(){
            group_passenger=$('.group-passenger').hide();
            $.each(element_ui_passengers.passengers, function( index, passenger_type ) {
                clone_group_passenger=group_passenger.clone(true).show();
                clone_group_passenger.find('.oder-passenger').html(passenger_type.text+':1');
                clone_group_passenger.find('.title-passenger').html(passenger_type.title);
                clone_group_passenger.insertBefore(group_passenger);
                clone_group_passenger.find('*[data-type="date"]').datepicker(element_ui_passengers.datepicker_option);
                clone_group_passenger.find('*[data-passenger-type!=""]').attr('data-passenger-type',passenger_type.text);
            });
            group_passenger.remove();
        },
        get_passengers:function(){
             $('.row-item').each(function(index){
                 index_passenger=index+1;
                 element_ui_passengers.list_passenger[index_passenger]={};
                 $(this).find('input,select,textarea').each(function(){
                     item=$(this);
                     element_ui_passengers.list_passenger[index_passenger][item.attr('data-name')]=item.val();
                 });
             });
            return  element_ui_passengers.list_passenger;
        },
        change_total_passenger:function(self,type)
        {
            total=$(self).val();
            key_passenger=element_ui_passengers.get_selected_key(type);
            from=key_passenger.set_selected;
            to=total;
            if(total<key_passenger.set_selected)
            {
                from=total;
                to=key_passenger.set_selected;
            }
            for(i=from;i<to;i++)
            {
                row_item=$('.row-item[data-passenger-type="'+type+'"]:last');
                if(total>key_passenger.set_selected)
                {
                    row_item.clone(true).insertBefore(row_item);
                    $('group-passenger[data-passenger-type="'+type+'"]').show();
                }else{
                    if($('.row-item[data-passenger-type="'+type+'"]').length>1)
                        row_item.remove();
                    if(total==0)
                    {
                        $('group-passenger[data-passenger-type="'+type+'"]').hide();
                    }
                }

            }
            key_passenger.set_selected=total;
            element_ui_passengers.format_all(type);




        },
        format_all:function(type){
            $('.row-item[data-passenger-type="'+type+'"]').each(function(index){
                order=index+1;
                $(this).find('*[data-name]').each(function(){
                    name=$(this).attr('data-name');
                    $(this).attr('name',name+'[]');
                    $(this).attr('id',name+'_'+order);
                });
                $(this).find('.oder-passenger').html(key_passenger.text+':'+order);
                $(this).find('*[data-type="date"]').datepicker("destroy" );
                $(this).find('*[data-type="date"]').datepicker(element_ui_passengers.datepicker_option);
            });
        },
        get_selected_key:function(type)
        {
            switch(type) {
                case element_ui_passengers.passengers.adult.text:
                    //code block
                    return  element_ui_passengers.passengers.adult;
                    break;
                case element_ui_passengers.passengers.teener.text:
                    return  element_ui_passengers.passengers.teener;
                    //code block
                    break;
                case element_ui_passengers.passengers.child.text:
                    return  element_ui_passengers.passengers.child;
                    //default code block
                    break;
            }
        },
        remove_row:function(self){
            type=$(self).attr('data-passenger-type');
            key_passenger=element_ui_passengers.get_selected_key(type);
            if(key_passenger.set_selected==1)
            {
                return;
            }
            row_item=$(self).closest('.row-item');
            row_item.remove();
            key_passenger.set_selected--;
            switch(type) {
                case element_ui_passengers.passengers.adult.text:
                    //code block
                   $('select[name="total_adult"]').val(key_passenger.set_selected);
                    break;
                case element_ui_passengers.passengers.teener.text:
                    $('select[name="total_teener"]').val(key_passenger.set_selected);
                    //code block
                    break;
                case element_ui_passengers.passengers.child.text:
                    $('select[name="total_child"]').val(key_passenger.set_selected);
                    //default code block
                    break;
            }
            element_ui_passengers.format_all(type);


        },
        update_other_element:function(){
            list_passenger=element_ui_passengers.get_passengers();
            console.log(list_passenger);
            element_ui_room.update_person_select(list_passenger);
            element_ui_booking_summary.update_person_select(list_passenger);
        }
    };
    $('.block-item.block-item-passengers').find(':input').change(function(){
        element_ui_passengers.update_other_element();
        list_passenger_room=element_ui_room.get_passengers_room();
        element_ui_booking_summary.update_room_select(list_passenger_room);
    });


});