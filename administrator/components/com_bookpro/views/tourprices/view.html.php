<?php
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 5/9/2015
 * Time: 2:57 PM
 */
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
AImporter::model('countries', 'paylogs', 'order', 'customer');

class BookProViewTourPrices extends BookproJViewLegacy {

    var $lists;
    var $items;
    var $pagination;
    var $selectable;
    var $params;

    function display($tpl = null) {
        $mainframe = &JFactory::getApplication();

        $this->state = $this->get('State');

        $this->sortDirection = $this->state->get('list.direction');
        $this->sortColumn = $this->state->get('list.ordering');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->addToolbar();

        parent::display($tpl);
    }


    protected function addToolbar() {
        JToolbarHelper::title(JText::_('COM_BOOKRPO_MESSAGE_MANAGER'), 'weblinks.png');
        JToolbarHelper::addNew('tourprice.add');
        JToolbarHelper::editList('tourprice.edit');
        JToolbarHelper::divider();
        JToolbarHelper::publish('tourprices.publish', 'Publish', true);
        JToolbarHelper::unpublish('tourprices.unpublish', 'UnPublish', true);
        JToolbarHelper::divider();
        JToolbarHelper::deleteList('', 'tourprices.delete');
    }

}

?>