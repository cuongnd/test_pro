<?php
$list_prices=array();
$db=JFactory::getDbo();
$app=JFactory::getApplication();
$query=$db->getQuery(true);
$query->select('person_type.*')
    ->from('#__bookpro_person_type AS person_type')
    ->where('person_type.get_price_map_person_type_id=0')
    ->order('person_type.ordering');
$list_person=$db->setQuery($query)->loadObjectList();
$tour_id=$app->input->get('tour_id',0);
require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
$table_tour=new JTableUpdateTable($db,'bookpro_tour');
$table_tour->load($tour_id);

$query->clear()
    ->select('group_size.*')
    ->from('#__bookpro_tour_group_size AS group_size')
    ->leftJoin('#__bookpro_tour_id_group_size_id AS tour_id_group_size_id ON tour_id_group_size_id.group_size_id=group_size.id')
    ->where('tour_id_group_size_id.tour_id='.(int)$tour_id)
    ->order('group_size.ordering')
    ->group('group_size.id')
;
$list_group=$db->setQuery($query)->loadObjectList();
$query->clear()
    ->select('tour_id_group_size_id_person_type_id_price.*')
    ->select('CONCAT(tour_id_group_size_id_person_type_id_price.tour_id,"-",tour_id_group_size_id_person_type_id_price.group_size_id,"-",tour_id_group_size_id_person_type_id_price.person_type_id) AS tour_id_group_size_id_person_type_id')
    ->from('#__bookpro_tour_id_group_size_id_person_type_id_price AS tour_id_group_size_id_person_type_id_price')
    ->where('tour_id_group_size_id_person_type_id_price.tour_id='.(int)$tour_id)
    ;
$list_price=$db->setQuery($query)->loadObjectList('tour_id_group_size_id_person_type_id');
$list_tour_price=array();
$item=new stdClass();
$item->title='';
foreach($list_person as $person)
{
    $item->{$person->person_type_name}=$person->person_type_name;
    $item->{$person->person_type_name."_margin"}="margin ".$person->person_type_name;
    $item->{$person->person_type_name."_total"}="total ".$person->person_type_name;
}
require_once JPATH_ROOT.'/libraries/PHPExcel-1.8/Classes/PHPExcel/Cell.php';
$list_tour_price[]=$item;
for($i=0;$i<count($list_group);$i++)
{
    $row_index=$i+2;
    $group=$list_group[$i];
    $item=new stdClass();

    $item->title=$group->group_name;
    $k=0;
    for($j=0;$j<count($list_person);$j++)
    {
        $person=$list_person[$j];
        $column_letter_price = PHPExcel_Cell::stringFromColumnIndex($j+$k+1);
        $column_letter_margin = PHPExcel_Cell::stringFromColumnIndex($j+$k+2);
        $key=$tour_id.'-'.$group->id.'-'.$person->id;
        $price=$list_price[$key]->price;
        $item->{$person->person_type_name}=$price;
        $margin_price= $list_price[$key]->margin_price;
        $item->{$person->person_type_name."_margin"}=$margin_price;
        $item->{$person->person_type_name."_total"}='='.$column_letter_price.$row_index.'+'.$column_letter_margin.$row_index;
        $k=$k+2;
    }
    $list_tour_price[]=$item;
}

return $list_tour_price;
?>