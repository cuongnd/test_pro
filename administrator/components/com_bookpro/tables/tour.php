<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class TableTour extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id;
	var $capacity;
	var $code;
	var $title;
	var $alias;
	var $cat_id;
	var $image;
	var $images;
	var $hotel_id;
    var $mapimage;
    var $intro;
	var $short_desc;
    
    /**
     * Tour grade value: 1,2,3,4,5
     * @var int
     */
    var $grade;
    
    var $min_age;
    
    var $max_age;
    var $activity;
    
	var $description;
	var $state;
	var $start_time;
	var $start;
	var $stype;
	var $ordering;
	var $duration;
	var $condition;
	var $include;
	var $exclude;
	var $created;
	var $metadesc;
	var $metakey;
	var $departure_id;
	var $insurance_id;
	var $publish_date;
	var $unpublish_date;
	var $visa_fee;
	var $files;
	
	var $pax_group;
	var $daytrip;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__' . PREFIX . '_tour', 'id', $db);
	}
	function init()
	{
		$this->cat_id=0;
		$this->code='';
		$this->title = '';
		$this->image = '';
		$this->images='';
		$this->description='';
		$this->duration='';
		$this->state=1;
		$this->ordering=0;
		$this->metadesc="";
		$this->start='';
		$this->condition='';
		$this->include='';
		$this->exclude='';
		$this->metakey='';
		$this->created=null;
		$this->departure_id=0;
		$this->daytrip=1;
	}

	function check(){
    	$date = JFactory::getDate();
    	$this->created=$date->toSql();
		if(trim($this->title) == '') {
			$this->setError(JText::_( 'TB_TOUR_NO_NAME' ));
			return false;
		}

		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		
    	return true;
    }
    
    public function publish($pks = null, $state = 1, $userId = 0) {
    	$k = $this->_tbl_key;
    
    	// Sanitize input.
    	JArrayHelper::toInteger ( $pks );
    	$userId = ( int ) $userId;
    	$state = ( int ) $state;
    
    	// If there are no primary keys set check to see if the instance key is set.
    	if (empty ( $pks )) {
    		if ($this->$k) {
    			$pks = array (
    					$this->$k
    			);
    		} 			// Nothing to set publishing state on, return false.
    		else {
    			$this->setError ( JText::_ ( 'JLIB_DATABASE_ERROR_NO_ROWS_SELECTED' ) );
    			return false;
    		}
    	}
    
    	// Build the WHERE clause for the primary keys.
    	$where = $k . '=' . implode ( ' OR ' . $k . '=', $pks );
    
    	// Determine if there is checkin support for the table.
    	if (! property_exists ( $this, 'checked_out' ) || ! property_exists ( $this, 'checked_out_time' )) {
    		$checkin = '';
    	} else {
    		$checkin = ' AND (checked_out = 0 OR checked_out = ' . ( int ) $userId . ')';
    	}
    
    	// Update the publishing state for rows with the given primary keys.
    	$this->_db->setQuery ( 'UPDATE ' . $this->_db->quoteName ( $this->_tbl ) . ' SET ' . $this->_db->quoteName ( 'state' ) . ' = ' . ( int ) $state . ' WHERE (' . $where . ')' . $checkin );
    
    	try {
    		$this->_db->execute ();
    	} catch ( RuntimeException $e ) {
    		$this->setError ( $e->getMessage () );
    		return false;
    	}
    
    	// If checkin is supported and all rows were adjusted, check them in.
    	if ($checkin && (count ( $pks ) == $this->_db->getAffectedRows ())) {
    		// Checkin the rows.
    		foreach ( $pks as $pk ) {
    			$this->checkin ( $pk );
    		}
    	}
    
    	// If the JTable instance value is in the list of primary keys that were set, set the instance.
    	if (in_array ( $this->$k, $pks )) {
    		$this->state = $state;
    	}
    
    	$this->setError ( '' );
    	return true;
    }
	
	
}