<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class TableHotel extends JTable
{
	var $id 			= null;
	var $category_id 	= null;
	var $title 			= null;
	var $alias;
	var $email			= null;
	var $website		=null;
	var $address1 		= null;
	var $address2 		= null;
	var $desc 	= null;
	var $cancel_policy 	= null;
	var $longitude;
	var $latitude;
	var $rank 			= null;
	var $phone 			= null;
	var $userid 		= null;
	var $city_id 		= null;
	var $state_id 		= null;
	var $country_id 	= null;
	var $state 			= null;
	var $featured		= null;
	var $ordering		=null;
	var $image;
	var $images;
	var $facility;
	var $hits			=0;
	var $code 			= NULL;
	var $vat_number 	= NULL ;
    var $promo_fee          = NULL;
    var $premium            = NULL;
    var $agent_comission    = NULL;
    var $add_comission      = NULL;
    var $mobile      = NULL;
    var $vat_no      = NULL;
    var $pan_no      = NULL;
    var $excise_no      = NULL;
    var $service_tax_no      = NULL;
    

	function __construct(& $db)
	{
		parent::__construct('#__bookpro_hotel', 'id', $db);
	}
	function init(){
		
        
	}
	function check(){
		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		
		if(!$this->id){
			$this -> code = $this->creat_unique_code_id();
		}
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
	function creat_unique_code_id(){
		$_code='';
		$chars ="0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		while ($i <=5 ){
			$num = rand() % 10;
			$tmp = substr($chars, $num,1);
			$_code = $_code . $tmp;
			$i++;
		}
		return $_code;
		
	}


}
?>
