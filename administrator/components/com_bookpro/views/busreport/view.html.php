<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 56 2012-07-21 07:53:28Z quannv $
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
//import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'route','request','date');
//import needed models
AImporter::model('customer','orders','agents','airports');
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_sales_list_');
}

class BookProViewBusreport extends BookproJViewLegacy
{
	/**
	 * Array containig browse table reservations items to display.
	 *
	 * @var array
	 */
	var $items;

	/**
	 * Standard Joomla! browse tables pagination object.
	 *
	 * @var JPagination
	 */
	var $pagination;

	/**
	 * Prepare to display page.
	 *
	 * @param string $tpl name of used template
	 */
	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		/* @var $mainframe JApplication */
		$document = &JFactory::getDocument();
		/* @var $document JDocument */
		$document->setTitle('Orders Management');
		$model = new BookProModelAgents();
		$this->lists = array();
		$this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
		$this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
		$this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
		$this->lists['agent_id'] = ARequest::getUserStateFromRequest('agent_id', 0 , 'int');
		$this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');

		$this->lists['from'] = ARequest::getUserStateFromRequest('from', '', 'string');
		$this->lists['to'] = ARequest::getUserStateFromRequest('to', '', 'string');

		$config =& JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');
		$today = JFactory::getDate('now',$tzoffset);
		$today->sub(new DateInterval('P0D'));
		$start= $today->format('Y-m-d',true);

		$today->add(new DateInterval('P9D'));
		$end = $today->format('Y-m-d',true);


		//$this->assignRef('days', $days);


		$this->lists['start'] = ARequest::getUserStateFromRequest('start', JFactory::getDate($start)->format('d-m-Y'), 'string');

		$this->lists['end'] = ARequest::getUserStateFromRequest('end', JFactory::getDate($end)->format('d-m-Y'), 'string');

			
		$from = $this->getDestinationSelectBox($this->lists['from'],'from');
		$to = $this->getDestinationSelectBox($this->lists['to'],'to');

		$this->assignRef('from', $from);
		$this->assignRef('to', $to);

		$this->setLayout(JRequest::getCmd('layout','default'));

		$document=JFactory::getDocument();
		$document->addScript('https://www.google.com/jsapi');

		parent::display($tpl);
	}
	function getAgenSelectBox($select, $field = 'agent_id')
	{
		$model = new BookProModelAgents();
		$lists = array();
		$model->init($lists);
		$fullList = $model->getData();
		return AHtml::getFilterSelect($field, 'Agent', $fullList, $select, true, '', 'id', 'company');
	}
	function getDestinationSelectBox($select, $field = 'from')
	{
		$model = new BookProModelAirports();

		$lists = array('limit' => null , 'limitstart' => null , 'state' => null ,'province'=>'1', 'access' => null , 'order' => 'ordering' , 'order_Dir' => 'ASC' , 'search' => null , 'parent' => null , 'template' => null);

		$model->init($lists);

		$fullList = $model->getData();

		return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_DESTINATION'), $fullList, $select, false, '', 'id', 'title');
	}
}