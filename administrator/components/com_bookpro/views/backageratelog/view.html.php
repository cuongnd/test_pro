<?php


defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed models
AImporter::model('packageratelog','tours', 'packages', 'package');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request','document','image');
AImporter::js('view-images');


class BookProViewPackageRateLog extends BookproJViewLegacy
{
   	
    function display($tpl = null)
    {          
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $model = new BookProModelpackageratelog();
        $model->setId(ARequest::getCid());
        $obj = &$model->getObject();
        $this->_displayForm($tpl, $obj);         
	}

    
    function _displayForm($tpl, $obj)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $error = JRequest::getInt('error');
        $data = JRequest::get('post');
        if ($error) {
            $obj->bind($data);
            
        }
        
        if (! $obj->id && ! $error) {
            $obj->init();
        }
        JFilterOutput::objectHTMLSafe($obj);
        $document->setTitle($obj->title);
        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */
        $this->assignRef('packages', $this->getpackageSelect($obj->package_id));      
        $this->assignRef('obj', $obj);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }
    function getpackageSelect($select){                  
    	 $model = new BookProModelpackages();
    	 $param=array();
    	 $model->init($param);
    	 $list=$model->getData();
    	 return AHtml::getFilterSelect('package_id', 'Select package', $list, $select, '', '', 'id', 'package_type');
    }
   

    
}

?>