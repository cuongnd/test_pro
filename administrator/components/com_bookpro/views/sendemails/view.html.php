<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
AImporter::model('countries', 'paylogs', 'order', 'customer');
require_once JPATH_COMPONENT . '/helpers/airport.php';
//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request', 'image');
AHtml::importIcons();
if (!defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_sendemail_list_');
}

class BookProViewSendemails extends BookproJViewLegacy {

	var $lists;
	var $items;
	var $pagination;
	var $selectable;
	var $params;

	function display($tpl = null) {

		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$this->state = $this->get('State');
		$this->sortDirection = $this->state->get('list.direction');
		$this->sortColumn = $this->state->get('list.ordering');

		//echo '<pre>';var_dump(count($this->items));
		$this->addToolbar();
			
		parent::display($tpl);
	}

	protected function addToolbar() {
		JToolbarHelper::title(JText::_('COM_BOOKRPO_MESSAGE_MANAGER'), 'weblinks.png');
		JToolbarHelper::addNew('sendemail.add');
		JToolbarHelper::editList('sendemail.edit');
		JToolbarHelper::divider();
		JToolbarHelper::publish('sendemails.publish', 'Publish', true);
		JToolbarHelper::unpublish('sendemails.unpublish', 'UnPublish', true);
		JToolbarHelper::divider();
		JToolbarHelper::deleteList('', 'sendemails.delete');
	}

	protected function getSortFields()
	{
		return array(
                'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
                'a.state' => JText::_('JSTATUS'),
                'a.title' => JText::_('JGLOBAL_TITLE'),
                'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}

?>