<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
AImporter::helper ( 'request', 'model' );
class TableMessage extends JTable {
	var $id;
	var $created;
	var $state;
	var $order_number;
	var $priority;
	var $subject;
	var $message;
	var $parent_id;
	var $path;
	var $lft;
	var $rgt;
	var $cid_from;
	var $cid_to;
	var $ordernumber;

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct ( '#__bookpro_messages', 'id', $db );
	}
	function init() {
	}
	function check() {
		$date = JFactory::getDate ();
		$this->created = $date->toSql ();
		
		$user = JFactory::getUser ();
		if (! $this->cid_from)
			$this->cid_from = $user->id; // Admin edit . if not exception ->cid_from ->change to admin
		
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