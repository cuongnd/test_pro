<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 * */
defined('_JEXEC') or die;
AImporter::helper('route', 'request');
AImporter::model('messages');

class BookProViewMessages extends BookproJViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null) {
        $this->items = $this->get('Items');
        //echo '<pre>';var_dump($this->items);
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->sortDirection = $this->state->get('list.direction');
        $this->sortColumn = $this->state->get('list.ordering');
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        JToolbarHelper::title(JText::_('COM_BOOKRPO_MESSAGE_MANAGER'), 'weblinks.png');
        JToolbarHelper::addNew('message.add');
        JToolbarHelper::editList('message.edit');
        JToolbarHelper::divider();
        JToolbarHelper::publish('messages.publish', 'Publish', true);
        JToolbarHelper::unpublish('messages.unpublish', 'UnPublish', true);
        JToolbarHelper::divider();
        JToolbarHelper::deleteList('', 'messages.delete');
    }

}

?>