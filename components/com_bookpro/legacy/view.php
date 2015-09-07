<?php
jimport('joomla.application.component.view');
class BookproFrontEndJViewLegacy extends JViewLegacy
{
    function display($tpl = null)
    {
        $doc=JFactory::getDocument();
        $lessInput=JPATH_ROOT.'/administrator/components/com_bookpro/assets/less/bookpro.less';
        $cssOutput=JPATH_ROOT.'/administrator/components/com_bookpro/assets/css/bookpro.css';
        BookProHelper::compileLess($lessInput,$cssOutput);

        $doc->addStyleSheet(JUri::root().'/administrator/components/com_bookpro/assets/css/bookpro.css');
        $doc->addScript(JUri::root().'/administrator/components/com_bookpro/assets/js/bookpro.js');
        parent::display($tpl);
    }
}
?>