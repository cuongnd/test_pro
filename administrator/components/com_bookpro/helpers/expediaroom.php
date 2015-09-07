 <?php
 class ExpediaRoomHelper {

    	static function getAdultSelectBox($number_adult,$field,$attribute){

    		$options=array();
    		for ($i = 1; $i < $number_adult+1; $i++) {
    			$options[]=JHtml::_('select.option',$i,$i);
    		}
    		return JHtmlSelect::genericlist($options, $field,$attribute,'value','text',1);

    	}
    	static function getChildSelectBox($number_child,$field,$attribute){

    		$options=array();
    		for ($i = 1; $i < $number_child+1; $i++) {
    			$options[]=JHtml::_('select.option',$i,$i);
    		}
    		return JHtmlSelect::genericlist($options, $field,$attribute,'value','text',1);

    	}
         static function getFacilitiesByRoomID($room_id){
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('fac.*');
            $query->from('#__bookpro_facility as fac');
            $query->leftJoin('#__bookpro_roomfacility AS roomfac ON roomfac.facility_id=fac.id');
            $query->where('roomfac.room_id='.$room_id);
            $query->where('fac.ftype=1');
            $db->setQuery($query);
            $list=$db->loadObjectList();
            return $list;

        }


    	static function getFacilities($room_id){

    		$db=JFactory::getDbo();
    		$query=$db->getQuery(true);
			$query->select('*')->from('#__bookpro_facility AS f');
			$query->leftJoin('#__bookpro_roomfacility AS rf ON f.id=rf.facility_id');
			$query->where('rf.room_id='.(int) $room_id);
			$db->setQuery($query);
			$items=$db->loadObjectList();
    		if(count($items)>0){
    			foreach ($items as $item){
    				$themes.= '<span class="label label-info">'. $item->title . '</span>&nbsp;';
    			}
    		}
    		return $themes;
    	}

}

?>