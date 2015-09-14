<?php
$passengers=DataSourceHelper::get_data_source_by_function("get_caculator_person_ty_by_order_id");
$passengers=reset($passengers);
$total=0;
$list_passenger=array();
foreach($passengers as $key=>$total_person_type)
{
    if($total_person_type) {
        $list_passenger[] = "$total_person_type $key";
        $total += $total_person_type;
    }
}
$list_passenger=implode(",",$list_passenger);
return "$total($list_passenger)";
?>