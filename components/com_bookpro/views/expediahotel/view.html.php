<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
AImporter::helper('bookpro', 'currency');
AImporter::model('facilities');

class BookProViewExpediaHotel extends JViewLegacy {

    // Overwriting JView display method
    function display($tpl = null) {
        $app = JFactory::getApplication();
        $input = $app->input;
        $tpl = $input->get('layout') ? $input->get('layout') : null;
        $this->config = AFactory::getConfig();
        $this->_prepareDocument();

        $dispatcher = JDispatcher::getInstance();
        $this->event = new stdClass();
        JPluginHelper::importPlugin('bookpro');

        parent::display($tpl);
    }

    protected function _prepareDocument() {
        
    }

    function getFacilitybox($hotel_id) {
        $items = HotelHelper::getFacilitiesBox($hotel_id);
        foreach ($items as $item) {
            $factitle = $item->title . '(' . CurrencyHelper::formatprice($item->price) . ')';
            $item->factitle = $factitle;
        }

        return AHtmlFrontEnd::checkBoxList($items, 'facility_id[]', '', $select, 'id', 'factitle');
    }

}
