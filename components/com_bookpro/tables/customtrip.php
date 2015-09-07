<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TableCustomtrip extends JTable
{
	
	
		
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_customtrip', 'id', $db);
	}

    
	
}
?>
