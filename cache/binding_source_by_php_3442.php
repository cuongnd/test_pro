<?php
$list_room_booking_by_order_id=DataSourceHelper::get_data_source_by_function("get_room_by_order_id");
$print_room=array();
foreach($list_room_booking_by_order_id as $room)
{
    $print_room[]="$room->total $room->title";
}
return implode(',',$print_room);
?>