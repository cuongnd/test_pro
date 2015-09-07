<?php

defined ( '_JEXEC' ) or die ();
class TourViewHotels extends JViewLegacy {

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

        JToolBarHelper::title(JText::_('Tour style'), '');
        JToolbarHelper::addNew('hotel.add');
        JToolbarHelper::editList('hotel.edit');

        JToolbarHelper::deleteList('', 'hotels.delete');

//        JHtmlSidebar::addFilter(
//            JText::_('JOPTION_SELECT_PUBLISHED'),
//            'filter_state',
//            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'),
//                'value', 'text', $this->state->get('filter.state'), true)
//        );
    }


}