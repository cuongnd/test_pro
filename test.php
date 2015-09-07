<?php
$db=Jfactory::getDBO();
$query=$db->getQuery(true);
$query->select('person_type.id,person_type.person_type_name');
$query->from('#__bookpro_person_type as person_type');
$query->leftJoin('#__bookpro_tour_id_group_size_id AS tour_id_group_size_id ON tour_id_group_size_id.group_size_id=')
$query->where('get_price_map_person_type_id=0');
$query->order('ordering DESC');
$db->setQuery($query);
$list_person=$db->loadObjectList();
$return_list=array();
foreach($list_person as $person)
{
    $return_list[]=(object)array(
        'id'=>'id_'.$person->id,
        'person_type_name'=>$person->person_type_name
    );

    $return_list[]=(object)array(
        'id'=>'margin_id_'.$person->id,
        'person_type_name'=>'margin '.$person->person_type_name
    );
    $return_list[]=(object)array(
        'id'=>'margin_total_id_'.$person->id,
        'person_type_name'=>'T.t price for '.$person->person_type_name
    );
}
return $return_list;
?>