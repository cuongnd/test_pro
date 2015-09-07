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
class BusHelper {
	
	
	static function getDepartBusStop($route_id){
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('s.*,d.title');
		$query->from('#__bookpro_busstation AS s');
		$query->leftJoin('#__bookpro_dest AS d ON s.dest_id=d.id');
		$query->where('s.bustrip_id ='. $route_id);
		$query->order('ordering ASC LIMIT 0,1');
		$db->setQuery((string)$query);
		$row = $db->loadObject();
		
		return $row;
	}

	static function getBustripParent($bustrip_id){
		AImporter::model('bustrip');
		$model = new BookProModelBusTrip();
		$bustrip = $model->getObjectFullById($bustrip_id);
		$parent_id = 0;
		if ($bustrip->parent_id) {
			$parent_id = $bustrip->parent_id;
		}else{
			$parent_id = $bustrip->id;
		}
		return $parent_id;
	}
    function getFacilities($bus_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('facility.*');
        $query->from('#__bookpro_facility as facility');
        $query->where('facility.object_id='.$bus_id);
        $query->where('facility.type="bus"');
        $db->setQuery($query);
        return $db->loadObjectList();

    }
	static function convertArrToObj($arrs){
		$obj = new stdClass();
		foreach ($arrs as $key=>$value){
			$obj->$key = $value;
		}
		return $obj;
	}
	static function getPrice($price,$passengers,$roundtrip = 0){
		
		
		$total=0;
		for ($i = 0; $i < count($passengers); $i++) {
			
			if ($roundtrip == 0) {
				if ($passengers[$i]['group_id'] == 1) {
					$total += $price->discount ? $price->discount : $price->adult;
					
				}elseif ($passengers[$i]['group_id'] == 2){
					$total += $price->child;
				}elseif ($passengers[$i]['group_id'] == 3){
					$total += $price->infant;
				}
				
			}else{
				
				if ($passengers[$i]['group_id'] == 1) {
					$total +=  $price->adult_roundtrip;
				}elseif ($passengers[$i]['group_id'] == 2){
					$total += $price->child_roundtrip;
				}elseif ($passengers[$i]['group_id'] == 3){
					$total += $price->infant_roundtrip;
				}
			}
		}
		
		return $total;
	}
    static function getTotalBookBus($obj_id,$date){

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

    /**
	 * 
	 * @param unknown 
	 * @return multitype:mixed
	 */
	static function getAgentBustrip($bustrip_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('bustrip.*,agent.id AS agent_id');
		$query->from('#__bookpro_bustrip AS bustrip');
		$query->join('LEFT', '#__bookpro_bus AS bus ON bus.id = bustrip.bus_id');
		$query->join('LEFT', '#__bookpro_agent AS agent ON bus.agent_id = agent.id');
		if ($bustrip_id) {
			$query->where('bustrip.id = '.$bustrip_id);
		}
		$db->setQuery($query);
		$agent = $db->loadObject();
		return $agent;
		
		
	}
	static function getPriceBag($qty,$agent_id){
		$db = JFactory::getDbo();
		
		if ((int) $qty) {
			
			$query = $db->getQuery(true);
			$query->select('baggage.*');
			$query->from('#__bookpro_baggage AS baggage');
			$query->where('baggage.qty='.$qty);
			if ($agent_id) {
				$query->where('baggage.agent_id='.$agent_id);
			}
			$db->setQuery($query);
			$baggage = $db->loadObject();
			$price = $baggage->price;
		}else{
			$price = 0;
		}
		
		
		
		
		return $price;
	}
	static function getPriceBags($passengers,$bustrip_id,$roundtrip = 0){
		$agent = BusHelper::getAgentBustrip($bustrip_id);
		
		$total = 0;
		if (count($passengers)) {
			for ($i =0;$i < count($passengers);$i++){
				if ($roundtrip == 1) {
					$total += BusHelper::getPriceBag($passengers[$i]['return_bag_qty'], $agent->agent_id);
				}else{
					
					$total += BusHelper::getPriceBag($passengers[$i]['bag_qty'], $agent->agent_id);
				}
				
			}
		}
		return $total;
	}
	static function getDepartLocation(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('d.id,d.title');
		$query->from('#__bookpro_dest AS d');
		$query->innerJoin('#__bookpro_bustrip AS f on f.from=d.id');
		$query->where('d.state=1');
		$query->order('d.ordering ASC');
		$query->group('f.from');
		$sql = (string)$query;
		$db->setQuery($sql);
		return $db->loadObjectList();
	}
	/**
	 * Get Route pair 
	 * @param unknown $from
	 * @param unknown $to
	 * @return Ambigous <mixed, NULL, multitype:unknown mixed >
	 */
	static function getRoutePair($from,$to){
		
		$db = JFactory::getDBO();
		$query= "SELECT * FROM #__bookpro_dest AS h WHERE h.id =". $from;
		$query.=' UNION ';
		$query.= "SELECT * FROM #__bookpro_dest AS h1 WHERE h1.id =". $to;
		$db->setQuery($query);
		return $db->loadObjectList();
		
	}
	static function getArrivalLocation(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('d.id,d.title');
		$query->from('#__bookpro_dest AS d');
		$query->innerJoin('#__bookpro_bustrip AS f on f.to=d.id');
		$query->where('d.state=1');
		$query->order('d.ordering ASC');
		$query->group('f.to');
		$sql = (string)$query;
		$db->setQuery($sql);
		return $db->loadObjectList();
	}
	
	static function getSMSContent($order_id){
		
		
		if (! class_exists('BookProModelBustrip')) {
			AImporter::model('bustrip');
		}
		if (! class_exists('BookProModelOrderInfos')) {
			AImporter::model('orderinfos');
		}
		
		$orderModel=new BookProModelOrder();
		$orderModel->setId($order_id);
		$order=$orderModel->getObject();
		
		$omodel=new BookProModelOrderInfos();
		$lists=array('order_id'=>$order->id);
		$omodel->init($lists);
		$orderinfo=$omodel->getData();
		
		$content=JText::sprintf('COM_BOOKPRO_BUS_TICKET_CODE',$order->order_number).PHP_EOL;
		$content.=JText::sprintf('COM_BOOKPRO_BUS_TICKET_CUSTOMER_NAME',$order->firstname).PHP_EOL;
		$content.=JText::sprintf('COM_BOOKPRO_BUS_TICKET_ROUTE',$order->from_name).PHP_EOL;
		
		if (count($orderinfo)>0){
			foreach ($orderinfo as $subject)
			{
				$fmodel=new BookProModelBusTrip();
				$bustrip=$fmodel->getObjectByID($subject->obj_id);
				$order_detail.='
						<tr>
						<td style="text-align: center;">'.$bustrip->from_name.'</td>
								<td style="text-align: center;">'.$bustrip->to_name.'</td>
										<td style="text-align: center;">'.$subject->adult.'</td>
												<td style="text-align: center;">'.$subject->child.'</td>
														<td style="text-align: center;">'.CurrencyHelper::formatprice($subject->price).'</td>
																<td style="text-align: center;">'.JFactory::getDate($subject->start)->format('d-m').' '.$bustrip->start.'</td>
																		<td>'.$bustrip->bus_name.'</td></tr>';
			}
		}
		$order_detail.='</table>';
		$input = str_replace('{tripdetail}', $order_detail, $input);
		
		return $input;
	}
	static function getBookBusSeat($bustrip_id,$date,$code,$return = false){
		$arrRoute = BusHelper::getRouteArr($bustrip_id);
		
		$arrLocation = BusHelper::getBookedSeatlocation($date, $bustrip_id,$return);
		
		//$arrLocation = BusHelper::getConvertLocationArr($location);
		
		if (count($arrRoute)) {
			foreach ($arrRoute as $route){
				$obj = BusHelper::getListBustripGroup($code,$route->from,$route->to);
				
				$arrObjLocation = BusHelper::getBookedSeatlocation($date, $obj->id,$return);
				
				//$arrObjLocation = BusHelper::getConvertLocationArr($objLocation);
				
				$result = array_diff($arrObjLocation, $arrLocation);
			
				$arrLocation = array_merge($arrLocation, $result);
			}
		}
		
		
		return $arrLocation;
	}
	static function getObjectFullById($id,$date,$roundtrip = 0){
		AImporter::model('bustrip');
		$model = new BookProModelBusTrip();
		$bustrip = $model->getObjectFullById($id);
		$price = BusHelper::getPriceBustripDate($bustrip->id, $date);
		
		
		if ($roundtrip == 0) {
			$last_price=$price->discount?$price->discount:$price->adult;
		}else{
			$last_price = $price->adult_roundtrip;
		}
		
		
		//$last_price=$bustrip->discount_price?$bustrip->discount_price:$bustrip->price;
		$price->roundtrip = $roundtrip;
		$bustrip->last_price=$last_price;
		$bustrip->price = $price;
		$bustrip->depart=$date;
		
		$stations = BusHelper::getStations($bustrip);
		
		$bustrip->stations = $stations;
		return $bustrip;
	}
	static function getPassengerPrice($price,$group_id,$roundtrip = 0){
		$last_price = 0;
		if ($roundtrip == 0) {
			if ($group_id == 1) {
				$last_price = $price->discount ? $price->discount : $price->adult;
			}elseif ($group_id == 2){
				$last_price = $price->child;
			}elseif ($group_id == 3){
				$last_price = $price->infant;
			}
		}else{
			if ($group_id == 1) {
				$last_price =  $price->adult_roundtrip;
			}elseif ($group_id == 2){
				$last_price = $price->child_roundtrip;
			}elseif ($group_id == 3){
				$last_price = $price->infant_roundtrip;
			}
		}
		return $last_price;
		
	}
	static function refundPrice($orderinfo,$order){
		
		
		$now = new JDate(JHtml::date('now','d-m-Y H:i:s'));
		$start = new JDate($orderinfo->depart_date);
	
		$days = $start->diff($now);
		$hour = $days->days*24+$days->h;
		if (BusHelper::getCheckRefund($hour)) {
			$refund = BusHelper::getRefundHour($hour);
			$price = $order->total - $order->total*$refund->amount/100;
		}else{
			$price = 0;
		}
	
		return $price;
	
	}
	static function getMinRefund($hour = 0){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('MIN(refund.number_hour)');
		$query->from('#__bookpro_refund AS refund');
		$query->state('refund.state = 1');
		if ($hour) {
			$query->where('refund.number_hour >'.$hour);
		}
		$db->setQuery($query);
		$min = $db->loadResult();
		if ($min) {
			return $min;
		}else{
			return 0;
		}
		
	}
	static function getMaxRefund($hour = 0){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('MAX(refund.number_hour)');
		$query->from('#__bookpro_refund AS refund');
		$query->where('refund.state = 1');
		if ($hour) {
			$query->where('refund.number_hour <='.$hour);
		}
		$db->setQuery($query);
		$max = $db->loadResult();
		if ($max) {
			return $max;
		}else{
			return 0;
		}
		
	}
	static function getRangeRefund($hour){
		$max = BusHelper::getMaxRefund();
		$min = BusHelper::getMinRefund();
		
		if ($hour >= $max) {
			return $max;
		}elseif ($hour <= $min){
			return $min;
		}else{
			$preRange = BusHelper::getMaxRefund($hour);
			return $preRange;
		}
	}
	static function getRefundHour($hour){
		
		$number_hour = BusHelper::getRangeRefund($hour);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('refund.*');
		$query->from('#__bookpro_refund AS refund');
		$query->where('refund.number_hour ='.$number_hour);
		
		$db->setQuery($query);
		$refund = $db->loadObject();
		return $refund;
	}
	static function getCheckRefund($hour){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('refund.*');
		$query->from('#__bookpro_refund AS refund');
		//$query->where('refund.number_hour <'.$hour);
		$query->order('refund.number_hour ASC');
		$db->setQuery($query);
		$refund = $db->loadObject();
	
		if ($hour <= $refund->number_hour) {
			return false;
		}else{
			return true;
		}
	
	}
	static function getInFosList($order_id){
		
		AImporter::model('orderinfos','bustrip','passengers');
		
		
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('obj.*');
		$query->from('#__bookpro_passenger AS obj');
		if ($order_id) {
			$query->where('obj.order_id='.$order_id);
		}
		$query->group('obj.route_id');
		$db->setQuery($query);
		$obj = $db->loadObject();
		
		$bustrips=array();
		$bustripModel=new BookProModelBusTrip();
		$bustrip=$bustripModel->getObjectFullById($obj->route_id);
		$price = BusHelper::getPriceBustripDate($bustrip->id, $obj->start);
		$stations = BusHelper::getStations($bustrip);
		$last_price=$price->adult_discount?$price->adult_discount:$price->adult;
		$bustrip->last_price=$last_price;
		$bustrip->price = $price;
		$bustrip->depart_date=$obj->start;
		$bustrip->stations = $stations;
		$bustrip->seat = BusHelper::getCountSeat($bustrip->id, $order_id);
		$location = BusHelper::getBustripLocation($bustrip->id, $order_id);
		$bustrip->location = $location;
		
		$passmodel= new BookproModelpassengers();
		
		$state = $passmodel->getState();
		$state->set('filter.order_id', $order_id);
		$state->set('filter.route_id', $bustrip->id);
		
		
		$passengers = $passmodel->getItems();
		
		$bustrip->passengers = $passengers;
		$bustrip->return = false;
		$bustrips[] = $bustrip;
		if ($obj->return_route_id) {
			$bustripModel=new BookProModelBusTrip();
			$return_bustrip=$bustripModel->getObjectFullById($obj->return_route_id);
			
			$price = BusHelper::getPriceBustripDate($return_bustrip->id, $obj->return_start);
			$stations = BusHelper::getStations($return_bustrip);
			$last_price=$price->adult_discount?$price->adult_discount:$price->adult;
			$return_bustrip->last_price=$last_price;
			$return_bustrip->price = $price;
			$return_bustrip->depart_date=$obj->return_start;
			$return_bustrip->stations = $stations;
			$return_bustrip->seat = BusHelper::getCountSeat($return_bustrip->id, $order_id,true);
			$location = BusHelper::getBustripLocation($return_bustrip->id, $order_id,true);
			$return_bustrip->location = $location;
			$return_bustrip->return = true;
			$passmodel= new BookproModelpassengers();
			
			$state = $passmodel->getState();
			$state->set('filter.order_id', $order_id);
			$state->set('filter.return_route_id', $return_bustrip->id);
			$passengers = $passmodel->getItems();
			$return_bustrip->passengers = $passengers;
			
			$bustrips[] = $return_bustrip;
		}
		//$infomodel=new BookProModelOrderinfos();
		//$infomodel->init(array('order_id'=>$order_id,'ordering'=>'id','order_Dir'=>'ASC'));
		//$infos=$infomodel->getData();
		//$infos = $passmodel->getItems();
		
		
		return $bustrips;
	}
	static function getPassengerFullObject($id,$return = 0){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('pass.*,CONCAT(`pass`.`firstname`," ",`pass`.`lastname`) AS ufirstname');
		$query->from('#__bookpro_passenger AS pass');
		if ($id) {
			$query->where('pass.id='.$id);
		}
		$db->setQuery($query);
		$pass = $db->loadObject();
		AImporter::model('bustrip','order');
		$tripmodel = new BookProModelBusTrip();
		$ordermodel = new BookProModelOrder();
		if ($return == 0) {
			$bustrip = $tripmodel->getObjectFullById($pass->route_id);
		}
		
		if ($return == 1) {
			$bustrip = $tripmodel->getObjectFullById($pass->return_route_id);
		}
		$pass->bustrip = $bustrip;
		$ordermodel->setId($pass->order_id);
		$order = $ordermodel->getObject();
		$pass->order = $order;

		$ticket_id = $order->order_number."-".$bustrip->code."-".$pass->id;
		$pass->ticket_id = $ticket_id;
		$pass->location_seat = $return == 0 ? $pass->seat : $pass->return_seat;
		$pass->depart_date = $return == 0 ? $pass->start : $pass->return_start;
		$pass->pprice = $return == 0 ? $pass->price : $pass->return_price;
		$pass->baggage_qty = $return == 0 ? $pass->bag_qty : $pass->return_bag_qty;
		$pass->baggage_price = $return == 0 ? $pass->price_bag : $pass->return_price_bag;
		$stations = BusHelper::getStations($bustrip);
		$pass->stations = $stations;
		return $pass;
		
	}
	static function getCountSeat($bustrip_id,$order_id,$return = false){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(obj.group_id)');
		$query->from('#__bookpro_passenger AS obj');
		$query->where('obj.order_id='.$order_id);
		if (!$return) {
			$query->where('obj.route_id='.$bustrip_id);
		}else{
			$query->where('obj.return_route_id='.$bustrip_id);
		}
		$db->setQuery($query);
		return $db->loadResult();
	}
	static function getBustripLocation($bustrip_id,$order_id,$return = false){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('obj.*');
		$query->from('#__bookpro_passenger AS obj');
		$query->where('obj.order_id='.$order_id);
		if (!$return) {
			$query->where('obj.route_id='.$bustrip_id);
		}else{
			$query->where('obj.return_route_id='.$bustrip_id);
		}
		$db->setQuery($query);
		$locations = array();
		$items = $db->loadObjectList();
		foreach ($items as $item){
			if (!$return){
				$locations[] = $item->seat;
			}else{
				$locations[] = $item->return_seat;
			}
			
		}
		$location = implode(",", $locations);
		return $location;
	}

	static function getBustripSearch($lists,$roundtrip = 0,$return = false){
		AImporter::model('bustripsearch');
		$model = new BookProModelBustripSearch();
		$model->init($lists);
		$items = $model->getData();

		$bustrips = array();
		for ($i = 0; $i < count($items); $i++) {
			// count available seats
            $items[$i]->bus_facilities=BusHelper::getFacilities($items[$i]->bus_id);
            $bustrips[] = $items[$i];
		}
		return $bustrips;
		
	}
	static function getBustripSearchstyl1($lists,$roundtrip = 0,$return = false){
		AImporter::model('bustripsearch');
		$model = new BookProModelBustripSearch();
		$model->init($lists);
		$items = $model->getData();
		$bustrips = array();
		for ($i = 0; $i < count($items); $i++) {
            $bustrips[] = $items[$i];





		}
		return $bustrips;

	}

	static function getConvertLocationArr($location){
		$str = str_replace(array('[', ']','"'), '', $location);
		$array_deny_select=explode(',', trim($str));
		return $array_deny_select;
	}
	function getBookedSeat($date, $obj_id,$return=false) {
		AImporter::helper('paystatus');
		PayStatus::init();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(obj.group_id) AS seat');
		$query->from('#__bookpro_passenger AS obj');
		$query->join('LEFT', '#__bookpro_orders AS `order` ON obj.order_id = `order`.`id`');
		if ($date) {
			$start = JHtml::date($date);
			$query->where('obj.start='.$db->quote($start));
		}
		if ($obj_id) {
			$query->where('obj.route_id='.$obj_id);
		}

		$query->where('`order`.`pay_status`='.$db->quote(PayStatus::$SUCCESS->getValue()));
		$db->setQuery($query);
		return $db->loadResult();

	}

	static function getBookedSeatlocation($date,$obj_id,$return=false){
		AImporter::helper('paystatus');
		PayStatus::init();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('obj.*');
		$query->from('#__bookpro_passenger AS obj');
		$query->join('LEFT', '#__bookpro_orders AS `order` ON obj.order_id = `order`.`id`');
		if (!$return) {
			if ($date) {
				$start = JFactory::getDate($date)->toSql();
				$query->where('obj.start='.$db->quote($start));
			}
			if ($obj_id) {
				$query->where('obj.route_id='.$obj_id);
			}
		}else{
			if ($date) {
				$start = JFactory::getDate($date)->toSql();
				$query->where('obj.return_start='.$db->quote($start));
			}
			if ($obj_id) {
				$query->where('obj.return_route_id='.$obj_id);
			}
		}
		$query->where('`order`.`pay_status`='.$db->quote(PayStatus::$SUCCESS->getValue()));


		$db->setQuery($query);
		$items = $db->loadObjectList();

		$locations = array();
		if (count($items)) {
			foreach ($items as $item){
				if (!$return) {
					$locations[] = $item->seat;
				}else{
					$locations[] = $item->return_seat;
				}

			}
		}
		return $locations;
	}

	static function getRouteArr($bustrip_id){

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('bustrip.*');
		$query->from('#__bookpro_bustrip AS bustrip');
		$query->where('id='.$bustrip_id);
		$db->setQuery($query);
		$bustrip = $db->loadObject();

		$arrRoute = explode(";", $bustrip->route);

		$keyFrom = array_search($bustrip->from, $arrRoute);
		$keyTo = array_search($bustrip->to, $arrRoute);



		$arrFrom = BusHelper::getArrDestRoute(0, $keyFrom, $arrRoute);
		$arrTo = BusHelper::getArrDestRoute($keyTo, count($arrRoute)-1, $arrRoute);

		$routes = BusHelper::getArrFromTo($arrFrom, $arrTo);

		return $routes;
	}
	static function getStations($bustrip){

		$arrRoute = explode(";", $bustrip->route);

		$startRoute = $bustrip->from;
		$keyFrom = array_search($bustrip->from, $arrRoute);

		$arrFrom = BusHelper::getArrDestRoute($keyFrom+1, count($arrRoute)-1, $arrRoute);


		$routes = BusHelper::getStationRoute($bustrip->from, $arrFrom);

		$stations = array();

		foreach ($routes as $route){

			$obj = BusHelper::getListBustripGroup($bustrip->code,$route->from,$route->to);

			$stations[] = $obj;
		}

		return $stations;

	}
	static function getStationRoute($from,$arrRoute){
		$arr = array();
		for ($i = 0;$i < count($arrRoute);$i++){
			$obj = new stdClass();
			$obj->from = $from;
			$obj->to = $arrRoute[$i];
			$arr[] = $obj;
		}
		return $arr;
	}

	static function getArrFromTo($arrFrom,$arrTo){
		$arr = array();
		foreach ($arrFrom as $from){
			foreach ($arrTo as $to){
				$obj = new stdClass();
				$obj->from = $from;
				$obj->to = $to;
				$arr[] = $obj;
			}
		}
		return $arr;
	}

	static function getArrDestRoute($start,$end,$arr){
		$arrDest = array();
		
		if ($start < $end) {
			
			for ($i = (int)$start;$i <= (int)$end;$i++){
				//var_dump($i);
				$arrDest[] = $arr[$i];
			}	
		}

		if ($start == $end) {
			
			$arrDest[] = $arr[$start];
		}
		
		
		
		return $arrDest;
	}
	static function getPriceBustripDate($bustrip_id,$date,$field = 'adult'){
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('rate.*');
		$query->from('#__bookpro_roomrate AS rate');
		if ($bustrip_id) {
			$query->where('rate.room_id='.$bustrip_id);
		}
		
		if ($date){
			$busdate = JFactory::getDate($date)->toSql();
			$query->where('rate.date ='.$db->quote($busdate));
		}
		
		if ($bustrip_id && $date) {
			$db->setQuery($query);
			$price = $db->loadObject();
			
		}else{
			$price = new stdClass();	
		}
		
		return $price;
		
	}
	static function getListBustripGroup($code,$from,$to){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		//$query->select('bustrip.*');
		$query->select('bustrip.*,`agent`.`brandname` AS `brandname`,agent.company, `bus`.`id` AS `bus_id_`,`seattemplate`.`id` AS `seattemplate_id`,`seattemplate`.`block_layout` AS `block_layout`,  `dest1`.`title` as `fromName`, `dest2`.`title` as `toName`');
		$query->select('`bus`.`seat` AS `bus_seat`, `bus`.`title` as `bus_name`, `bus`.`desc` as `bus_sum`,CONCAT(`dest1`.`title`,'.$db->quote('-').',`dest2`.`title`) AS title,`bustrip`.`id` AS b_id');
		
		$query->from('#__bookpro_bustrip AS bustrip');
		$query->join("LEFT", '#__bookpro_dest AS `dest1` ON `bustrip`.`from` = `dest1`.`id`');
		$query->join("LEFT", '#__bookpro_dest AS `dest2` ON `bustrip`.`to` = `dest2`.`id`');
		$query->join('LEFT', '#__bookpro_bus AS `bus` ON `bus`.`id` = `bustrip`.`bus_id`');
		$query->join('LEFT', '#__bookpro_agent AS agent ON `agent`.`id` = `bus`.`agent_id`');
		$query->where(array('bustrip.code='.$db->quote($code),'bustrip.from='.$from,'bustrip.to='.$to));


		$db->setQuery($query);
		
		
		$bustrip = $db->loadObject();
		
		return $bustrip;
	}
}