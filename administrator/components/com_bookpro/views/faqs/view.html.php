<?php

defined('_JEXEC') or die;

class BookproViewFaqs extends BookproJViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * (non-PHPdoc)
     * @see JViewLegacy::display()
     */
    public function display($tpl = null) {
        //$model = $this->getModel();
        //$model->init(array('title'=>'111'));
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->addToolbar();
        //BookproHelper::setSubmenu('');
        $this->tour_id = JFactory::getApplication()->getUserStateFromRequest('tour_id', 'tour_id', 0);
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        JToolBarHelper::title(JText::_('COM_BOOKPRO_FAQ_MANAGER'), 'airline');
        JToolbarHelper::addNew('faq.add');
        JToolbarHelper::editList('faq.edit');
        JToolbarHelper::divider();
        JToolbarHelper::publish('faq.publish', 'Publish', true);
        JToolbarHelper::unpublish('faqs.unpublish', 'UnPublish', true);
        JToolbarHelper::divider();
        JToolbarHelper::deleteList('', 'faqs.delete');
    }

}
