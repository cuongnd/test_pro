<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class TablePackageHotel extends JTable
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
	
	var $hotel_id;
	var $packagetype_id;
    var $itinerary_id;

	function __construct(& $db)
	{
		parent::__construct('#__' . PREFIX . '_packagehotel', 'id', $db);
	}
	function init()
	{
		$this->packagetype_id   = 0;
		$this->hotel_id         = 0;
        $this->itinerary_id    = 0;          
	}

	
	
}