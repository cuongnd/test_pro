<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TableFlightRate extends JTable
{
	var $id;
	var $pricetype;
	
	var $date;
	var $adult;
	var $child;
	var $adult_discount;
	var $child_discount;
	var $flight_id;
		
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_flightrate', 'id', $db);
	}
    function init()
    {
        $this->id = 0;
    }	
}
?>
