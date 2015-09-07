<?php


defined('_JEXEC') or die('Restricted access');

class TableOrderInfo extends JTable
{

	var $id;
	var $order_id;
	var $adult;
	var $child;
	var $obj_id;
	var $start;
	var $end;
	var $price;
	var $priority;
	var $purpose;
	var $location;
	var $created;
	var $infant;
    var $package;
    
	 
	/**
	 * Construct object.
	 *
	 * @param JDatabaseMySQL $db database connector
	 */
	function __construct(& $db)
	{
		parent::__construct('#__' . PREFIX . '_orderinfo', 'id', $db);
	}

	/**
	 * Init empty object.
	 */
	function init()
	{
		$this->id = 0;
		$this->order_id = NULL;
		$this->adult = 0;
		$this->child = 0;
		$this->start = NULL;
		$this->end= NULL;
		$this->obj_id = NULL;
		$this->price = 0;
		$this->priority=0;
		$this->purpose='';
		$this->location=0;
		$this->created= NULL;
		$this->infant=0;
		$this->package=NULL;
			 
	}
    function check(){
    	$date = JFactory::getDate();
    	$this->start=JFactory::getDate($this->start)->toSql();
    	$this->end=JFactory::getDate($this->end)->toSql();
    	$this->created=$date->toSql();
    	return true;
     }
}

?>