<?php


defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');


//import needed models
AImporter::model('airport',"countries",'states');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request','image');
AHtml::importIcons();

class BookProViewSms extends BookproJViewLegacy
{

   	
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $model = new BookProModelSms();
        $model->setId(ARequest::getCid());
        $airport = &$model->getObject();
        $this->_displayForm($tpl, $airport);
               
	    }

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     * @param TableCustomer $customer
     * @param JUser $user
     */
    function _displayForm($tpl, $airport)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $error = JRequest::getInt('error');
        $data = JRequest::get('post');
        if ($error) {
            $airport->bind($data);
            
        }
        
        if (! $airport->id && ! $error) {
            $airport->init();
        }
        JFilterOutput::objectHTMLSafe($airport);
        $document->setTitle(BookProHelper::formatName($airport));
        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */
        $this->assignRef('obj', $airport);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }

   
}

?>