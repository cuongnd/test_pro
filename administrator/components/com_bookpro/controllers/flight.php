<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: flight.php 66 2012-07-31 23:46:01Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');


class BookProControllerFlight extends JControllerForm
{
	function __construct(){
		parent::__construct();
		$this->registerTask('saveStats');
	}
    
	function addstats(){
		$this->setRedirect('index.php?option=com_bookpro&view=stats&layout=edit');
	}
	function saveStats(){
		$app = JFactory::getApplication();
		$input = $app->input;
		$jform = $input->get('jform',array(),'array');
		$frate = $input->get('frate',array(),'array');
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin ('bookpro');
		
		
		$results = $dispatcher->trigger( "onBookproFlightGet", array( $jform,$frate ));
		
		if (!array_key_exists("error", $results[0])){
			$dispatcher->trigger( "saveFlight", array( $results[0]));
			
		}
		
		
		
	
		$this->setRedirect(JRoute::_('index.php?option=com_bookpro&view=flights', false));
		
	}
	
	function calendar(){
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
	function deleteRateDate(){
		$id = JRequest::getInt('id',0);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if ($id) {
			$query->delete('#__bookpro_roomrate');
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
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$task = $this->getTask();

		if ($task == 'save')
		{
			$this->setRedirect(JRoute::_('index.php?option=com_bookpro&view=flights', false));
		}
	}
	

  }

?>