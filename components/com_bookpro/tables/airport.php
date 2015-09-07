<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airport.php  23-06-2012 23:33:14
 **/
defined('_JEXEC') or die('Restricted access');

class TableAirport extends JTableNested
{
  
    var $id;
	var $parent_id;
	var $path;
	var $lft;
	var $rgt;
    var $title;
    var $alias;
    var $ordering;
    var $country_id;
    var $longitude;
    var $latitude;
    var $metakey;
    var $access;
    var $metadesc;
    var $desc;
    var $image;
    var $images;
    var $value;
    var $code;
    var $state;
    // has airport
    var $air;
    var $bus;
    var $province;
    var $intro;
    
    // has
   
  
    function __construct(& $db)
    {
        parent::__construct('#__bookpro_dest', 'id', $db);
    }
    function check(){
    	
    	if(empty($this->alias)) {
    		$this->alias = $this->title;
    	}
    	$this->alias = JFilterOutput::stringURLSafe($this->alias);
    	
    	return true;
    }
    public function delete($pk = null, $children = false)
    {
    	return parent::delete($pk, $children);
    }

    /**
     * Init empty object.
     */
    function init()
    {
		$this->province=1;
    
    }
    
    public function publish($pks = null, $state = 1, $userId = 0)
    {
    	$k = $this->_tbl_key;
    
    	// Sanitize input.
    	JArrayHelper::toInteger($pks);
    	$userId = (int) $userId;
    	$state  = (int) $state;
    
    	// If there are no primary keys set check to see if the instance key is set.
    	if (empty($pks))
    	{
    		if ($this->$k)
    		{
    			$pks = array($this->$k);
    		}
    		// Nothing to set publishing state on, return false.
    		else {
    			$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
    			return false;
    		}
    	}
    
    	// Build the WHERE clause for the primary keys.
    	$where = $k.'='.implode(' OR '.$k.'=', $pks);
    
    	// Determine if there is checkin support for the table.
    	if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
    	{
    		$checkin = '';
    	}
    	else
    	{
    		$checkin = ' AND (checked_out = 0 OR checked_out = '.(int) $userId.')';
    	}
    
    	// Update the publishing state for rows with the given primary keys.
    	$this->_db->setQuery(
    			'UPDATE '.$this->_db->quoteName($this->_tbl) .
    			' SET '.$this->_db->quoteName('state').' = '.(int) $state .
    			' WHERE ('.$where.')' .
    			$checkin
    	);
    
    	try
    	{
    		$this->_db->execute();
    	}
    	catch (RuntimeException $e)
    	{
    		$this->setError($e->getMessage());
    		return false;
    	}
    
    	// If checkin is supported and all rows were adjusted, check them in.
    	if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
    	{
    		// Checkin the rows.
    		foreach ($pks as $pk)
    		{
    			$this->checkin($pk);
    		}
    	}
    
    	// If the JTable instance value is in the list of primary keys that were set, set the instance.
    	if (in_array($this->$k, $pks))
    	{
    		$this->state = $state;
    	}
    
    	$this->setError('');
    	return true;
    }
}

?>