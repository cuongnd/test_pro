<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('countries', 'application', 'customer');
AImporter::helper('image');
JHtml::_('jquery.framework');

class BookProViewExpediaHotelbook extends JViewLegacy
{
    // Overwriting JView display method
    function display($tpl = null)
    {
        $app =& JFactory::getApplication();
        $this->config = AFactory::getConfig();
        $model_customer = new BookProModelCustomer();
        $this->customer = $model_customer->getObjectByUserId();

        //$this->assign('countries',$this->getCountrySelect($this->customer->country_id));
        $dispatcher = JDispatcher::getInstance();
        $this->event = new stdClass();
        JPluginHelper::importPlugin('bookpro');
        $results = $dispatcher->trigger('onBookproProductAfterTitle', array($this->tour));
        $this->event->afterDisplayTitle = $results[0];

        parent::display($tpl);
    }

    function getCountryCodeSelect($select)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__bookpro_country_code AS cc');
        $query->select('cc.country_id AS id,CONCAT(cc.short_name,"(+",cc.calling_code,")") AS title');
        $db->setQuery($query);
        $list = $db->loadObjectList();

        return AHtmlFrontEnd::getFilterSelect('infobooking[countryCode]', JText::_("COM_BOOKPRO_SELECT_COUNTRY_CODE"), $list, $select, false, 'class="input-medium"', 'id', 'title');

    }
}
