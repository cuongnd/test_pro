<?php

defined ( '_JEXEC' ) or die ();
class TourViewCountries extends JViewLegacy {

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
        JToolBarHelper::title(JText::_('Countries'), '');
        JToolbarHelper::addNew('country.add');
        JToolbarHelper::editList('country.edit');
        JToolbarHelper::deleteList('', 'countries.delete');


    }


}