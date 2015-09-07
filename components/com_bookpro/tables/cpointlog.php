<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

		
jimport('joomla.application.component.model');

class TableCPointLog extends JTable
{
	var $id;
	var $cid;
	var $title;
	
	/**
	 * Point redeem by customer
	 * @var unknown
	 */
	var $point;
	
	var $created;
	
	var $state;
	/**
	 * Created by user
	 * @var int
	 */
	var $createby;
	var $state;
		
	function __construct(& $db) 
	{
	  	parent::__construct('#__bookpro_cpointlog', 'id', $db);
	}

	function init(){
		
	}
	
	
	
}
?>
