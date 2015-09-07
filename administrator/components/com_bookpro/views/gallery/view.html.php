<?php

defined('_JEXEC') or die;

class BookproViewGallery extends BookproJViewLegacy {

    protected $form;
    protected $item;
    protected $state;

    /**
     * (non-PHPdoc)
     * @see JViewLegacy::display()
     */
    public function display($tpl = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->addToolbar();
        $this->obj_id = JFactory::getApplication()->getUserStateFromRequest('obj_id', 'obj_id', 0);
        AImporter::model('tour');
        $modeltour = new BookProModelTour();
        $modeltour->setId($this->obj_id);
        $this->tour = $modeltour->getObject();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);
        JToolBarHelper::title(JText::_('Edit Image'), 'gallery');
        JToolBarHelper::apply('gallery.apply');
        JToolBarHelper::save('gallery.save');
        JToolBarHelper::cancel('gallery.cancel');
    }

}