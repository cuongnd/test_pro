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

    class HotelHelper {
        /**
        *
        * @param int $room_count
        * @param date $checkin
        * @param date $checkout
        * Tra ve danh sach khach san co tong so phong trong khoang thoi gian lon hon hoac bang $room_count
        */
        static function getHotelAvailable($checkin,$checkout,$room_count = 0){
            AImporter::model('hotels');
            $db = JFactory::getDbo();
            $query = 'SELECT * FROM #__bookpro_hotel WHERE state = 1';
            $db->setQuery($query);
            $hotels = $db->loadObjectList();
            //$model = new BookProModelHotels();
            //$lists = array('state'=>1);
            //$model->init($lists);
            $no_hotel = array();
            //$hotels = $model->getData();

            if ($hotels) {
                foreach ($hotels as $hotel){

                    $room = HotelHelper::getRoomTotalHotelId($hotel->id, $checkin, $checkout);
					//$rate = HotelHelper::getRoomRateHotelId($hotel->id, $checkin, $checkout);
					//var_dump($rate);
                    if ($room >= $room_count && $rate > 0) {
                        $no_hotel[] = $hotel->id;
                    }
                }    
            }

            return $no_hotel;
        }
        static function getLastbookingdate($hotel_id)
        {
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('orderbooking.created');
            $query->from('#__bookpro_orders AS orderbooking');
            $query->leftJoin('#__bookpro_orderinfo AS orderinfo ON orderinfo.order_id=orderbooking.id');
            $query->leftJoin('#__bookpro_room AS room ON room.id=orderinfo.obj_id');
            $query->leftJoin('#__bookpro_hotel AS hotel ON hotel.id=room.hotel_id');
            $query->where('orderinfo.type="HOTEL_ROOM"');
            $query->where('hotel.id='.$hotel_id);
            $query->order('orderbooking.created');
            $db->setQuery($query,0,1);
           
            $lastoder=$db->loadObject();
           
            return $lastoder;
        }
        static function getFacilitiesByHotelID($hotel_id){
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('fac.*');
            $query->from('#__bookpro_facility as fac');
            $query->leftJoin('#__bookpro_hotelfacility AS hotelfac ON hotelfac.facility_id=fac.id');
            $query->where('hotelfac.hotel_id='.$hotel_id);
            $query->where('fac.ftype=0');
            $db->setQuery($query);
            $list=$db->loadObjectList();

            return $list;

        }
        static function getFacilitiesBox($hotel_id){
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('fac.*');
            $query->from('#__bookpro_facility as fac');
            $query->innerjoin('#__bookpro_hotelfacility as fachotel ON fachotel.facility_id=fac.id');
            $query->where('fachotel.hotel_id='.$hotel_id);
            $db->setQuery($query);

            $list=$db->loadObjectList();

            return $list;

        }
        static function getHotelbyRoomID($room_id){
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('hotel.*');
            $query->from('#__bookpro_hotel as hotel');
            $query->innerjoin('#__bookpro_room as room ON room.hotel_id=hotel.id');
            $query->where('room.id='.$room_id);
            $db->setQuery($query);
            $hotel=$db->loadObject();
            return $hotel;

        }
        static function checkOutDate($date){
            $checkout = new JDate($date);
            return JFactory::getDate($checkout->modify('+1 day'))->format('d-m-Y');
        }

        /**
        * 
        * @param int $hotel_id
        * @param date $checkin
        * @param date $checkout
        * 
        * Tra ve danh sach room theo khach san, moi ban ghi se chua so luong room
        * 
        */
        static function getRoomAvailable($hotel_id, $checkin, $checkout){
            AImporter::model('rooms');
            $model = new BookProModelRooms();

            $lists = array('state'=>1,'hotel_id'=>$hotel_id);
            $model->init($lists);
            $rooms = $model->getData();
            //$arooms = array();
            foreach ($rooms as $room){
                $total = $room->quantity - HotelHelper::getDateRoom($room->id, $checkin, $checkout);
                $price = HotelHelper::getRoomRateMinPrice($room->id, $checkin, $checkout);
                $total_price = HotelHelper::getRoomRateTotalPrice($room->id, $checkin, $checkout);
                $room->total = $total;
                $room->price = $price;
                $room->total_price = $total_price;
                //$arooms[] = $room;
            }
            return $rooms;

        }
        static function getRoomRateMinPrice($room_id,$checkin,$checkout){
        	AImporter::helper('date');
            $start = JFactory::getDate($checkin)->format('d-m-Y',true);
            $end = JFactory::getDate($checkout)->format('d-m-Y',true);
            $numberday = DateHelper::getCountDay($start,$end);
            $dStart = new JDate($checkin);
            $total = array();
            for ($i = 0;$i < $numberday;$i++){
                $dDate = clone $dStart;
                $date = $dDate->add(new DateInterval('P'.$i.'D'));
                $date = JFactory::getDate($date)->format('d-m-Y',true);

                $total[]=(int) HotelHelper::getRoomRatePriceDate($room_id, $date);
            }
            /*
            $db = JFactory::getDbo();
            $query = 'SELECT SUM(obj.rate) FROM #__bookpro_roomrate AS obj ';
            $where = array();
            if ($room_id) {
            $where[] = 'obj.room_id = '.$room_id;
            }else{
            $where[] = 'obj.rom_id IS NULL';
            }
            if ($checkin) {
            $where[] = 'DATE_FORMAT(`obj`.`date`,"%d-%m-%Y") >= '.$db->quote($start);
            }
            if ($checkout) {
            $where[] = 'DATE_FORMAT(`obj`.`date`,"%d-%m-%Y") <= '.$db->quote($end);
            }
            $query .= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
            $db->setQuery($query);

            return $db->loadResult();
            */

            //return min($total);
            if (!empty($total)) {
            	return min($total);
            }else{
            	return 0;
            }
            
        }
        static function getRoomRateTotalPrice($room_id,$checkin,$checkout){
            $start = JFactory::getDate($checkin)->format('d-m-Y',true);
            $end = JFactory::getDate($checkout)->format('d-m-Y',true);
            $numberday = DateHelper::getCountDay($start,$end);
            $dStart = new JDate($checkin);
            $total = 0;
            for ($i = 0;$i < $numberday;$i++){
                $dDate = clone $dStart;
                $date = $dDate->add(new DateInterval('P'.$i.'D'));
                $date = JFactory::getDate($date)->format('d-m-Y',true);

                $total +=(int) HotelHelper::getRoomRatePriceDate($room_id, $date);
            }
            /*
            $db = JFactory::getDbo();
            $query = 'SELECT SUM(obj.rate) FROM #__bookpro_roomrate AS obj ';
            $where = array();
            if ($room_id) {
            $where[] = 'obj.room_id = '.$room_id;
            }else{
            $where[] = 'obj.rom_id IS NULL';
            }
            if ($checkin) {
            $where[] = 'DATE_FORMAT(`obj`.`date`,"%d-%m-%Y") >= '.$db->quote($start);
            }
            if ($checkout) {
            $where[] = 'DATE_FORMAT(`obj`.`date`,"%d-%m-%Y") <= '.$db->quote($end);
            }
            $query .= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
            $db->setQuery($query);

            return $db->loadResult();
            */

            return $total;
        }
        /*
        var $room: object room
        */

        function CaculatePriceRoom($room,$totaladult,$totalchild)
        {
            $total=0;
            if($totaladult>$totaladult->adult)
            {
                $adult_price=explode(',',$room->adult_price);

                for($i=0;$i<$totaladult-$room->adult;$i++)
                {
                    $total+=$adult_price[$i];
                }

            }

            $child_price=explode(',',$room->child_price);
            for($i=0;$i<$totalchild;$i++)
            {
                $total+=$child_price[$i];
            }


            return $total;
        }

        static function getRoomRatePriceDate($room_id,$date){

            $date = JFactory::getDate($date)->toSql();
            $db = JFactory::getDbo();
            $query = 'SELECT obj.rate FROM #__bookpro_roomrate AS obj ';

            $where = array();

            if($room_id){
                $where[] = 'obj.room_id='.$room_id;
            }else{
                $where[] = 'obj.room_id IS NULL';
            }
            if ($date) {
                $where[] = '`obj`.`date`='.$db->quote($date);
            }
            $query .= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
            $db->setQuery($query);
            $price = $db->loadResult();
            $price = (int) $price;
            return $price;
        }
        static function getRoomTotalHotelId($hotel_id, $checkin, $checkout){
            AImporter::model('rooms');

            $model = new BookProModelRooms();
            $lists = array('hotel_id'=>$hotel_id,'state'=>1);
            $model->init($lists);
            $rooms = $model->getData();

            $total = 0;

            if (count($rooms)) {
                foreach ($rooms as $room){
                    $total +=$room->quantity - HotelHelper::getDateRoom($room->id, $checkin, $checkout);
                }    
            }

            return $total;
        }
        static function getRoomRateHotelId($hotel_id, $checkin, $checkout){
        	AImporter::model('rooms');
        
        	$model = new BookProModelRooms();
        	$lists = array('hotel_id'=>$hotel_id,'state'=>1);
        	$model->init($lists);
        	$rooms = $model->getData();
        
        	$total = 0;
        
        	if (count($rooms)) {
        		foreach ($rooms as $room){
        			$total +=HotelHelper::getRoomRateMinPrice($room->id, $checkin, $checkout);
        		}
        	}
        
        	return $total;
        }

        static function getDateRoom($room_id,$checkin,$checkout){
            $start = new JDate($checkin);
            $end = new JDate($checkout);
            $days = $start->diff($end)->days;

            $total = array();

            if ($days) {
                for ($i = 0;$i <$days;$i++){
                    $dStart = clone $start;

                    $date = $dStart->add(new DateInterval('P'.$i.'D'));
                    $date = JFactory::getDate($date)->format('d-m-Y',true);

                    $total[]=(int) HotelHelper::getTotalBookRoom($room_id, $date);

                }    

            }

            if (!empty($total)) {
                return min($total);
            }else{
                return 0;
            }


        }
        static function getTotalBookRoom($obj_id,$date){

            $date = JFactory::getDate($date)->toSql();
            $db = JFactory::getDbo();
            $query = 'SELECT SUM(obj.qty) FROM #__bookpro_orderinfo AS obj ';
            $query .='LEFT JOIN #__bookpro_orders AS `order` ON `obj`.`order_id` = `order`.`id`';

            $where = array();
            $where[] = "`order`.`pay_status`= 'SUCCESS'";
            if ($obj_id) {
                $where[] = 'obj.obj_id='.$obj_id;
            }
            if ($date) {

                $where[] = '`obj`.`start` <='.$db->quote($date);
                $where[] = '`obj`.`end` >='.$db->quote($date);
            }
            $query .= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';


            $db->setQuery($query);
            return $db->loadResult();

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
        static function getRooms($order_id){
            AImporter::model('orderinfos','room');
            $infomodel=new BookProModelOrderinfos();
            $infomodel->init(array('order_id'=>$order_id,'type'=>"HOTEL_ROOM"));
            $infos=$infomodel->getList();

            foreach ($infos as $info){

                $roomModel=new BookProModelRoom();
                $roomModel->setId($info->obj_id);
                $info->room=$roomModel->getObject();
            }
            return $infos;
        }
        static function getFacilities($order_id){
            AImporter::model('orderinfos','facility');
            $infomodel=new BookProModelOrderinfos();
            $infomodel->init(array('order_id'=>$order_id,'type'=>"HOTEL_FACILITY"));
            $infos=$infomodel->getData();

            foreach ($infos as $info){

                $facModel=new BookProModelFacility();
                $facModel->setId($info->obj_id);
                $info->facility=$facModel->getObject();
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
        static function getMinPriceHotelInMonth($hotel_id){
            $date = new JDate();
            $end = $date->format('t-m-Y',true);
            $startMonth = $date->setDate($date->year, $date->month, 01);
            $startMonth = $date->format('d-m-Y');
            $endMonth = new JDate($end);
            $endMonth = $endMonth->format('d-m-Y');

            $db = JFactory::getDBO();
            $query = 'SELECT * FROM #__bookpro_room AS room ';
            $where = array();
            if ($hotel_id) {
                $where[] = 'room.hotel_id = '.$hotel_id;
            }
            $query .= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';

            $db->setQuery($query);
            $rooms = $db->loadObjectList();
            $price = array();
            if (count($rooms)) {
                foreach ($rooms as $room){
                    $price[] = HotelHelper::getMinTotalPriceRoom($room->id, $startMonth, $endMonth);
                }    
            }

            if (!empty($price)) {
                $returnprice =min($price); 
            }else{
                $returnprice =0;
            }
            return $returnprice;


        }
        static function getMinTotalPriceRoom($room_id,$start,$end){
            $db = JFactory::getDbo();
            $query = 'SELECT r.* FROM #__bookpro_roomrate AS r ';
            $where = array();
            if($room_id){
                $where[] ='r.room_id = '.$room_id;
            }
            if ($start && $end) {
                $where[] =' r.date BETWEEN '. $db->quote(JFactory::getDate($start)->format('Y-m-d',true)) .' AND ' .$db->quote(JFactory::getDate($end)->format('Y-m-d',true));
            }
            $query .= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';

            $query .=' HAVING r.rate > 0';

            $db->setQuery($query);
            $rates =  $db->loadObjectList();
            $minPrice = array();
            if (count($rates)) {
                foreach ($rates as $rate){
                    $minPrice[] = $rate->rate;
                }
            }
            return min($minPrice);
        } 
        static function getGroupPrice($tour_id,$packagetype_id){

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__bookpro_tour_package AS p');
            $query->where("p.tour_id = " . $db->quote($tour_id))->where("p.packagetype_id = ".$packagetype_id);
            $query->order('price DESC');
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
       
        static function getHotelByUserId($user_id){
            $db = JFactory::getDbo();
            $query = 'SELECT room.id FROM #__bookpro_room AS room ';
            $query .='LEFT JOIN #__bookpro_hotel AS hotel ON room.hotel_id = hotel.id ';
            if ($user_id) {
                $query .=' WHERE hotel.userid ='.$user_id;
            }             
            $db->setQuery($query);
            return $db->loadColumn();

        }
        static function getObjectHotelByOrder($order_id){
            $db = JFactory::getDbo();
            $query = 'SELECT `hotel`.*,`city`.`title` AS `city_title` FROM #__bookpro_hotel AS `hotel` ';
            $query .= 'LEFT JOIN `#__bookpro_dest` AS `city` ON `city`.`id`=`hotel`.`city_id` ';
            $query .='LEFT JOIN #__bookpro_room AS `room` ON `hotel`.`id` = `room`.`hotel_id` ';
            $query .='LEFT JOIN #__bookpro_orderinfo AS `info` ON `room`.`id` = `info`.`obj_id` ';
            $query .='WHERE `info`.`order_id` = '.$order_id;
            $query .=' GROUP BY `hotel`.`id`';
            $db->setQuery($query);
            $hotel = $db->loadObject();
            return $hotel;


        }
        static function getOrderByHotelUser($user_id, $startlimit=0, $limit=5){
            $objs = HotelHelper::getHotelByUserId($user_id); 
            $hotels = array();             
            if( count($objs) <=0 ){     
                $objs[]= 0;
            }

            $db = JFactory::getDbo();
            $query = 'SELECT `order`.`id` FROM `#__bookpro_orders` AS `order` ';
            $query .='LEFT JOIN `#__bookpro_orderinfo` AS `oinfo` ON `order`.`id` = `oinfo`.`order_id`';
            if (!empty($objs)) {
                $query .=' WHERE `oinfo`.`obj_id` IN  ('.implode(",", $objs).')';
            }
            $query .=' GROUP BY `order`.`id` ';
            $query .= ' LIMIT '.$startlimit.', '.$limit;
            $db->setQuery($query);
            /*$hotels = $db->loadObjectList();     
            return $hotels;              */

            return $db->loadColumn();

        }
        function formatprice($price){



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
    

        static function displayHotelMap($hotel_id) {
            $link=JUri::root().'index.php?option=com_bookpro&task=displaymap&tmpl=component&hotel_id='.$hotel_id;
            return '<span class="icon-map-marker" style="padding-right:3px"></span><a href="'.$link.'" class="modal_hotel" rel="{handler: \'iframe\', size: {x: 570, y: 530}}">'.JText::_("COM_BOOKPRO_VIEW_MAP").'</a>';
        }

       

        static function getCustomerIdByUserLogin()
        {
            $user = &JFactory::getUser();
            JTable::addIncludePath(JPATH_COMPONENT_BACK_END.'/tables');
            $customer = JTable::getInstance('customer', 'table');
            $customer->load(array('user'=>$user->id));
            if($customer->id){
                return $customer->id;
            }else{
                return "error";
            }
        }    

        function getHotelSelectBoxSearchBySupplier($select)
        { 
            $model = new BookProModelRegisterHotels();
            $lists = array('userid'=>HotelHelper::getCustomerIdByUserLogin());
            $model->init($lists);
            $fullList = $model->getData();    
            if($fullList){
                return JHtmlSelect::genericlist($fullList, 'search_hotel_id','','id','title',$select);
            }else{
                return '';
            }
        }

        function getHotelSelectBoxBySupplier($select)
        { 
            $model = new BookProModelRegisterHotels();
            $lists = array('userid'=>HotelHelper::getCustomerIdByUserLogin());
            $model->init($lists);
            $fullList = $model->getData();                  

            $options[]     = JHTML::_('select.option',  '', '- '.JText::_('COM_BOOKPRO_SELECT_HOTEL').' -', 'id', 'title');  

            $options = array_merge($options, $fullList) ;

            return JHtml::_('select.genericlist',$options,'hotel_id', 'class="required invalid error"', 'id', 'title',$select);

        }        

        static function getSupplierSelect($select=null)
        {     
            AImporter::model('customers');
            $config = AFactory::getConfig(); 
            $model = new BookProModelCustomers();
            $lists = array('group_id'=>$config->supplierUsergroup);
            $model->init($lists);
            $list = $model->getData();
            return AHtml::getFilterSelect('userid', JText::_('COM_BOOKPRO_SELECT_SUPPLIER'), $list, $select, $autoSubmit, '', 'id', 'fullname');       
        }
        static function displayAgentDiscount($hotel){
        $user=JFactory::getUser();
			if(!$user->guest) {
				$config=AFactory::getConfig();
				if(in_array($config->agentUsergroup, $user->groups) ){
					$customer=AFactory::getCustomer();
					if($hotel->agent_comission>0 || $hotel->premium >0 ) {
					if($customer->cgroup_id==1)
					    return JText::sprintf('COM_BOOKPRO_AGENT_DISCOUNT_TXT',$hotel->agent_comission);
					else 
						return JText::sprintf('COM_BOOKPRO_AGENT_DISCOUNT_TXT',$hotel->premium);
				}
				}
			}
        	
        }

}