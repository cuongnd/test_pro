<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

        
jimport('joomla.application.component.model');

class TableRoomPriceLog extends JTable
{
        var $id;
        var $startdate;
        var $enddate;
        var $tour_id;
        var $tourpackage_id;
        var $extra_bed;
        var $prenight;
        var $pretransfer;
        var $posttransfer;
        
    function __construct(& $db) 
    {
          parent::__construct('#__bookpro_roompricelog', 'id', $db);
    }
    function init()
    {
        $this->id = 0;
        $this->startdate= '';
        $this->enddate  = '';
        $this->tour_id = '';
        $this->tourpackage_id  = '';
    }    
}
?>
