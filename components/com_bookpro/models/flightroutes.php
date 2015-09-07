<?php
AImporter::helper('request', 'model');
defined ( '_JEXEC' ) or die ();
class BookproModelFlightroutes extends JModelList {
	public function __construct($config = array()) {
		if (empty ( $config ['filter_fields'] )) {
			$config ['filter_fields'] = array (
					'id','a.id',
					'title','a.title',
					'state', 'a.state'
			);
		}
		parent::__construct ( $config );
	}
	protected function populateState($ordering = null, $direction =null)
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $published);
		
		$from = $this->getUserStateFromRequest($this->context . '.filter.from', 'from', '');
		$this->setState('filter.from', $from);
		
		$to = $this->getUserStateFromRequest($this->context . '.filter.to', 'to', '');
		$this->setState('filter.to', $to);
		
		parent::populateState('a.ordering', 'asc');
	}
	protected function getListQuery() {
		$db = $this->getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'a.*');
		$query->select('`dest1`.`code` as `from_code`,`dest2`.`code` as `to_code`, `dest1`.`title` as `destfrom`, `dest2`.`title` AS `destto`');
		$query->from ( $db->quoteName ( '#__bookpro_flightroute' ) . ' AS a' );
		
		$query->join('LEFT', '#__bookpro_dest AS dest1 ON a.from = dest1.id');
		$query->join('LEFT', '#__bookpro_dest AS dest2 ON a.to = dest2.id');
		$from = $this->getState('filter.from');
		$to = $this->getState('filter.to');
		if($from){
			$query->where('dest1.id='.$from);
		}
		if($to){
			$query->where('dest2.id='.$to);
		}
		return $query;
	}
	
	 
	function getflightroute($form,$to) {
		$db = $this->getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'a.*');
		$query->select('`dest1`.`code` as `from_code`,`dest2`.`code` as `to_code`, `dest1`.`title` as `destfrom`, `dest2`.`title` AS `destto`');
		$query->from ( $db->quoteName ( '#__bookpro_flightroute' ) . ' AS a' );
		
		$query->join('LEFT', '#__bookpro_dest AS dest1 ON a.from = dest1.id');
		$query->join('LEFT', '#__bookpro_dest AS dest2 ON a.to = dest2.id');
	 	$query->where('a.from='.$form.' and a.to= '.$to);
	 	$db->setQuery($query);
	 	$result = $db->loadObjectList();	 	
	 	return $result;
	}
 
}