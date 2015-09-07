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
    define('SESSION_PREFIX', 'bookpro_airport_list_');
}

class BookProViewGalleries extends BookproJViewLegacy {

    var $lists;
    var $items;
    var $pagination;
    var $selectable;
    var $params;

    function display($tpl = null) {
        $mainframe = &JFactory::getApplication();

        $this->obj_id = JFactory::getApplication()->getUserStateFromRequest('obj_id', 'obj_id', 0);

        $this->state = $this->get('State');

        $this->state->set('filter.obj_id', $this->obj_id);
        
      	$session = JFactory::getSession();
        $sessionType = $session->get('type');
        $this->state->set('filter.type', $sessionType);
        $this->sortDirection = $this->state->get('list.direction');
        $this->sortColumn = $this->state->get('list.ordering');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        //echo '<pre>';var_dump($this->pagination);
        $this->addToolbar();
      
        AImporter::model('tour');
        $modeltour = new BookProModelTour();
        $modeltour->setId($this->obj_id);
        $this->tour = $modeltour->getObject();



        parent::display($tpl);
    }


    protected function addToolbar() {
        JToolbarHelper::title(JText::_('COM_BOOKRPO_MESSAGE_MANAGER'), 'weblinks.png');
        JToolbarHelper::addNew('gallery.add');
        JToolbarHelper::editList('gallery.edit');
        JToolbarHelper::divider();
        JToolbarHelper::publish('galleries.publish', 'Publish', true);
        JToolbarHelper::unpublish('galleries.unpublish', 'UnPublish', true);
        JToolbarHelper::divider();
        JToolbarHelper::deleteList('', 'galleries.delete');
    }

}

?>