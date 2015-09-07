<?php


defined('_JEXEC') or die('Restricted access');

class TableFlight extends JTable
{
  
    var $id;
	var $metadesc;
    var $keyword;
    var $ordering;
    var $desfrom;
    var $airline_id;
    var $desto;
    var $start;
    var $end;
    var $duration;
    var $frequency;
    var $eco_price;
    var $eco_seat;
    var $bus_price;
    var $bus_seat;
    var $state;
	var $roundtrip_bus_price;
	var $roundtrip_eco_price;
    var $fdate;
    
   
    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__bookpro_flight', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {
        $this->id = 0;
        $this->metadesc = '';
        $this->id = 0;
        $this->desfrom = '';
        $this->desto = '';
        $this->airline_id='';
        $this->start = NULL;
        $this->end = NULL;
        $this->eco_seat = 0;
        $this->eco_price = 0;
        $this->frequency='';
        $this->bus_price= 0;
        $this->bus_seat = 0;
        $this->state=1;
        $this->keyword="";
		$this->roundtrip_eco_price=0;
		$this->roundtrip_bus_price=0;
        $this->fdate=null;
       
    }
	function check()
	{
		$this->state=1;
	    return true;
	}
	public function publish($pks = null, $state = 1, $userId = 0) {
		$k = $this->_tbl_key;
	
		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state = (int) $state;
	
		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}
	
		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);
	
		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		} else {
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}
	
		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
				'UPDATE ' . $this->_db->quoteName($this->_tbl) .
				' SET ' . $this->_db->quoteName('state') . ' = ' . (int) $state .
				' WHERE (' . $where . ')' .
				$checkin
		);
	
		try {
			$this->_db->execute();
		} catch (RuntimeException $e) {
			$this->setError($e->getMessage());
			return false;
		}
	
		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
			// Checkin the rows.
			foreach ($pks as $pk) {
				$this->checkin($pk);
			}
		}
	
		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}
	
		$this->setError('');
		return true;
	}
	
}

?>