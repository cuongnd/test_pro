<?php


    defined('_JEXEC') or die;
    AImporter::helper('route');

    class bookproViewaddons extends BookproJViewLegacy
    {
        protected $items;
        protected $pagination;
        protected $state;

        /**
        * (non-PHPdoc)
        * @see JViewLegacy::display()
        */
        public function display($tpl = null)
        {

            $this->items		= $this->get('Items');
            $this->pagination	= $this->get('Pagination');
            $this->state        = $this->get('State');
            //$hotels = $this->getHotelSelect( $this->state->get('filter.hote_id'));
            //$this->assignRef('hotels',$hotels );  

            $this->addToolbar();
            parent::display($tpl);
        }

        /**
        * Add the page title and toolbar.
        *
        * @since	1.6
        */
        protected function addToolbar()
        {
            $input=JFactory::getApplication()->input;
            $type=$input->get('type','','string');
            JToolBarHelper::title(JText::_('addons '.$type), 'Addon');
            JToolbarHelper::addNew('addon.add');
            JToolbarHelper::editList('addon.edit');
            JToolbarHelper::divider();
            JToolbarHelper::publish('addons.publish', 'Publish', true);
            JToolbarHelper::unpublish('addons.unpublish', 'UnPublish', true);
            JToolbarHelper::divider();
            JToolbarHelper::deleteList('', 'addons.delete');
        }
        function getHotelSelect($select){

            AImporter::model('hotels');
            $modelhotel = new BookProModelHotels();
            $param=array();
            $modelhotel->init($param);
            $hotels = $modelhotel->getData();
            return AHtml::getFilterSelect('hotel_id', JText::_('COM_BOOKPRO_SELECT_HOTEL'), $hotels, $select, true, '', 'id', 'title');
        }
    }
