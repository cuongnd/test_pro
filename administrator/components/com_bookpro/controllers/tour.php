<?php

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');


class BookProControllerTour extends AController
{


	var $_model;

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->_model = $this->getModel('tour');
		$this->_controllerName = CONTROLLER_TOUR;
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
		}
		JRequest::setVar('view', 'tours');
		parent::display();
	}

	function export(){
		require_once JPATH_COMPONENT_BACK_END.DS.'helpers'.DS.'PHPExel.php';
		
		$cids = ARequest::getCids();
		$objPHPExcel->getProperties()->setCreator("Bookpro")
		->setLastModifiedBy("Bookpro")
		->setTitle($tour->code)
		->setSubject("Tour Document");
		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'Code')
		->setCellValue('B1', 'Title')
		->setCellValue('C1', 'Alias')
		->setCellValue('D1', 'Departure Dates')
		->setCellValue('E1', 'Description')
		->setCellValue('F1', 'Packages')
		->setCellValue('G1', 'Misc Tax')
		->setCellValue('G1', 'Visa');
		
		for ($i = 1; $i <= count($cids); $i++) {
			
			$this->_model->setId($cids[$i-1]);
			$tour=$this->_model->getObject();
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i+1, $tour->code)
			->setCellValue('B'.$i+1, $tour->title)
			->setCellValue('C'.$i+1, $tour->alias)
			->setCellValue('D'.$i+1, $departure)
			->setCellValue('E'.$i+1, $tour->desc)
			->setCellValue('F'.$i+1, $package)
			->setCellValue('G'.$i+1, $tour->tax_tip_fee)
			->setCellValue('G'.$i+1, $tour->visa_fee);
			
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
		echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
		 die;
	}
	/**
	 * Open editing form page
	 */
	function editing()
	{
		parent::editing('tour');
	}

	/**
	 * Cancel edit operation. Check in subject and redirect to subjects list.
	 */
	function cancel()
	{
		parent::cancel('Subject editing canceled');
	}

	/**
	 * Save items ordering
	 */
	function saveorder()
	{
		JRequest::checkToken() or jexit('Invalid Token');

		$cids = ARequest::getCids();
		$order = ARequest::getIntArray('order');
		if (ARequest::controlCids($cids, 'save order')) {
			$mainframe = &JFactory::getApplication();
			if ($this->_model->saveorder($cids, $order)) {
				$mainframe->enqueueMessage(JText::_('Successfully saved order'), 'message');
			} else {
				$mainframe->enqueueMessage(JText::_('Tour save failed'), 'error');
			}
		}
		ARequest::redirectList(CONTROLLER_TOUR);
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
	 * Set item order
	 *
	 * @param int $direct move direction
	 */
	function setOrder($direct)
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$cid = ARequest::getCid();
		$mainframe = &JFactory::getApplication();
		if ($this->_model->move($cid, $direct)) {
			$mainframe->enqueueMessage(JText::_('Successfully moved item'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Item move failed'), 'error');
		}
		ARequest::redirectList(CONTROLLER_TOUR);
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
		$post = JRequest::get('post');
		$post['id'] = ARequest::getCid();
		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['itinerary'] = JRequest::getVar('itinerary', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['condition'] = JRequest::getVar('condition', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['include'] = JRequest::getVar('include', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['exclude'] = JRequest::getVar('exclude', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['country_id'] = JRequest::getVar('country_id',array());
		$post['cat_id']=JRequest::getVar('cat_id',array());
		$post['hotel_id']=JRequest::getVar('hotel_id',array());
		$post['files']=implode(JRequest::getVar('files',array()),';');
		
		$id = $this->_model->store($post);
		//var_dump($id);die();




		if ($id !== false) {
			$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}
		if ($apply) {
			$app=JFactory::getApplication();
			$app->redirect('administrator/index.php?option=com_bookpro&view=tours');
			return;
			//ARequest::redirectEdit(CONTROLLER_TOUR, $id);
		} else {
			ARequest::redirectList(CONTROLLER_TOUR);
		}

	}
	function save2copy(){
		/*
		JRequest::checkToken() or jexit('Invalid Token');
		
		
		$mainframe = &JFactory::getApplication();
		
		$post = JRequest::get('post');
		
		
		$post['id'] = ARequest::getCid();
		
		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['itinerary'] = JRequest::getVar('itinerary', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['condition'] = JRequest::getVar('condition', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['include'] = JRequest::getVar('include', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['exclude'] = JRequest::getVar('exclude', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['country_id'] = JRequest::getVar('country_id',array());
		$post['cat_id']=JRequest::getVar('cat_id',array());
		$post['hotel_id']=JRequest::getVar('hotel_id',array());
		$id = $this->_model->store($post);
		 
		$post['id']='0';
		$post['title']=$post['title'].'(2)';
		$id = $this->_model->store($post);
		
		if ($id !== false) {
			$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
		} else {
			$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
		}
		if ($apply) {
			ARequest::redirectEdit(CONTROLLER_TOUR, $id);
		} else {
			ARequest::redirectList(CONTROLLER_TOUR);
		}
		*/
		 
		
	}


}

?>