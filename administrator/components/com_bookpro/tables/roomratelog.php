<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TableRoomRateLog extends JTable
{
	var $id;
	var $startdate;
	var $enddate;
    var $dayrate;
    var $endrate;
    var $hotel_id;
    var $room_id;
		
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_roomratelog', 'id', $db);
	}
    function init()
    {
        $this->id = 0;
        $this->startdate= '';
        $this->enddate  = '';
        $this->dayrate  = '';
        $this->endrate  = '';
        $this->hotel_id = '';
        $this->room_id  = '';
    }	
}
?>
