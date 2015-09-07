<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/9/2015
 * Time: 11:57 AM
 */
defined('_JEXEC') or die;
class TourViewTours extends JViewLegacy
{
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


        JToolBarHelper::title(JText::_('List tour'), 'location');
        JToolbarHelper::addNew('tour.add');
        JToolbarHelper::editList('tour.edit');
        JToolbarHelper::divider();
        JToolbarHelper::publish('tours.publish', 'Publish', true);
        JToolbarHelper::unpublish('tours.unpublish', 'UnPublish', true);
        JToolbarHelper::divider();
        JToolbarHelper::deleteList('', 'tours.delete');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter_state',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'),
                'value', 'text', $this->state->get('filter.state'), true)
        );
    }

    protected function getSortFields()
    {
        return array(
            'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'a.state' => JText::_('JSTATUS'),
            'a.title' => JText::_('JGLOBAL_TITLE'),
            'a.id' => JText::_('JGRID_HEADING_ID')
        );
    }
}