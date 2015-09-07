<?php

class ModMenuLeftHelperFrontEnd
{
    function getList($params)
    {
        $dataSource=$params->get('datasource','');
        $dataSource="select * from #__plugins";
        $db=JFactory::getDbo();
        $db->setQuery($dataSource);
        $list=$db->loadObjectList();
        return $list;
    }
 }

?>