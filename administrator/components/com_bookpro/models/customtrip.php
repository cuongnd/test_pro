<?php
defined ( '_JEXEC' ) or die ();
jimport ( 'joomla.application.component.model' );
class BookProModelCustomTrip extends JModelAdmin {
	
	var $_table;
	var $listcountry = array (
			"Vietnam",
			"Cambodia",
			"Laos",
			"Thailand",
			"Myanmar",
			"Yunnan"
	);
	var $program = array (
			"Culture and history",
			"Eco tourism",
			"Beach relaxing",
			"Bicycle / Motorbike",
			"Walking / Trekking",
			"Health retreat / Spa",
			"Golf touring",
			"Kayaking / Rafting",
			"Photography / Videography",
			"Snorkeling / Diving",
			"Cooking class" 
	);
	var $transport = array (
			"Private car",
			"Local bus",
			"Airplane",
			"Motorbike",
			"Train",
			"Cruise",
			"Bicyle" 
	);
	var $meals = array (
			"Breakfast",
			"Lunch",
			"Dinner" 
	);
	var $accommodation = array (
			"Standard class (2*)",
			"First class (3*)",
			"Superior class (4*)",
			"Deluxe class (5*)",
			"Homestay",
			"Boutique hotels" 
	);
	var $traveltype = array (
			"Private trip",
			"Join group",
			"Both options" 
	);
	public function getTable($type = 'Customtrip', $prefix = 'Table', $config = array()) {
		return JTable::getInstance ( $type, $prefix, $config );
	}
	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm ( 'com_bookpro.customtrip', 'customtrip', array (
				'control' => 'jform',
				'load_data' => $loadData 
		) );
		if (empty ( $form ))
			return false;
		return $form;
	}
	function addCountry($country) {
		array_push ( $this->listcountry, $country );
		return true;
	}
	function getListCountries() {
		return $this->listcountry;
	}
	function getProgram() {
		return $this->program;
	}
	function getMeals() {
		return $this->meals;
	}
	function getTransport() {
		return $this->transport;
	}
	function getAccommodation() {
		return $this->accommodation;
	}
	function getTravelType() {
		return $this->traveltype;
	}
	/*
	 * Nationality
	 */
	function getAllCountries() {
		$db = $this->getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'id,country_name' )->from ( '#__bookpro_country' );
		$db->setQuery ( $query );
		
		$countrys = $db->loadObjectList ();
		return $countrys;
	}
	
	function getCountryIDByName($countryName) {
		$db = $this->getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'id' )->from ( '#__bookpro_country' )->where ( "country_name = '" . $countryName . "'" );
		$db->setQuery ( $query );
		$country = $db->loadResult ();
		return $country;
	}
	function getCountryNameById($id){
		$db = $this->getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'country_name' )->from ( '#__bookpro_country' )
		->where ( "id = '" . $id . "'" );
		$db->setQuery ( $query );
		$country = $db->loadResult ();
		return $country;
	}
	function getDestCountries($countryID) {
		$db = $this->getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'd.title' )->from ( '#__bookpro_dest AS d' )->leftJoin ( '#__bookpro_country AS c ON c.id=d.country_id' )->where ( 'c.id=' . $countryID );
		
		$db->setQuery ( $query );
		
		$countrys = $db->loadObjectList ();
		return $countrys;
	}
	
	function loadPassengerByOrderID($order_id){
		$db = $this->getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( '*' )->from ( '#__bookpro_passenger' )
		->where("order_id=".$order_id);
		$db->setQuery ( $query );
		
		$passengers = $db->loadObjectList ();
		return $passengers;
	}
	function loadPassengerByID($id){
		
		$db = $this->getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( '*' )->from ( '#__bookpro_passenger' )
		->where("id=".$id);
		$db->setQuery ( $query );
	
		$passenger = $db->loadObject() ;
		return $passenger;
	}
}