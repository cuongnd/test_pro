<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TablePackageRatedaytripjoingroup extends JTable
{
	var $id;
	//var $date;
	var $adult;
	var $teen;
	var $child1;
	var $child2;
	var $state	= null;
	var $tour_id;
	
	//var $type;
	var $tourpackage_id;
	/**
	 * type co the 0,1
	 * @var unknown
	 */
	var $type;
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_packageratedaytripjoingroup', 'id', $db);
	}
    function init()
    {
        //$this->id = ;
    }	
}
?>
