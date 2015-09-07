<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TablebusRateLog extends JTable
{
	var $id;
	var $startdate;
    var $bus_id;
		
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_busratelog', 'id', $db);
	}
    function init()
    {
        $this->id = 0;
        $this->startdate= '';
        $this->bus_id  = '';
    }	
}
?>
