 <?php 
 class RoomHelper {
    	
    	static function getAdultSelectBox($room,$field,$attribute){
    		
    		$options=array();
    		$adult=$room->adult+1;
    		for ($i = 1; $i < $adult; $i++) {
    			$options[]=JHtml::_('select.option',$i,$i);
    		}
    		if($room->adult_price){
    			
    			$adult_price=explode(',', $room->adult_price);
    			
    			for ($i = 0; $i < count($adult_price); $i++) {
    				
    				$txt=($adult+$i).'(+'.$adult_price[$i].')';
    				$options[]=JHtml::_('select.option',$adult+$i,$txt);
    				
    			}
    		}
    		return JHtmlSelect::genericlist($options, $field,$attribute,'value','text',1);
    		
    	}
    	static function getChildSelectBox($room,$field,$attribute){
    	
    		$options=array();
    		$child_price=explode(',', $room->child_price);
    		
    			$options[]=JHtml::_('select.option',0,0);
    			for ($i = 0; $i < count($child_price); $i++) {
    				if($child_price[$i]>0)
    					$txt=($i+1).'(+'.$child_price[$i].')';
    				else {
    					$txt= ($i+1).'(Free)';
    				}
    				$options[]=JHtml::_('select.option',$i+1,$txt);
    				
    		}
    		return JHtmlSelect::genericlist($options, $field,$attribute);
    	
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