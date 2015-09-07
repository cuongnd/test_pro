<?php


defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed models
AImporter::model('roomratelog','hotels', 'rooms', 'room');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request','document','image');
AImporter::js('view-images');


class BookProViewRoomRateLog extends BookproJViewLegacy
{
   	
    function display($tpl = null)
    {          
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $model = new BookProModelRoomratelog();
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
        $this->assignRef('rooms', $this->getRoomSelect($obj->room_id));      
        $this->assignRef('obj', $obj);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }
    function getRoomSelect($select){                  
    	 $model = new BookProModelRooms();
    	 $param=array();
    	 $model->init($param);
    	 $list=$model->getData();
    	 return AHtml::getFilterSelect('room_id', 'Select Room', $list, $select, '', '', 'id', 'room_type');
    }
   

    
}

?>