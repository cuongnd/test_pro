<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TableFlightRateLog extends JTable
{
	var $id;
	var $startdate;
	var $enddate;
    var $adult;
    var $child;
    var $adult_discount;
    var $child_discount;
    var $flight_id;
		
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_flightratelog', 'id', $db);
	}
    function init()
    {
        $this->id = 0;
        $this->startdate= '';
        $this->enddate  = '';
        $this->flight_id  = '';
    }	
}
?>
