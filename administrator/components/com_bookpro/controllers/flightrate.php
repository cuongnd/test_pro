<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');
AImporter::model('flightrates', 'flightrate', 'flightratelog');

class BookProControllerFlightRate extends AController
{


	var $_model;

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->_model = $this->getModel('flightrate');
		$this->_controllerName = 'flightrate';
	}

	/**
	 * Display default view - Airport list
	 */
	function display()
	{
		switch ($this->getTask()) {
			case 'publish':
				$this->state($this->getTask());
				break;
			case 'unpublish':
				$this->state($this->getTask());
				break;
			case 'trash':
				$this->state($this->getTask());
				break;
			default:
				JRequest::setVar('view', 'flightrate');
		}

		parent::display();
	}

	/**
	 * Open editing form page
	 */
	function editing()
	{
		parent::editing('flightrate');
	}
	function ratedetail(){
		
		$this->setRedirect('index.php?option=com_bookpro&view=flightratedetail');
	}
	/**
	 * Cancel edit operation. Check in subject and redirect to subjects list.
	 */
	function cancel()
	{
		$mainframe = &JFactory::getApplication();
		$mainframe->enqueueMessage(JText::_('Subject editing canceled'));
		$mainframe->redirect('index.php?option=com_bookpro&view=flights');
	}



	/**
	 * Move item up in ordered list
	 */
	function orderup()
	{
		$this->setOrder(- 1);
	}

	/**
	 * Move item down in ordered list
	 */
	function orderdown()
	{
		$this->setOrder(1);
	}


	/**
	 * Save subject and state on edit page.
	 */
	function apply()
	{
		$this->save(true);
	}


	/**
	 * Save subject.
	 *
	 * @param boolean $apply true state on edit page, false return to browse list
	 */
	function save($apply = false)
	{
		
		JRequest::checkToken() or jexit('Invalid Token');
		$mainframe = &JFactory::getApplication();
		
		$input=JFactory::getApplication()->input;
		
		$post = JRequest::get('post');
		$jform = $input->get('jform',array(),'array');
		$frate = $input->get('frate',array(),'array');
		
		
		$startdate = new JDate($jform['startdate']);
		$enddate   = new JDate($jform['enddate']);
		$starttoend =  $startdate->diff($enddate)->days;
		//delete old record
		$model=new BookProModelFlightRate();
		
		$flight_id = $jform['flight_id'];
		
		$this->deleteRate($startdate, $enddate, $jform['flight_id']);
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->insert('#__bookpro_flightrate');
		
		$query->columns('pricetype,flight_id,date,adult,child,infant,adult_roundtrip,child_roundtrip,infant_roundtrip,discount,adult_taxes,adult_fees,child_taxes,child_fees,infant_taxes,infant_fees');
		$values=array();
			
		for($i=0; $i <= $starttoend; $i++)
		{
		foreach ($frate as $rate){
		$temp=array( $db->quote($rate['pricetype']),$jform['flight_id'],$db->quote($startdate->toSql()),$rate['adult'],$rate['child'],$rate['infant'],
				$rate['adult_roundtrip'],$rate['child_roundtrip'],$rate['infant_roundtrip']);
				if ($rate['discount']) {
					$temp[] = $rate['discount'];
				}else{
					$temp[] = 0;
				}
				$temp[] = $rate['adult_taxes'];
				$temp[] = $rate['adult_fees'];
				$temp[] = $rate['child_taxes'];
				$temp[] = $rate['child_fees'];
				$temp[] = $rate['infant_taxes'];
				$temp[] = $rate['infant_fees'];
				$values[]=implode(',', $temp);
		}
		
									$startdate=$startdate->add(new DateInterval('P1D'));
		}
		$query->values($values);
		$db->setQuery($query);
		if($db->execute()){
		
		
		foreach ($frate as $rate){
		$logModel=new BookProModelFlightRateLog();
		$data=array('startdate'=>$jform['startdate'],'enddate'=>$jform['enddate'],'flight_id'=>$jform['flight_id'],'adult'=>$rate['adult'],'child'=>$rate['child']);
				$logModel->store($data);
		}
		
		
		} else {
				$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}
		//ARequest::redirectList('flightrate');
		$this->setRedirect('index.php?option=com_bookpro&view=flightrate&flight_id='.$flight_id);

	}
	function deleteRate($from,$to,$flight_id){
		try {
			
			$db=JFactory::getDbo();
			$query=$db->getQuery(true);
			$query->delete('#__bookpro_flightrate')->where(array('flight_id='.$flight_id,'date BETWEEN '.$db->quote($from).' AND '.$db->quote($to)));
			$db->setQuery($query);
		
			$db->execute();
			return true;
		}catch (Exception $e){
			
			return false;
		}
	}
	function deleteRoomRate(){
		$id = JRequest::getInt('id',0);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if ($id) {
			$query->delete('#__bookpro_flightrate');
			$query->where('id='.$id);
			$db->setQuery($query);
			$db->query();
		}
		$calendar_attributes = array(
				'min_select_year' => 2014,
				'max_select_year' => 2016
		);
		if (isset($_REQUEST['action']) AND $_REQUEST['action'] == 'pn_get_month_cal') {
			require_once JPATH_COMPONENT_BACK_END.'/classes/calendar.php';
			AImporter::css('calendar');
			$calendar = new PN_Calendar($calendar_attributes);
			echo $calendar->draw(array(), $_REQUEST['year'], $_REQUEST['month']);
			exit;
		}
	}
	


}

?>