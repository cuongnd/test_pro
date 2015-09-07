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

class BookProViewDocuments extends BookproJViewLegacy {

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
        JToolbarHelper::addNew('document.add');
        JToolbarHelper::editList('document.edit');
        JToolbarHelper::divider();
        JToolbarHelper::publish('documents.publish', 'Publish', true);
        JToolbarHelper::unpublish('documents.unpublish', 'UnPublish', true);
        JToolbarHelper::divider();
        JToolbarHelper::deleteList('', 'documents.delete');
    }

}

?>