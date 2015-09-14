<?php
$app=JFactory::getApplication();
$order_id=$app->input->get('order_id',0,'int');
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('roomtype.*')
    ->from('#__bookpro_order_roomtypepassenger AS roomtypepassenger')
    ->leftJoin('#__bookpro_roomtype AS roomtype ON roomtype.id=roomtypepassenger.roomtype_id')
    ->where('roomtypepassenger.order_id='.(int)$order_id)
;
$list_room_by_order_id=$db->setQuery($query)->loadObjectList();
$return_room=array();

foreach($list_room_by_order_id as $room)
{
    $item=new stdClass();
    $item->id=$room->id;
    $item->title=$room->title;
    $item->total=$return_room[$room->id]->total+1;
    $return_room[$room->id]=$item;
}
return $return_room;


?>
