<?php

    defined('_JEXEC') or die;

    class BookproViewFacility extends BookproJViewLegacy
    {
        protected $form;
        protected $item;
        protected $state;

        /**
        * (non-PHPdoc)
        * @see JViewLegacy::display()
        */
        public function display($tpl = null)
        {

            $this->form		= $this->get('Form');
            $this->item		= $this->get('Item');

            $this->state	= $this->get('State');
            $this->assignRef('hotels', $this->getHotelSelect($this->item->hotel_id));

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
            JRequest::setVar('hidemainmenu', true);
            JToolBarHelper::title(JText::_('Edit Facility'), 'facility');
            JToolBarHelper::apply('facility.apply');
            JToolBarHelper::save('facility.save');
            JToolBarHelper::cancel('facility.cancel');
        }
        function getHotelSelect($select){

            AImporter::model('hotels');
            $model = new BookProModelHotels();  
            $param=array();
            //$param['state']='1';
            $model->init($param);
            $list=$model->getData();
            return AHtml::getFilterSelect('jform[hotel_id]', JText::_('COM_BOOKPRO_SELECT_HOTEL'), $list, $select, $autoSubmit, '', 'id', 'title');
        }
}