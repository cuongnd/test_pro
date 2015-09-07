<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TablePackageRatedaytripjoingroupLog extends JTable
{
	var $id;
	var $startdate;
	var $enddate;
        var $dayrate;
        var $endrate;
        var $tour_id;
        var $tourpackage_id;
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_packageratedaytripjoingrouplog', 'id', $db);
	}
    function init()
    {
        $this->id = 0;
        $this->startdate= '';
        $this->enddate  = '';
       // $this->dayrate  = '';
        //$this->endrate  = '';
        $this->tour_id = '';
        $this->tourpackage_id  = '';
    }	
}
?>
