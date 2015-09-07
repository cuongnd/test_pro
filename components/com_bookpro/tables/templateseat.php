<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TableTemplateSeat extends JTable
{
	var $id;
	var $row;
	var $col='';
	// array of seat attribute
	var $attr;
	
	
		
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_templateseat', 'id', $db);
	}

	function init(){
	
	}
	
}
?>
