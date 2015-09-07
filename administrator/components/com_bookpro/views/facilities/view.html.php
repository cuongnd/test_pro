<?php


    defined('_JEXEC') or die;
    AImporter::helper('route');

    class BookproViewFacilities extends BookproJViewLegacy
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
            JToolBarHelper::title(JText::_('Facilities '.$type), 'facility');
            JToolbarHelper::addNew('facility.add');
            JToolbarHelper::editList('facility.edit');
            JToolbarHelper::divider();
            JToolbarHelper::publish('facilities.publish', 'Publish', true);
            JToolbarHelper::unpublish('facilities.unpublish', 'UnPublish', true);
            JToolbarHelper::divider();
            JToolbarHelper::deleteList('', 'facilities.delete');
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
