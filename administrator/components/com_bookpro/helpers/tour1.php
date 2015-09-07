<?php
    /**
    * Bookpro check class
    *
    * @package Bookpro
    * @author Nguyen Dinh Cuong
    * @link http://ibookingonline.com
    * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @version $Id: tourhelper.php 105 2012-08-30 13:20:09Z quannv $
    */

    class TourHelper {

        /**
        *
        * @param Tour ID $tour_id
        * @return minimum price of a tour
        */
    	static function getMinPriceTour($tour_id,$from_date,$to_date,$field='adult'){
    		AImporter::helper('date');
    		$start = JFactory::getDate($from_date)->format('d-m-Y',true);
    		$end = JFactory::getDate($to_date)->format('d-m-Y',true);
    	
    		$db = JFactory::getDBO();
    		$query = 'SELECT package.* FROM #__bookpro_tour_package AS package ';
    		$where = array();
    		if ($tour_id) {
    			$where[] = 'package.tour_id = '.$tour_id;
    		}
    		$query .= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
    	
    		$db->setQuery($query);
    		$packages = $db->loadObjectList();
    		$price = array();
    		if (count($packages)) {
    			foreach ($packages as $package){
    				$price[] = TourHelper::getMinTotalPricePackage($package->id, $start, $end,$field);
    			}
    		}
    	
    		if (!empty($price)) {
    			$returnprice =min($price);
    		}else{
    			$returnprice =0;
    		}
    		return $returnprice;
    	
    	
    	}
    	static function getCountryObject($id){
    		
    		$db = JFactory::getDbo();
    		$query = $db->getQuery(true);
    		$query->select('country.*');
    		$query->from('#__bookpro_country AS country');
    		$query->where(array('country.state=1','country.id='.$id));
    		$db->setQuery($query);
    		$obj = $db->loadObject();
    		//var_dump($obj);
    		return $obj;
    	}
    	static function getCountryByTour($tour_id){
    		$db = JFactory::getDbo();
    		$query = $db->getQuery(true);
    		$query->select('country.*');
    		$query->from('#__bookpro_country AS country');
    		$query->leftJoin('#__bookpro_dest AS dest ON dest.country_id = country.id');
    		$query->leftJoin('#__bookpro_itinerary AS iti ON iti.dest_id = dest.id');
    		$query->where('iti.tour_id='.$tour_id);
    		$query->group('country.id');
    		$db->setQuery($query);
    		$objs = $db->loadObjectList();
    		
    		$html = array();
    		foreach ($objs AS $obj){
    			$html[] = $obj->country_name;
    		}
    		$html = implode(",", $html);
    		
    		return $html;
    		
    	}
    	static function getMinTotalPricePackage($package_id,$start,$end,$field = 'adult'){
    		
    		$db = JFactory::getDbo();
    		$query = 'SELECT r.* FROM #__bookpro_packagerate AS r ';
    		$where = array();
    		if($package_id){
    			$where[] ='r.tourpackage_id = '.$package_id;
    		}
    		if ($start && $end) {
    			$where[] =' r.date BETWEEN '. $db->quote(JFactory::getDate($start)->format('Y-m-d',true)) .' AND ' .$db->quote(JFactory::getDate($end)->format('Y-m-d',true));
    		}
    		$query .= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
    	
    		$query .=' HAVING r.'.$field.' > 0';
    	
    		$db->setQuery($query);
    		$rates =  $db->loadObjectList();
    		$minPrice = array();
    		if (count($rates)) {
    			foreach ($rates as $rate){
    				$minPrice[] = $rate->$field;
    			}
    		}
    		if (!empty($minPrice)) {
    			$returnprice =min($minPrice);
    		}else{
    			$returnprice =0;
    		}
    		return $returnprice;
    		//return min($minPrice);
    	}
    	static function getPackageRateMinPrice($package_id,$from_date,$to_date,$field='adult'){
    		
    		AImporter::helper('date');
    		$start = JFactory::getDate($from_date)->format('d-m-Y',true);
    		$end = JFactory::getDate($to_date)->format('d-m-Y',true);
    		$numberday = DateHelper::getCountDay($start,$end);
    		$dStart = new JDate($from_date);
    		$total = array();
    		for ($i = 0;$i < $numberday;$i++){
    			$dDate = clone $dStart;
    			$date = $dDate->add(new DateInterval('P'.$i.'D'));
    			$date = JFactory::getDate($date)->format('d-m-Y',true);
    		
    			$price=(int) TourHelper::getPackageRatePrice($package_id, $date,$field);
    			if($price){
    				$total[] = $price;
    			}
    		}
    		if (!empty($total)) {
    			return min($total);
    		}else{
    			return 0;
    		}
    	}
		static function getPackageRatePrice($package_id,$date,$field = 'adult'){
			$date = JFactory::getDate($date)->toSql();
			$db = JFactory::getDbo();
			$query = 'SELECT obj.* FROM #__bookpro_packagerate AS obj ';
			
			$where = array();
			
			if($package_id){
				$where[] = 'obj.tourpackage_id='.$package_id;
			}else{
				$where[] = 'obj.tourpackage_id IS NULL';
			}
			if ($date) {
				$where[] = '`obj`.`date`='.$db->quote($date);
			}
			$query .= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
			
			$db->setQuery($query);
			$obj = $db->loadObject();
			$price = (int) $obj->$field;
			return $price;
		}
        static function getPrice($price,$groups){
            AImporter::model('cgroup');
            $total=0;
            for ($i = 0; $i < count($groups); $i++) {
                $model=new BookProModelCGroup();
                $model->setId($groups[$i]);
                $group=$model->getObject();
                $total+=($price*$group->discount)/100;
            }
            return $total;
        }
        static function getactivitiesByTourID($tour_id){
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('act.title');
            $query->from('#__bookpro_activities  as act');
            $query->leftJoin('#__bookpro_touractivity as touract ON touract.activity_id=act.id');
            $query->where('fachotel.touract='.$tour_id);
            $db->setQuery($query);

            $list=$db->loadColumn();

            return $list;

        }

        static function getRoomType($order_id){
            AImporter::model('orderinfos','roomtype');
            $infomodel=new BookProModelOrderinfos();
            $infomodel->init(array('order_id'=>$order_id,'type'=>"ROOMTYPE"));
            $infos=$infomodel->getData();
            foreach ($infos as $info){
                $roomtypeModel=new BookProModelRoomType();
                $roomtypeModel->setId($info->obj_id);
                $info->roomtype=$roomtypeModel->getObject();
            }
            return $infos;
        }

        static function getMinPrice($tour_id){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('MIN(p.price)');
            $query->from('#__bookpro_tour_package AS p');
            $query->where("p.tour_id = " . $db->quote($tour_id));
            $sql = (string)$query;
            $db->setQuery($sql);
            return $db->loadResult();
        }
        static function getGroupPrice($tour_id,$packagetype_id){

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__bookpro_tour_package AS p');
            $query->where("p.tour_id = " . $db->quote($tour_id))->where("p.packagetype_id = ".$packagetype_id);
            $query->order('min_person ASC');
            $sql = (string)$query;
            $db->setQuery($sql);
            return $db->loadObjectList();

        }
        static function getPackages($tour_id){

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('p.*,tp.id AS package_id');
            $query->from('#__bookpro_packagetype AS p');
            $query->innerJoin('#__bookpro_tour_package AS tp ON p.id=tp.packagetype_id');
            $query->where("tp.tour_id = " . $db->quote($tour_id));
            $query->group('p.id');
            $query->order('p.ordering');
            $sql = (string)$query;
            $db->setQuery($sql);
            return $db->loadObjectList();
        }
        
        function buildThemesTour($tour_id){
            if (! class_exists('BookProModelCategories')) {
                AImporter::model('categories');
            }
            $model = new BookProModelCategories();
            $items = $model->getCategoryByTour($tour_id);
            $themes='';
            if(count($items)>0){
                foreach ($items as $item){
                    $themes.= '<span class="label label-info">'. $item->title . '</span>';
                }
            }
            return $themes;
        }
        function buildCategoryTour($tour_id){
            if (! class_exists('BookProModelCategories')) {
                AImporter::model('categories');
            }
            $model = new BookProModelCategories();
            $items = $model->getCategoryByTour($tour_id);
            $themes='';
            if(count($items)>0){
                $i = 1;
                foreach ($items as $item){
                    $themes.=  $item->title;
                    if ($i < count($items)) {
                        $themes.='-';
                    }
                    $i++;
                }
            }
            return $themes;
        }

        function formatprice($price){



        }
        static function buildDuration($duration){
            if (! class_exists('BookProModelCategory')) {
                AImporter::model('category');
            }
            $model = new BookProModelCategory();
            $item = $model->setId($duration);
            $item =$model->getObject();
            return $item->title;
        }
        static function buildActivityTour($tour_id){
            if (! class_exists('BookProModelActivities')) {
                AImporter::model('activities');
            }
            $model = new BookProModelActivities();
            $items = $model->getActivityByTour($tour_id);
            $themes='';
            if(count($items)>0){
                $i = 1;
                foreach ($items as $item){

                    $themes.=  $item->title;
                    if ($i < count($items)) {
                        $themes.= '-';
                    }
                    $i++;
                }
            }
            return $themes;
        }

        static function getTourDestination($tour_id){
            if (! class_exists('BookProModelItineraries')) {
                AImporter::model('itineraries');
            }
            $model = new BookProModelItineraries();
            $lists=array('order'=>'ordering','state'=>'1','tour_id'=>$tour_id);
            $model->init($lists);
            $items = $model->getData();
            $iti_sum=array();
            if(count($items)>0){
                //$i = 1;
                for ($i = 0; $i < count($items); $i++) {
                    //$iti_sum.='<span class="label label-info">'.$items[$i]->dest_name.'</span>';
                    $iti_sum[]=$items[$i]->dest_name;
                   /* if ($i < count($items)) {
                        $iti_sum .='-';
                    }
                    $i ++;*/
                }

            }
            return implode("-", $iti_sum);

        }
        public static function  buildItinerary($tour_id) {
            if (! class_exists('BookProModelItineraries')) {
                AImporter::model('itineraries');
            }
            $result=array();
            $model = new BookProModelItineraries();
            $lists=array('order'=>'ordering','state'=>'1','tour_id'=>$tour_id);
            $model->init($lists);
            $items = $model->getData();
            $iti_sum='';
            $iti_detail='';
            $airlinelogo=array();
            if(count($items)>0){
                for ($i = 0; $i < count($items); $i++) {
                    $iti_sum.='<span>'.$items[$i]->dest_name.'</span>';
                    $iti_detail.='<h2>'.$items[$i]->title.'</h2>';
                    $iti_detail.='<p>'.$items[$i]->desc.'</p>';
                }

            }
            $result['iti_sum']=substr($iti_sum,0, strlen($iti_sum)) ;
            $result['iti_detail']=$iti_detail;
            $result['airline_logo']=implode(',',$airlinelogo);

            return $result;
        }

        static function getBookedTour($order_id){
            $db = JFactory::getDbo();
            $query = "
            SELECT t.*, pt.title AS package_name,pt.price AS package_price FROM
            `#__bookpro_orderinfo` AS oi,
            `#__bookpro_tour_package` AS pt,
            `#__bookpro_tour` AS t WHERE oi.obj_id = pt.id AND pt.tour_id = t.id AND oi.order_id =".$order_id;
            $db->setQuery($query);
            $rows = $db->loadObject();
            return $rows;
        }

        static function getRoomTypeByPackage($package_id){
            $db =& JFactory::getDBO();
            $sql='SELECT *  FROM #__bookpro_roomtype AS rt INNER JOIN #__bookpro_roomtype_tourpackage AS rtpk ON rt.id=rtpk.roomtype_id';
            $sql.=' WHERE rtpk.tourpackage_id='.$package_id;
            $db->setQuery($sql);
            return $db->loadObjectList();
        }

        static function formatDepartDate($date){
            AImporter::helper('date');
            $dateArr=explode(';', $date);
            $strDate='';
            for ($i = 0; $i < count($dateArr); $i++) {
                $temp=JFactory::getDate($dateArr[$i]);
                if($temp > JFactory::getDate())
                {

                    $strDate.='<span class="depart_date">'.DateHelper::formatDate($dateArr[$i],'D, d-m-Y').'</span>';
                }
            }
            return $strDate;
        }
        static function getSelectDepartDate($start){
            AImporter::helper('date');
            $option=array();
            $startarr=explode(',',JString::trim($start));

            for ($i = 0; $i < count($startarr); $i++) {
                $temp=JFactory::getDate($startarr[$i]);
                if($temp > JFactory::getDate())
                {
                    $check_in=JFactory::getDate($startarr[$i]);
                    $option[] = JHTML::_( 'select.option', $startarr[$i],JHtml::_ ('date',$check_in,'D, d-m-Y'));
                }
            }
            if(count($option)>0)
                return JHtml::_('select.radiolist', $option, 'depart', 'class="inputbox" ', 'value', 'text', '', '' );
            else {
                return JText::_('COM_BOOKPRO_UNAVAILABLE');
            }

        }
        function getPackageObj($tour_id){
            if (! class_exists('BookProModelTourPackages')) {
                AImporter::model('tourpackages');
            }
            if (! class_exists('BookProModelPackagePrices')) {
                AImporter::model('packageprices');
            }
            $result=array();
            $model = new BookProModelTourPackages();
            $lists=array('tour_id'=>$tour_id);
            $model->init($lists);
            $packages=$model->getData();


            for ($i = 0; $i < count($packages); $i++) {
                $priceModel=new BookProModelPackagePrices();
                $package=$packages[$i];
                $plists=array('package_id'=>$package->id);
                $priceModel->init($plists);
                $prices=$priceModel->getData();
                $package->prices=$prices;
            }
            return $packages;

        }
        static function getDeparture($tour){

            if($tour->departure_id){
                JTable::addIncludePath(JPATH_COMPONENT_BACK_END.'/tables');
                $item = JTable::getInstance('airport', 'table');
                return $item;
            }
            else return null;

        }
        static function getPickup($id){
            if (! class_exists('BookProModelCategory')) {
                AImporter::model('category');
            }
            $model=new BookProModelCategory();
            $model->setId($id);
            return $model->getObject();
        }
        static function getDownloadFiles($files){
            $files = explode(';', $files);
            $result='';
            if(count($files)>0)
                for ($i = 0; $i < count($files); $i++) {
                    $file = explode('::',$files[$i]);
                    $url=JURI::base().'components/com_bookpro/files/'.$file[0];
                    $result.='<span>'.JHtml::link($url,'Download').'</span>';
            }
            return $result;
        }
       
       static function getMealOverview($meal){
       		$meals = explode(";", $meal);
       		$html = array();
       		if (!empty($meals)){
       			foreach ($meals as $m){
       				$html[] = '1'.strtoupper($m);
       			}
       		}
       		
       		$str = implode(", ", $html);
       		return $str;
       }
       static function  getMealIntinerary($meal){
       		$meals = explode(";", $meal);
       		$html = array();
       		foreach ($meals as $m){
       			$html[] = TourHelper::getMeal(strtolower($m));
       		}
       		$str = implode(", ", $html);
       		return $str;
       }
       static function getMeal($m){
       		$meals = TourHelper::getMealList();
       		$html = "";
       		foreach ($meals as $meal){
       			if ($meal->id == $m) {
       				$html .="1".$meal->title;
       			}
       		}
       		return $html;
       }
        static function getMealList(){
            $lists=array();
            $meal=new stdClass();
            $meal->id='b';
            $meal->title=JText::_('COM_BOOKPRO_MEAL_BREAKFAST');
            $lists[]=$meal;
            $meal=new stdClass();
            $meal->id='l';
            $meal->title=JText::_('COM_BOOKPRO_MEAL_LUNCH');
            $lists[]=$meal;
            $meal=new stdClass();
            $meal->id='d';
            $meal->title=JText::_('COM_BOOKPRO_MEAL_DINNER');
            $lists[]=$meal;
            return $lists;
        }
        static function formatTourType($type){
            if($type=="shared"){
                return JText::_('COM_BOOKPRO_TOUR_SHARED_GROUP');
            }else{
                return JText::_('COM_BOOKPRO_TOUR_PRIVATE');
            }	
        }
        static function getTours(){      
            $db=JFactory::getDbo();
            $query  = 'SELECT obj.* ';
            $query .= 'FROM #__bookpro_tour  as obj '; 
            $query .= 'WHERE obj.state=1'; 

            $db->setQuery($query);         
            return $db->loadObjectList();    
        }

        static function getTourPackagesByTourId($tour_id){      
            $db=JFactory::getDbo();
            $query = 'SELECT `obj`.*, `tour`.`title` as `tour_name`,`packagetype`.`title` as `packagetype_name`,CONCAT(`packagetype`.`title`," ",`obj`.`min_person`) AS `packagetitle` ';           
            $query .= 'FROM #__bookpro_tour_package  as obj ';                                                              
            $query .= 'LEFT JOIN `#__bookpro_tour` AS `tour` ON `tour`.`id` = `obj`.`tour_id` ';
            $query .= 'LEFT JOIN `#__bookpro_packagetype` AS `packagetype` ON `packagetype`.`id` = `obj`.`packagetype_id` ';
            $query .= 'WHERE obj.tour_id ='.$tour_id;   
            $query .= ' ORDER BY ordering';  
            $db->setQuery($query);         
            return $db->loadObjectList();    
        }

        static function getHotelByPackageId($package_id){      
            $db=JFactory::getDbo();
            $query  = 'SELECT packagehotel.*, hotel.* ';
            $query .= 'FROM #__bookpro_packagehotel  as packagehotel ';
            $query .= 'LEFT JOIN #__bookpro_hotel as hotel ON hotel.id=packagehotel.hotel_id ';
            $query .= 'WHERE packagehotel.package_id ='.$package_id; 
            $query .= ' ORDER BY packagehotel.order_hotel ASC ';    
            $db->setQuery($query);         
            return $db->loadObjectList();

        }
        

            
        static function getTourPackageById($package_id){      
            $db=JFactory::getDbo();
            $query = 'SELECT `obj`.*,`tour`.`title` AS `tour_title`, `ptype`.`title` AS `type_title`,CONCAT(`ptype`.`title`," ",`obj`.`min_person`) AS `packagetitle`  FROM `#__bookpro_tour_package` AS `obj` ';
            $query .= 'LEFT JOIN `#__bookpro_tour` AS `tour` ON `tour`.`id` = `obj`.`tour_id` ';
            $query .= 'LEFT JOIN `#__bookpro_packagetype` AS `ptype` ON `ptype`.`id` = `obj`.`packagetype_id` ';
            $query .= 'WHERE `obj`.`id` = ' . (int) $package_id; 
            $db->setQuery($query);         
            return $db->loadObject();     
        }
        
           static function getPackageHotelByTou_idAndItinerary_idAndPackagetype_id($tour_id, $itinerary_id, $packagetype_id){

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('packagehotel.*');
            $query->from('#__bookpro_packagehotel AS packagehotel');
            $query->leftJoin('#__bookpro_packagetype AS packagetype ON packagetype.id=packagehotel.packagetype_id');
            $query->leftJoin('#__bookpro_itinerary AS itinerary ON itinerary.id=packagehotel.itinerary_id');
            $query->where("itinerary.tour_id = " . $db->quote($tour_id));
            $query->where("packagehotel.itinerary_id = " . $db->quote($itinerary_id));
            $query->where("packagehotel.packagetype_id = " . $db->quote($packagetype_id));
            $sql = (string)$query;
            $db->setQuery($sql);
            return $db->loadObject();
        }
        
        static function getArrayIdPackageHotelsByTou_idAndItinerary_idAndPackagetype_id($tour_id, $itinerary_id, $packagetype_id){

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('`packagehotel`.`id`');
            $query->from('#__bookpro_packagehotel AS packagehotel');
            $query->leftJoin('#__bookpro_packagetype AS packagetype ON packagetype.id=packagehotel.packagetype_id');
            $query->leftJoin('#__bookpro_itinerary AS itinerary ON itinerary.id=packagehotel.itinerary_id');
            $query->where("itinerary.tour_id = " . $db->quote($tour_id));
            $query->where("packagehotel.itinerary_id = " . $db->quote($itinerary_id));
            $query->where("packagehotel.packagetype_id = " . $db->quote($packagetype_id));
            $sql = (string)$query;
            $db->setQuery($sql);
            return $db->loadColumn();
        }
        
            static function getArrayIdHotelsByTou_idAndItinerary_idAndPackagetype_id($tour_id, $itinerary_id, $packagetype_id){

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('`packagehotel`.`hotel_id`');
            $query->from('#__bookpro_packagehotel AS packagehotel');
            $query->leftJoin('#__bookpro_packagetype AS packagetype ON packagetype.id=packagehotel.packagetype_id');
            $query->leftJoin('#__bookpro_itinerary AS itinerary ON itinerary.id=packagehotel.itinerary_id');
            $query->where("itinerary.tour_id = " . $db->quote($tour_id));
            $query->where("packagehotel.itinerary_id = " . $db->quote($itinerary_id));
            $query->where("packagehotel.packagetype_id = " . $db->quote($packagetype_id));
            $sql = (string)$query;
            $db->setQuery($sql);
            return $db->loadColumn();
        }
          
}