<?php

defined ( '_JEXEC' ) or die ();
class TourViewTourtariffs extends JViewLegacy {

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
        JToolBarHelper::title(JText::_('Tour tariffs'), '');
        JToolbarHelper::addNew('tourtariff.add');
        JToolbarHelper::editList('tourtariff.edit');

        JToolbarHelper::deleteList('', 'tourtariffs.delete');

//        JHtmlSidebar::addFilter(
//            JText::_('JOPTION_SELECT_PUBLISHED'),
//            'filter_state',
//            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'),
//                'value', 'text', $this->state->get('filter.state'), true)
//        );
    }


}