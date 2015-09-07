<?php

defined ( '_JEXEC' ) or die ();
class TourViewGalleries extends JViewLegacy {

    protected $items;
    protected $state;
    protected $pagination;
    public function display($tpl=null){
         TourHelper::addSubmenu('tour');

        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->addToolbar();
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('City'), '');
        JToolbarHelper::addNew('gallery.add');
        JToolbarHelper::editList('gallery.edit');
        JToolbarHelper::deleteList('', 'galleries.delete');


    }


}