https://www.freelancer.com/ajax/table/project_contest_datatable.php?sEcho=3&iColumns=35&sColumns=&iDisplayStart=20&iDisplayLength=100&iSortingCols=1&iSortCol_0=6&sSortDir_0=desc&bSortable_0=false&bSortable_1=false&bSortable_2=false&bSortable_3=true&bSortable_4=false&bSortable_5=false&bSortable_6=true&bSortable_7=false&bSortable_8=true&bSortable_9=true&bSortable_10=false&bSortable_11=false&bSortable_12=false&bSortable_13=false&bSortable_14=false&bSortable_15=false&bSortable_16=false&bSortable_17=false&bSortable_18=false&bSortable_19=false&bSortable_20=false&bSortable_21=false&bSortable_22=false&bSortable_23=false&bSortable_24=false&bSortable_25=false&bSortable_26=false&bSortable_27=false&bSortable_28=false&bSortable_29=false&bSortable_30=false&bSortable_31=false&bSortable_32=false&bSortable_33=false&bSortable_34=false&keyword=&featured=false&fulltime=false&nda=false&qualified=false&sealed=false&urgent=false&guaranteed=false&highlight=false&private=false&top=false&type=&budget_min=false&budget_max=false&contest_budget_min=false&contest_budget_max=false&hourlyrate_min=false&hourlyrate_max=false&skills_chosen=&verified_employer=false&bidding_ends=N%2FA&bookmarked=false&countries=false&languages=&hourlyProjectDuration=false&advancedFilterPanelView=&disablePushState=false&pushStateRoot=%2Fjobs&lat=false&lon=false&local=false&location=[object+Object]&ul=vi&uc=1&xpbonus_catIds=223%2C12%2C171%2C51%2C59%2C107%2C215%2C133%2C106%2C77%2C55%2C335%2C54%2C305%2C158%2C3%2C247%2C68%2C17%2C197&jobIdEnable=on&status=open
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