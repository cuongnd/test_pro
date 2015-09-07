<?php

/*** No direct access ***/
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Travelbook Table class
 *
 * @package    Travelbook
 * @subpackage administrator
 */
class TableCarCategory extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = 0;

	/**
	 * @var string
	 */
	var $type;
	var $title = '';
	var $alias = '';
	var $description = '';
	var $created=null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
    {
        parent::__construct('#__' . PREFIX . '_car_category', 'id', $db);
    }
	
	
	function init()
	{
		$this->id = 0;
		$this->type='';
		$this->title = '';
		$this->alias = '';
		$this->description='';
		$this->created=null;
	}
	function check()
	{
		// check for valid title
		if(trim($this->title) == '') {
			$this->setError(JText::_( 'TB_CATEGORY_NO_NAME' ));
			return false;
		}
		$date = JFactory::getDate();
    	$this->created=$date->toSql();

		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		return true;
	}
}