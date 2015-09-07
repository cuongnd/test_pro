<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class TableItineraryDest extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id;
	var $intinerary_id;
	var $dest_id;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__' . PREFIX . '_itinerarydest', 'id', $db);
	}
	function init()
	{	
		$this->intinerary_id;
		$this->dest_id=0;
		
	}

	function check(){
    	//$date = JFactory::getDate();
    	return true;
    }
   
}