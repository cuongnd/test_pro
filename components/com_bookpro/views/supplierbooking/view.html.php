<?php

    // No direct access to this file
    defined('_JEXEC') or die('Restricted access');
    // import Joomla view library
    jimport('joomla.application.component.view');
    AImporter::helper('bookpro');
    AImporter::model('facilities' );
    class BookProViewSupplierbooking extends JViewLegacy
    {
        // Overwriting JView display method
        function display($tpl = null)
        {

            parent::display($tpl);
        }
        function getHotelSelect(){
            AImporter::model('registerhotels');
             AImporter::helper('hotel');
            $model = new BookProModelRegisterHotels();

            $param=array('userid'=>HotelHelper::getCustomerIdByUserLogin());
            $model->init($param);
            $lists = $model->getData();

            return AHtmlFrontEnd::getFilterSelect('hotel', JText::_('COM_BOOKPRO_SELECT_HOTEL'), $lists, '', '', '', 'id', 'title');

        }


    }
