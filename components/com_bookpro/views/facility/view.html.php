<?php
    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    //import needed models
    AImporter::model('room','hotels','hotel','registerhotels','facilities');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request','document','image','hotel');
    AImporter::js('view-images');


    class BookProViewfacility extends JViewLegacy
    {

        function display($tpl = null)
        {   

            $this->form        = $this->get('Form');
            $this->item        = $this->get('Item');
            
            
            $this->state    = $this->get('State');
            $hotels=$this->getHotelSelect($this->item->hotel_id);
            $this->assignRef('hotels',$hotels );
            $this->_displayForm($tpl, $obj);    
        }

         function getHotelSelect($select){

            $modelhotel = new BookProModelRegisterHotels();
            $param=array('userid'=>HotelHelper::getCustomerIdByUserLogin());
            $modelhotel->init($param);
            $hotels = $modelhotel->getData();
            return AHtmlFrontEnd::getFilterSelect('jform[hotel_id]', JText::_('COM_BOOKPRO_SELECT_HOTEL'), $hotels, $select, $autoSubmit, '', 'id', 'title');
        }
        function _displayForm($tpl, $obj)
        {
          

            parent::display($tpl);
        }


      

    }

?>