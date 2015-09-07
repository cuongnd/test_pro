<?php 
class ActivityHelper {



	static function buildActivities($type='title',$activities){
		AImporter::helper('image');
		$arr=array();
		if ($type=="title"){
			foreach ($activities as $a) {
				$arr[]=$a->title;
			}
			return implode(',', $arr);
		}else if ($type=="icon"){

			foreach ($activities as $a) {
				$arr[]='<img src="'.$a->image .'" class="img-facility">';
			}	
			return implode('', $arr);
		}
			
		

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