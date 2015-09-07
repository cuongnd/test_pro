<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TableAmenity extends JTable
{
	var $id;
	var $title;
	var $language;
	var $access;
	var $state;
	var $image;
		
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_amenity', 'id', $db);
	}

	function init(){
		$this->state=1;
	}
	
}
?>
