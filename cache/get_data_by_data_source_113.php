<?php
$app=JFactory::getApplication();
$db=JFactory::getDbo();
$order_id=$app->input->get('order_id',0,'int');
$query=$db->getQuery(true);
require_once JPATH_ROOT.'/components/com_phpmyadmin/helpers/datasource.php';
require_once JPATH_ROOT.'/components/com_bookpro/helpers/bookpro.php';
$list_person_type=DataSourceHelper::get_data_source_by_function('get_list_person_ty');
$list_passenger=DataSourceHelper::get_data_source_by_function('get_list_passenger_by_order_id');
$list_count_person_type=array();

foreach($list_person_type as $person_type)
{
    foreach($list_passenger as $passenger) {
        $year_old=BookProHelperFrontEnd::getyearold($passenger->birthday);
        $list_count_person_type[$person_type->person_type_name]=0;
        if($person_type->old_from<=$year_old && $year_old< $person_type->old_to)
        {
            $list_count_person_type[$person_type->person_type_name]++;
        }
    }
}
return  array($list_count_person_type);


?>