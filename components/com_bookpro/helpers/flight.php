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
class FlightHelper {
	static function getMinPriceFlight($flight_id,$from_date,$to_date,$field='adult'){
		AImporter::helper('date');
		$start = JFactory::getDate($from_date)->toSql();
		$end = JFactory::getDate($to_date)->toSql();
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('MIN(rate.'.$field.')');
		$query->from('#__bookpro_flightrate AS rate');
		if ($flight_id){
			$query->where('rate.flight_id='.$flight_id);
		}
		$query->where('rate.date BETWEEN '.$db->quote($start).' AND '.$db->quote($end));
		$db->setQuery($query);
		$min = $db->loadResult();
		return $min;
	}
	static function getOrderInfos($order_id){
		
	}
	static function formatPackage($package){
	
		return JText::_('COM_BOOKPRO_FLIGHT_'.strtoupper($package));
	
	}
	static function getMinPriceBySearch($from,$to,$date,$field = 'adult'){
		$fdate = JFactory::getDate($date)->toSql();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('flight.*,rate.adult AS rate_price');
		$query->from('#__bookpro_flight AS flight');
		$query->join('LEFT', '#__bookpro_flightrate AS rate ON rate.flight_id = flight.id');
		$query->group('flight.id');
		$query->having('MIN(rate.adult)');
		$query->where('rate.date ='. $db->quote($fdate));
		$sql ="(".(string) $query.") AS f";
		$query1 = $db->getQuery(true);
		$query1->select('MIN(f.rate_price)');
		$query1->from($sql);
		$db->setQuery($query1);
		return $db->loadResult();
		
		
	}
	static function getFrequencyFlight($frequency){
		$frequencys = explode(",", $frequency);
		AImporter::helper('date');
		$days=DateHelper::dayofweek();
		$date = array();
		foreach ($days as $key=>$day){
			if (in_array($key, $frequencys)) {
				$date[] = $day->value;
			}else{
				$date[] = "-";
			}
		}
		return implode(" ", $date);
	}
	static function getBirthDay($name ='',$attribs= '',$id=''){
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('DD'));
		for($i = 1;$i <=31;$i++){
			$options[] = JHtmlSelect::option($i,$i);
		}
		return JHtmlSelect::genericlist($options, $name,$attribs,'value','text',0,$id);
		 
	}
	static function getBirthMonth($name = '',$attribs = '',$id=''){
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('MM'));
		for ($i = 1;$i <= 12;$i++){
			$options[] = JHtmlSelect::option($i,$i);
		}
		return JHtmlSelect::genericlist($options, $name,$attribs,'value','text',0,$id);
	}
	static function getCountryCode($selected,$name ='phone_code',$attribs){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('country.*');
		$query->select('CONCAT(`country`.`country_code`,'.$db->quote('-').',country.phone_code'.') AS text ');
		
		$query->from('#__bookpro_country AS country');
		$query->where('country.state=1');
		$db->setQuery($query);
		$countries = $db->loadObjectList();
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('COM_BOOKPRO_FLIGHT_CUSTOMER__COUNTRY_CODE'));
		foreach ($countries as $country){
			$options[] = JHtmlSelect::option($country->phone_code,$country->text);
		}
		return JHtmlSelect::genericlist($options, $name,$attribs,'value','text',$selected);
	}
	static function phoneType($selected,$name,$attribs=''){
		$types = array(1=>JText::_('COM_BOOKPRO_FLIGHT_CUSTOMER_MOBILE'),
						2=>JText::_('COM_BOOKPRO_FLIGHT_CUSTOMER_TELEPHONE')
		);
		$options = array();
		foreach ($types as $key=>$type){
			$options[] = JHtmlSelect::option($key,$type);
			
		}
		return JHtmlSelect::genericlist($options, $name,$attribs,'value','text',$selected);
	}
	static function getBirthYear($name='',$attribs = '',$id=''){
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('YY'));
		$date = new JDate('now');
		$year = $date->format('Y');
		$year_min = (int) $year - 90;
		for ($i = $year ;$i >= $year_min;$i--){
			$options[] = JHtmlSelect::option($i,$i);
		}
		return JHtmlSelect::genericlist($options, $name,$attribs,'value','text',0,$id);
	}
	static function getGender() {
		return array(
				array(
					'value'=>'',
					'text'=>JText::_('COM_BOOKPRO_FLIGHT_PASSENGER_TITLE')
				),	
				array(
						'value' => 1,
						'text' => JText::_('COM_BOOKPRO_MALE')
				),
				array(
						'value' => 0,
						'text' => JText::_('COM_BOOKPRO_FEMALE')
				)
		);
	}
	static function getFlightByCart($rate_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('rate.*,flight.airline_id,flight.start,flight.end,flight.duration,`airline`.`image` AS `airline_logo`');
		$query->from('#__bookpro_flightrate AS rate');
		$query->select('CONCAT(airline.code,'.$db->quote(" ").',`flight`.`flightnumber`'.') AS flight_number ');
		$query->select('CONCAT(`dest1`.`title`,'.$db->quote('-').',country1.country_name'.') AS fromName ');
		$query->select('CONCAT(`dest2`.`title`,'.$db->quote('-').',country2.country_name'.') AS toName ');
		$query->select('dest1.code AS fromIATA');
		$query->select('dest2.code AS toIATA');
		$query->join('LEFT', '#__bookpro_flight AS flight ON rate.flight_id=flight.id');
		$query->join('LEFT', '#__bookpro_dest AS dest1 ON flight.desfrom=dest1.id');
		$query->join('LEFT', '#__bookpro_dest AS dest2 ON flight.desto=dest2.id');
		$query->join('LEFT', '#__bookpro_country AS country1 ON dest1.country_id=country1.id');
		$query->join('LEFT', '#__bookpro_country AS country2 ON dest2.country_id=country2.id');
		$query->join('LEFT', '#__bookpro_airline AS airline ON flight.airline_id = airline.id');
		
		if ($rate_id) {
			$query->where('rate.id='.$rate_id);
			
		}
		$query->group('rate.flight_id');
		$db->setQuery($query);
		$flight = $db->loadObject();
		return $flight;
	}
	static function getTopFlightCity($desfrom){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('flight.*,count(flight.desto) AS number_flights');
		$query->select('dest1.title AS fromtitle');
		$query->select('dest2.title AS totitle');
		$query->from('#__bookpro_flight AS flight');
		$query->join('LEFT', '#__bookpro_dest AS dest1 ON flight.desfrom=dest1.id');
		$query->join('LEFT', '#__bookpro_dest AS dest2 ON flight.desto=dest2.id');
		if ($desfrom){
			$query->where('flight.desfrom='.$desfrom);
		}
		$query->group('flight.desto');
		$db->setQuery($query);
		$flights = $db->loadObjectList();
		return $flights;
		
	}
	static function getFlightByCountry($country_id){
		AImporter::helper('date');
		$now = JFactory::getDate('now')->format('d-m-Y');
		$start = DateHelper::dateBeginMonth(strtotime($now));
		
		$end = DateHelper::dateEndMonth(strtotime($now));
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('flight.*');
		$query->select('CONCAT(`dest1`.`title`,'.$db->quote('-').',`dest2`.`title`) AS title');
		$query->from('#__bookpro_flight AS flight');
		$query->join("LEFT", '#__bookpro_dest AS `dest1` ON `flight`.`desfrom` = `dest1`.`id`');
		$query->join("LEFT", '#__bookpro_dest AS `dest2` ON `flight`.`desto` = `dest2`.`id`');
		
		if ($country_id){
			$query->where('dest1.country_id='.$country_id);
		}
		$query->order('RAND()');
		
		$db->setQuery($query,0,4);
		$flights = $db->loadObjectList();
		
		
		
		
		foreach ($flights as $flight){
			$price = FlightHelper::getMinPriceFlight($flight->id, $start, $end);
			
			$flight->price = $price;
		}
		
		return $flights;
		
	}
	static function getPriceCiTyFlight($dest_from,$dest_to){
		AImporter::helper('date');
		$now = JFactory::getDate('now')->format('d-m-Y');
		$start = DateHelper::dateBeginMonth(strtotime($now));
		
		$end = DateHelper::dateEndMonth(strtotime($now));
		
		$price = FlightHelper::getFlightByCity($dest_from, $dest_to,$start,$end);
		
		return $price;	
	}
	
	static function getFlightByCity($dest_from,$dest_to,$start,$end){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('flight.*');
		$query->from('#__bookpro_flight AS flight');
		$query->where('flight.state=1');
		if ($dest_from){
			$query->where('flight.desfrom='.$dest_from);
		}
		if ($dest_to){
			$query->where('flight.desto='.$dest_to);
		}
		$db->setQuery($query);
		$flights = $db->loadObjectList();
		$price_arr = array();
		foreach ($flights as $flight){
			$price = FlightHelper::getMinPriceFlight($flight->id, $start, $end);
			if ($price){
				$price_arr[] = $price;
			}
		}
		
		return min($price_arr);
		
	}
	static function getAirlineByFlightSearch($dest_from,$dest_to){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('airline.*');
		$query->from('#__bookpro_airline AS airline');
		$query->join('LEFT', '#__bookpro_flight AS flight ON flight.airline_id = airline.id');
		
		if ($dest_from) {
			$query->where('flight.desfrom='.$dest_from);
		}
		
		if ($dest_to){
			$query->where('flight.desto='.$dest_to);
		}
		$query->where('flight.state=1');
		$query->group('airline.id');
		$db->setQuery($query);
		$airlines = $db->loadObjectList();
		return $airlines;
	}
	static function countFlightBySearch(){
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		
		if ((int) $cart->roundtrip == 0) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(flight.id)');
			$query->from('#__bookpro_flight AS flight');
			if ($cart->from) {
				$query->where('flight.desfrom='.$cart->from);
			}
			if ($cart->to) {
				$query->where('flight.desto='.$cart->to);
			}
			
			$sstart = JFactory::getDate($cart->start)->toSql();
			$query->where('rate.date = '.$db->quote($sstart));
			$db->setQuery($query);
			return $db->loadResult();
		}else{
			return 0;
		}
		
		
	}
	static function getMinPriceByAirline($airline_id,$date){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('MIN(rate.adult)');
		$query->from('#__bookpro_flightrate AS rate');
		$query->join('LEFT', '#__bookpro_flight AS flight ON rate.flight_id = flight.id');
		$query->join('LEFT', "#__bookpro_airline AS airline ON flight.airline_id = airline.id");
		if ($airline_id) {
			$query->where('airline.id='.$airline_id);
		}
		$sdate = JFactory::getDate($date)->toSql();
		$query->where('rate.date = '.$db->quote($sdate));
		$db->setQuery($query);
		$price = $db->loadResult();
		return $price;
		
	}
	static function getAllAirline(){
		/*
		 * Tim kiem tat ca flight cho truong hop search one way
		 */
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$list=array();
		$list['desfrom']=$cart->from;
		$list['desto']=$cart->to;
		$list['adult']= $cart->adult;
		$list['children'] = $cart->children;
		$list['infant'] = $cart->infant;
		$list['order']='fdate';
		$list['ordering']='ASC';
		$list['min_price'] = $cart->min_price;
		$list['max_price'] = $cart->max_price;
		$list['airline'] = $cart->airline;
		
		$list['min_time'] = $cart->min_time;
		
		$list['max_time'] = $cart->max_time;
		$list['depart_date']=$cart->start;
		
		$flights = FlightHelper::getFlightSearch($list);
		return count($flights);
	}
	static function getAirlineBySearch(){
	/*
	 * Hien thi cac airline dac biet
	 */	
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('airline.*');
		$query->from('#__bookpro_airline AS airline');
		$query->join('LEFT', '#__bookpro_flight AS flight ON flight.airline_id = airline.id');
		$query->join('LEFT', '#__bookpro_flightrate AS rate ON flight.id = rate.flight_id');
		if ($cart->from) {
			$query->where('flight.desfrom='.$cart->from);
		}
		if ($cart->to){
			$query->where('flight.desto='.$cart->to);
		}
		
		
		
		
		$query->where('flight.state=1');
		$query->group('airline.id');
		
		$db->setQuery($query);
		$airlines = $db->loadObjectList();
		$specials = array();
		foreach ($airlines as $airline){
			$min_price = 0;
			if ((int) $cart->roundtrip == 0) {
				
				$min_price +=FlightHelper::getMinPriceByAirline($airline->id, $cart->start);
					
					
			}else{
				$min_price +=FlightHelper::getMinPriceByAirline($airline->id, $cart->start);
				$min_price +=FlightHelper::getMinPriceByAirline($airline->id, $cart->end);
			}
			if ($min_price) {
				$airline->min_price = $min_price;
				$specials[] = $airline;
			}
		}
		return $specials;
	}
	static function getAirlineByRoute($dest_from,$dest_to){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('flight.*,airline.title AS airline_name,airline.image AS airline_logo');
		$query->select('CONCAT(`dest1`.`title`,'.$db->quote('(').',dest1.code,'.$db->quote(')').') AS fromName ');
		$query->select('CONCAT(`dest2`.`title`,'.$db->quote('(').',dest2.code,'.$db->quote(')').') AS toName ');
		$query->from('#__bookpro_flight AS flight');
		
		$query->join('LEFT', '#__bookpro_airline AS airline ON flight.airline_id=airline.id');
		$query->select('CONCAT(`dest1`.`title`,'.$db->quote('(').',dest1.code,'.$db->quote(')').','.$db->quote('-').',`dest2`.`title`,'.$db->quote('(').',dest2.code,'.$db->quote(')').') AS title ');
		$query->join('LEFT', '#__bookpro_dest AS dest1 ON dest1.id = flight.desfrom');
		$query->join('LEFT', '#__bookpro_dest AS dest2 ON dest2.id = flight.desto');
		
		//$query->join('LEFT', '#__bookpro_airline AS airline ON airline.id = flight.airline_id');
		
		if ($dest_from){
			$query->where('flight.desfrom='.$dest_from);
		}
		if ($dest_to){
			$query->where('flight.desto='.$dest_to);
		}
		$query->where('flight.state=1');
		$query->group('flight.airline_id');
		$db->setQuery($query);
		$flights = $db->loadObjectList();
		
		foreach ($flights as $flight){
			$min_price = FlightHelper::getPriceAirlineByRoute($flight->desfrom, $flight->desto, $flight->airline_id);
			$flight->min_price = $min_price;
		}
		
		
		return $flights;
	}
	
	static function getPriceAirlineByRoute($dest_from,$dest_to,$airline_id){
		AImporter::helper('date');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$now = JFactory::getDate('now')->format('d-m-Y');
		$start = DateHelper::dateBeginMonth(strtotime($now));
		
		$end = DateHelper::dateEndMonth(strtotime($now));
		
		$query->select('rate.adult AS price');
		$query->from('#__bookpro_flight AS flight');
		$query->join('LEFT', '#__bookpro_flightrate AS rate ON rate.flight_id = flight.id');
		if ($dest_from){
			$query->where('flight.desfrom='.$dest_from);
			
		}
		if ($dest_to){
			$query->where('flight.desto='.$dest_to);
		}
		if ($airline_id){
			$query->where('flight.airline_id='.$airline_id);
		}
		$sstart = JFactory::getDate($start)->toSql();
		$send = JFactory::getDate($end)->toSql();
		$query->where('rate.date BETWEEN '.$db->quote($sstart).' AND '.$db->quote($send));
		$query->where('flight.state=1');
		$from = "(".(string) $query.") AS f";
		
		$query1 = $db->getQuery(true);
		$query1->select('MIN(f.price)');
		$query1->from($from);
		
		
		
		$db->setQuery($query1);
		$price = $db->loadResult();
		
		return $price;
		
		
	}
	static function getAirportByDest($dest_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('airport.*');
		$query->from('#__bookpro_dest AS airport');
		if ((int) $dest_id){
			$query->where('airport.parent_id='.$dest_id);
		}else{
			return array();
		}
		$query->where('airport.air = 1');
		$query->where('airport.state=1');
		$db->setQuery($query);
		
		$airports = $db->loadObjectList();
		
		
		return $airports;
	}
	static function getRoomSelect($flight_id,$id,$name='jform[base][room_id]'){
		$db = JFactory::getDbo();
        	$query = $db->getQuery(true);
        	$query->select('flight.*');
        	$query->select('CONCAT(`dest1`.`title`,'.$db->quote('-').',`dest2`.`title`,'.$db->quote('&nbsp;').','.$db->quote('(').',airline.code,'.$db->quote('_').',flight.flightnumber,'.$db->quote(')').') AS title');
        	$query->from('#__bookpro_flight AS flight');
        	$query->join("LEFT", '#__bookpro_dest AS `dest1` ON `flight`.`desfrom` = `dest1`.`id`');
        	$query->join("LEFT", '#__bookpro_dest AS `dest2` ON `flight`.`desto` = `dest2`.`id`');
        	$query->join('LEFT', '#__bookpro_airline AS `airline` ON `airline`.`id` = `flight`.`airline_id`');
        	$query->where('flight.state=1');
        	//$query->order('flight.lft ASC');
        	$db->setQuery($query);
        	$lists = $db->loadObjectList();
		$items = array();
		$items[] = JHtmlSelect::option(0,JText::_('COM_BOOKPRO_SELECT_ROOM'),'id','title');
		foreach ($lists as $list){
			//var_dump($list->id);
			$items[] = JHtmlSelect::option($list->id,$list->title,'id','title');
		}
		
		return JHTML::_('select.genericlist', $items, $name, '', 'id', 'title', $flight_id,$id);
		//return JHTML::_('select.genericlist', $lists, 'room_id', '', 'id', 'title', $flight_id);
		//return AHtmlFrontEnd::getFilterSelect($name, 'COM_BOOKPRO_SELECT_ROOM', $lists, $flight_id, '', '', 'id', 'title');
	}
	static function getObjectRate($rate_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('rate.*');
		$query->from('#__bookpro_flightrate AS rate');
		$query->where('rate.id='.$rate_id);
		$db->setQuery($query);
		$rate = $db->loadObject();
		return $rate;
	}
	static function getBaggageSelectBox($name = 'baggage',$id='baggage'){
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('COM_BOOKPRO_SELECT_BAGGAGE'));
		$baggages = array(15,20,30);
		foreach ($baggages as $bagage){
			$options[] = JHtmlSelect::option($bagage,$bagage);
		}
		return JHtmlSelect::genericlist($options, $name,'class="baggage-flight select span12"','value','text',null,$id);
		
	}
	static function getPassengerForm($adult,$children,$infant){
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$passengers = array();
		
		$j = 0;
		if ($adult) {
			
			for($i =0;$i < $adult;$i++){
				$j++;
				$passenger = new stdClass();
				$passenger->title = JText::sprintf('COM_BOOKPRO_PASSENGER_ADULT',$j);
				$passenger->group_id = 1;
				$passenger->route_id = $cart->rate_id;
				$passenger->start = $cart->start;
				if ($cart->return_rate_id) {
					$passenger->return_route_id = $cart->return_rate_id;
				}
				
				$passenger->return_start = $cart->end;
				$passenger->bagName = 'baggage[adult][]';
				$passenger->bagId = 'bag_adult'.$i;
				$passenger->psform_bag_adult = 'psform_bag_adult'.$i;
				$passengers[] = $passenger;
			}
		}
		if ($children) {
			for($i =0;$i < $children;$i++){
				$j++;
				$passenger = new stdClass();
				$passenger->title = JText::sprintf('COM_BOOKPRO_PASSENGER_CHILDREN',$j);
				$passenger->group_id = 2;
				$passenger->route_id = $cart->rate_id;
				$passenger->start = $cart->start;
				if ($cart->return_rate_id) {
					$passenger->return_route_id = $cart->return_rate_id;
				}
				$passenger->return_start = $cart->end;
				$passenger->bagName = 'baggage[children][]';
				$passenger->bagId = 'bag_child'.$i;
				$passenger->psform_bag_adult = 'psform_bag_child'.$i;
				$passengers[] = $passenger;
			}
		}
		if ($infant) {
			for($i =0;$i < $infant;$i++){
				$j++;
				$passenger = new stdClass();
				$passenger->title = JText::sprintf('COM_BOOKPRO_PASSENGER_INFANT',$j);
				$passenger->group_id = 3;
				$passenger->route_id = $cart->rate_id;
				$passenger->start = $cart->start;
				if ($cart->return_rate_id) {
					$passenger->return_route_id = $cart->return_rate_id;
				}
				$passenger->return_start = $cart->end;
				
				$passenger->bagName = 'baggage[infant][]';
				$passenger->bagId = 'bag_infant'.$i;
				$passenger->psform_bag_adult = 'psform_bag_infant'.$i;
				$passengers[] = $passenger;
			}
		}
		return $passengers;
	}
	static function getBookedSeat($date, $rate_id) {
		
		AImporter::helper('paystatus');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(pass.id)');
		$query->from('#__bookpro_passenger AS pass');
		$query->join('INNER', '#__bookpro_orders AS orders ON pass.order_id = orders.id');
		PayStatus::init();
		$query->where('orders.pay_status='.$db->quote(PayStatus::$SUCCESS->getValue()));
		if ($date) {
			$start = JFactory::getDate($date)->toSql();
			$query->where('pass.start='.$db->quote($start));
		}
		$query->where('(pass.route_id='.$rate_id.' OR pass.return_route_id='.$rate_id.')');
		$db->setQuery($query);
		return $db->loadResult();
	}
	static function checkDateBooking($order_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__bookpro_passenger');
		$query->where('order_id='.$order_id);
		$db->setQuery($query);
		$obj = $db->loadObject();
		$start = new JDate($obj->start);
		$now = new JDate('now');
		if ($start < $now) {
			return false;
		}else{
			return true;
		}
	}
	static function getTotalPrice($price,$adult,$children,$infant,$roundtrip=0){
	
		
		
		$total = 0;
		
		if ($adult) {
			if ($roundtrip == 0) {
				$total += $price->adult*$adult;
				
			}else{
				$total += $price->adult_roundtrip*$adult;
			}
			//$total+= $price->adult_taxes+$price->adult_fees;
		}
		
		if ($children) {
			if ($roundtrip == 0) {
				$total += $price->child*$children;
			}else{
				$total += $price->child_roundtrip*$children;
			}
			//$total+= $price->child_taxes+$price->child_fees;
			
		}
		if ($infant) {
			if ($roundtrip == 0) {
				$total += $price->infant*$infant;
			}else{
				$total += $price->infant_roundtrip*$infant;
			}
			//$total+= $price->infant_taxes+$price->infant_fees;
		}
		
		return $total;
	}
	static function getFormPassengerGroup($count,$group_id){
		$obj = new stdClass();
		$obj->count = $count;
		$obj->group_id = $group_id;
		return $obj;
	}
	static function getPriceAdult($price,$adult,$group_id){
	
		$unit_price = FlightHelper::getPriceGroup($price, $group_id);
		$total = $adult*$unit_price;
		return $total;
	}
	static function getPriceGroup($price,$group_id){
		AImporter::model('cgroup');
		$model=new BookProModelCGroup();
		$model->setId($group_id);
		$group=$model->getObject();
		$total=($price*$group->discount)/100;
		return $total;
	}
	static function getFlightDetail($price_id){
	
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('rate.*,flight.flightnumber,flight.start,flight.end,flight.desc,dest1.title  AS from_title,dest2.title AS to_title,dest1.code AS from_code,dest2.code AS to_code,airline.title AS airline_name')
		->from('#__bookpro_flightrate AS rate');
		$query->innerJoin('#__bookpro_flight AS flight ON rate.flight_id=flight.id');
		$query->leftJoin('#__bookpro_airline AS airline ON airline.id=flight.airline_id');
		$query->join("LEFT", '#__bookpro_dest AS dest1 ON flight.desfrom = dest1.id');
		$query->join("LEFT", '#__bookpro_dest AS dest2 ON flight.desto = dest2.id');
		$query->where('rate.id='.$price_id);
		$db->setQuery($query);
		return $db->loadObject();
	
	}
	function getInfoList($order_id){
		AImporter::model('orderinfos','flight','passengers');
		
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
		
		$flighlts=array();
		$fmodel = new BookProModelFlight();
		$flight = $fmodel->getFlightInfo($obj->route_id);
		
		$flight->price = $obj->price;
		$flight->depart_date=$obj->start;
		
		
		
		$passmodel= new BookproModelpassengers();
		
		$state = $passmodel->getState();
		$state->set('filter.order_id', $order_id);
		$state->set('filter.route_id', $flight->id);
		
		
		$passengers = $passmodel->getItems();
		
		$flight->passengers = $passengers;
		$flight->return = false;
		$flights[] = $flight;
		if ($obj->return_route_id) {
			$fmodel = new BookProModelFlight();
			$return_flight = $fmodel->getFlightInfo($obj->return_route_id);
				
			
			
			$return_flight->price = $obj->return_price;
			$return_flight->depart_date=$obj->return_start;
			
			$return_flight->return = true;
			$passmodel= new BookproModelpassengers();
				
			$state = $passmodel->getState();
			$state->set('filter.order_id', $order_id);
			$state->set('filter.return_route_id', $return_flight->id);
			$passengers = $passmodel->getItems();
			$return_flight->passengers = $passengers;
				
			$flights[] = $return_flight;
		}
		//$infomodel=new BookProModelOrderinfos();
		//$infomodel->init(array('order_id'=>$order_id,'ordering'=>'id','order_Dir'=>'ASC'));
		//$infos=$infomodel->getData();
		//$infos = $passmodel->getItems();
		
		
		return $flights;
		
		
	}
	static function getObjectInFo($obj_id){
		AImporter::model('flight');
		$fmodel = new BookProModelFlight();
		$flight = $fmodel->getFlightInfo($obj_id);
		return $flight;
	}
	static function getCountGroup($group_id,$order_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(group_id) AS count');
		$query->from('#__bookpro_passenger AS pass');
		if ($group_id) {
			$query->where('pass.group_id='.$group_id);
		}else{
			return 0;
		}
		if ($order_id) {
			$query->where('pass.order_id='.$order_id);
		}else{
			return 0;
		}
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
		
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
		AImporter::model('flight','order');
		$flightmodel = new BookProModelFlight();
		$ordermodel = new BookProModelOrder();
		if ($return == 0) {
			$flight = $flightmodel->getObjectFullById($pass->route_id);
		}
	
		if ($return == 1) {
			$flight = $flightmodel->getObjectFullById($pass->return_route_id);
		}
		$pass->flight = $flight;
		$ordermodel->setId($pass->order_id);
		$order = $ordermodel->getObject();
		$pass->order = $order;
	
		$ticket_id = $order->order_number."-".$flight->code."-".$pass->id;
		$pass->ticket_id = $ticket_id;
		$pass->location_seat = $return == 0 ? $pass->seat : $pass->return_seat;
		$pass->depart_date = $return == 0 ? $pass->start : $pass->return_start;
		$pass->pprice = $return == 0 ? $pass->price : $pass->return_price;
		$pass->baggage_qty = $return == 0 ? $pass->bag_qty : $pass->return_bag_qty;
		$pass->baggage_price = $return == 0 ? $pass->price_bag : $pass->return_price_bag;
		
		return $pass;
	
	}
	static function getPrice($passengers,$roundtrip = 0){
	
		AImporter::model('cgroup');
		
		for ($i = 0; $i < count($passengers); $i++) {
				
			if ($roundtrip == 0) {
				
				$total += $passengers[$i]['price'];
		
			}else{
				
				$total += $passengers[$i]['return_price'];
			}
		}
		return $total;
	}
	static function getTypesPrice($flight_id,$date,$field = 'adult'){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('#__bookpro_flightrate AS rate');
		if ($flight_id) {
			$query->where('rate.flight_id='.$flight_id);
		}
		
	}
	
	static function getPriceBustripDate($flight_id,$date,$field = 'adult'){
	
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('rate.*');
		$query->from('#__bookpro_flightrate AS rate');
		if ($flight_id) {
			$query->where('rate.flight_id='.$flight_id);
		}
	
		if ($date){
			$busdate = JFactory::getDate($date)->toSql();
			$query->where('rate.date ='.$db->quote($busdate));
		}
	
		if ($flight_id && $date) {
			$db->setQuery($query);
			$price = $db->loadObject();
				
		}else{
			$price = new stdClass();
		}
	
		return $price;
	
	}
	
	static function getRateByFlight($flight_id,$date,$price_type = 'BASE'){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('rate.*');
		
		if ($date) {
			$depart_date=JFactory::getDate($date)->toSql();
				
		}
		
		$query->from('#__bookpro_flightrate AS rate');
		$query->where('rate.pricetype='.$db->quote($price_type));
		if ($flight_id) {
			$query->where('rate.flight_id='.$flight_id);
		}
		if ($date) {
			$query->where('rate.date='.$db->quote($depart_date));
		}
		
		 $db->setQuery($query);
		 $room = $db->loadObject();
		 return $room;
		
	}
	static function getListFlightPrice($flight_id,$arrs){
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('rate.*');
		
		if ($arrs['depart_date']) {
			$depart_date=JFactory::getDate($arrs['depart_date'])->toSql();
		
		}
		
		$query->from('#__bookpro_flightrate AS rate');
		//$query->where('rate.pricetype='.$db->quote($price_type));
		if ($flight_id) {
			$query->where('rate.flight_id='.$flight_id);
		}
		if ($arrs['depart_date']) {
			$query->where('rate.date='.$db->quote($depart_date));
		}
		
		if ($arrs['min_price'] > 0 && $arrs['max_price'] > 0) {
			$query->where('rate.adult BETWEEN '.$arrs['min_price'].' AND '.$arrs['max_price']);
		}
		
		$db->setQuery($query);
		$data = $db->loadObjectList('pricetype');
		$keys=array_keys($data);
		if(count($keys)>0)
		for ($i = 0; $i < count($keys); $i++) {
			$booked=FlightHelper::getBookedSeat($arrs['depart_date'], $data[$keys[$i]]->id);
			$data[$keys[$i]]->booked=$booked;
		}
		return $data;
		/*
		
		$config = JComponentHelper::getParams('com_bookpro');
		$prices = array();
		$base = FlightHelper::getRateByFlight($flight_id, $date);
		$prices['base'] = $base;
		if ($config->get('economy')){
			$economy = FlightHelper::getRateByFlight($flight_id, $date,'ECO');
			$prices['eco'] = $economy;
		}
		if ($config->get('business')) {
			$business = FlightHelper::getRateByFlight($flight_id, $date,'BUS');
			$prices['bus'] = $business;
		}
		return $prices;*/
	}
	static function getObjectFlight($flight_id,$date,$package,$return=false){
		AImporter::model('flight');
		$model = new BookProModelFlight();
		
		$flight=$model->getFlightInfo($flight_id);
		$flight->package = $package;
		$flight->depart_date = $date; 
		$rate = FlightHelper::getRateByFlight($flight_id, $date,$package);
		$flight->rate = $rate;
		
		return $flight;
	}
	static function getFlightTo($from){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('dest.*');
		$query->from('#__bookpro_dest AS dest');
		$query->join('INNER', '#__bookpro_flight AS flight ON dest.id = flight.desto');
		if ($from){
			$query->where('flight.desfrom='.$from);
		}else{
			return null;
		}
		$query->where('flight.state = 1');
		$query->group('dest.id');
		$db->setQuery($query);
		$dests = $db->loadObjectList();
		return $dests;
		
	}
	
	static function getFlightSearch($lists,$roundtrip = 0,$return = false){
		$config = JComponentHelper::getParams('com_bookpro');
		AImporter::model('flights');
		$model = new BookProModelFlights();
		$model->init($lists);
	
		$items = $model->getData();
		
		
		
		$flights = array();
		
		for ($i = 0; $i < count($items); $i++) {
			// count available seats
			$arrfilter = array();
			$arrfilter['depart_date'] = $lists['depart_date'];
			$arrfilter['min_price'] = $lists['min_price'];
			$arrfilter['max_price'] = $lists['max_price'];
			//$rates = FlightHelper::getListFlightPrice($items[$i]->id, $arrfilter);
			$rates = FlightHelper::getListFlightPrice($items[$i]->id, $arrfilter);
			$items[$i]->rates = $rates;
			
				
	
		}
		
		return $items;
		
	
	}
}