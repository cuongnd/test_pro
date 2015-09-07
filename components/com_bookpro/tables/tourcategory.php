<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class TableTourCategory extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id;

	/**
	 * @var string
	 */
	
	var $cat_id;
	var $tour_id;

	function __construct(& $db)
	{
		parent::__construct('#__' . PREFIX . '_tourcategory', 'id', $db);
	}
	function init()
	{
		$this->tour_id=0;
		$this->cat_id = 0;
		
	}

	
	
}