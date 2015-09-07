<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request','hotel');
AImporter::model('airports');
//import custom icons
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
		define('SESSION_PREFIX', 'bookpro_hotel_list_');
	
}


class BookProViewHotels extends BookproJViewLegacy
{
	/**
	 * Array containing browse table filters properties.
	 *
	 * @var array
	 */
	var $lists;

	/**
	 * Array containig browse table subjects items to display.
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
	 * Sign if table is used to popup selecting customers.
	 *
	 * @var boolean
	 */
	var $selectable;

	/**
	 * Standard Joomla! object to working with component parameters.
	 *
	 * @var $params JParameter
	 */
	var $params;

	/**
	 * Prepare to display page.
	 *
	 * @param string $tpl name of used template
	 */
	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		/* @var $mainframe JApplication */

		$document = JFactory::getDocument();
		/* @var $document JDocument */
		$document->setTitle(JText::_('List of hotels'));
		$model = new BookProModelHotels();
		$this->lists = array();
		$this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
		$this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
		$this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
		$this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
		$this->lists['title'] = ARequest::getUserStateFromRequest('title', null, 'string');
        $this->lists['userid'] = ARequest::getUserStateFromRequest('userid', null, 'string');

		$model->init($this->lists);

		$this->pagination = &$model->getPagination();
		$this->items = $model->getData();
		$this->params = JComponentHelper::getParams(OPTION);
		$this->selectable = JRequest::getCmd('task') == 'element';
		$this->assignRef('cities', $this->getCitySelectBox($this->lists['city_id']));
		parent::display($tpl);

		 
	}
	function getCitySelectBox($select, $field = 'city_id', $autoSubmit = true)
	{
		$model = new BookProModelAirports();
        $fullList = $model->getItems();
        return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_DESTINATION'), $fullList, $select, $autoSubmit, '', 'id', 'title');
    }
   
}

?>