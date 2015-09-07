<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class TableRoomPrice extends JTable
{   
    var $id;
    var $price;
    var $date;
    var $tourpackage_id;
    var $roomtype_id;      
    
    function __construct(& $db)
    {
        parent::__construct('#__bookpro_roomprice', 'id', $db);
    }
    
    function init(){
                          
    }         
}
?>
