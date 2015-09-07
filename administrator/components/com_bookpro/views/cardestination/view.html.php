<?php


defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed models
AImporter::model('cardestination',"countries",'states');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request','image','airport');
AImporter::js('view-images');
AHtml::importIcons();

class BookProViewCarDestination extends BookproJViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */                    
        $model = new BookProModelCarDestination();   //var_dump(ARequest::getCid()); die;
        $model->setId(ARequest::getCid());
        $obj = &$model->getObject();
        $this->_displayForm($tpl, $obj);
               
	    }

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     * @param TableCustomer $customer
     * @param JUser $user
     */
    function _displayForm($tpl, $obj)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $error = JRequest::getInt('error');
        $data = JRequest::get('post');
        if ($error) {
            $airport->bind($data);
            
        }
        
        if (! $obj->id && ! $error) {
            $obj->init();
        }
        JFilterOutput::objectHTMLSafe($obj);
        $document->setTitle(BookProHelper::formatName($obj));
        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */

        $this->assignRef('obj', $obj);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }
}

?>