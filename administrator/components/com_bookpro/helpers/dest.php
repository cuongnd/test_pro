 <?php 
 class DestHelper {
    	
    	static function buildDestType($dest){
    		$str='';
    		if($dest->air)
    			$str.= '<i class="icon-large icon-plane"></i>';
    		if($dest->bus)
    			$str.= '<i class="icon-large icon-bus"></i>';
    		if($dest->province)
    			$str.= '<i class="icon-large icon-home"></i>';
    		return $str;
    	}
    	
    	static function getHotelByDest($dest_id){
    	
    		$mainframe = JFactory::getApplication();
    		$db = JFactory::getDBO();
    		$query = $db->getQuery(true);
    		$query->select("h.*");
    		$query->from('#__bookpro_hotel AS h');
    		$query->where("state=1 AND city_id=".$dest_id);
    		$sql = (string)$query;
    		$db->setQuery($sql);
    		$data = $db->loadObjectList();
    		$query->clear();
    		return count($data);
    	
    	}

}

?>