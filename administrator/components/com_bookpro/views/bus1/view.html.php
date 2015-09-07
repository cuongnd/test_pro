<?php


defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed models
AImporter::model('bus',"countries",'states');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request','image','bus');
AImporter::js('view-images');
AHtml::importIcons();

class BookProViewBus extends BookproJViewLegacy
{
    function display($tpl = null)
    {
        $this->form		= $this->get('Form');
        $this->item		= $this->get('Item');
        $this->state	= $this->get('State');
        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Prepare to display page.
     *
     * @param string $tpl name of used template
     * @param TableCustomer $customer
     * @param JUser $user
     */
    protected function addToolbar()
    {
        JRequest::setVar('hidemainmenu', true);
        JToolBarHelper::title(JText::_('Car Edit'), 'bus');
        JToolBarHelper::apply('bus.apply');
        JToolBarHelper::save('bus.save');
        JToolBarHelper::cancel('bus.cancel');


        JHtml::_('behavior.modal','a.jbmodal');
        JHtml::_('behavior.formvalidation');
    }
    function _displayForm($tpl, $airport)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */

        $error = JRequest::getInt('error');
        $data = JRequest::get('post');

        JFilterOutput::objectHTMLSafe($airport);
        $document->setTitle(BookProHelper::formatName($airport));
        /* @var $params JParameter */
        $countrybox=BookProHelper::getCountrySelect($airport->country_id);
        $this->countries=$countrybox;
        //$this->assignRef("parents",$this->getParentBox($airport->parent_id));
        //$this->assignRef('obj', $airport);
        parent::display($tpl);
    }


}

?>