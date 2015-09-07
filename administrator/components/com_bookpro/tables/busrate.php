<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TablebusRate extends JTable
{
	var $id;
	var $date;
	var $rate;
		
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_busrate', 'id', $db);
	}
    function init()
    {
        $this->id = 0;
        $this->rate = '';
    }	
}
?>
