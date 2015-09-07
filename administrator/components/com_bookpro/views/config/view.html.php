<?php

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
jimport('joomla.html.pane');

//import needed JoomLIB helpers
AImporter::helper('parameter', 'route');
//import needed assets
AImporter::js('view-config');

class BookProViewConfig extends BookproJViewLegacy
{
    /**
     * Component configuration as Joomla params object JParameter.
     * 
     * @var JParameter
     */
    var $params;

    function display($tpl = null)
    {
        JRequest::setVar('hidemainmenu', 1);

        $this->params = &AParameter::loadComponentParams();

        if (is_null($this->params->get('customers_usergroup')))
            $this->params->set('customers_usergroup', CUSTOMER_GID);

        parent::display($tpl);
    }

}
?>