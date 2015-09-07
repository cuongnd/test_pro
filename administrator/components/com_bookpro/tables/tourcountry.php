<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class TableTourCountry extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id;

	/**
	 * @var string
	 */
	
	var $country_id;
	var $tour_id;

	function __construct(& $db)
	{
		parent::__construct('#__' . PREFIX . '_tourcountry', 'id', $db);
	}
	function init()
	{
		$this->tour_id=0;
		$this->country_id = 0;
		
	}

	
	
}