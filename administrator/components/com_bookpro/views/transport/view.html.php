<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 81 2012-08-11 01:16:36Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');


//import needed models
AImporter::model('airport');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request');
//import needed assets
AHtml::importIcons();

class BookProViewTransport extends BookproJViewLegacy
{

   	
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $model = new BookProModelTransport();
        $model->setId(ARequest::getCid());
        
        $flight = &$model->getObject();
        
        
        $this->_displayForm($tpl, $flight);
        
               
	    }

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     * @param TableCustomer $customer
     * @param JUser $user
     */
    function _displayForm($tpl, $flight)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $error = JRequest::getInt('error');
        $data = JRequest::get('post');
        if ($error) {
            $flight->bind($data);
        }
        
        if (! $flight->id && ! $error) {
            $flight->init();
        }
        JFilterOutput::objectHTMLSafe($flight);
       
        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */
        $airportFrombox=$this->getDestinationSelectBox($flight->from,'from');
        $airportTobox=$this->getDestinationSelectBox($flight->to,'to');
        
      
        
        $this->assignRef("dfrom",$airportFrombox);
        $this->assignRef("dto",$airportTobox);
        $this->assignRef('obj', $flight);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }

 function getDestinationSelectBox($select, $field = 'from')
    {
        $model = BookProHelper::getAirportModel();
        $lists = array('limit' => null , 'limitstart' => null , 'state' => null , 'access' => null , 'order' => 'ordering' , 'order_Dir' => 'ASC' , 'search' => null , 'parent' => null , 'template' => null);
        $model->init($lists);
        $fullList = $model->getFullList();
        return AHtml::getFilterSelect($field, 'Select Destination', $fullList, $select, false, '', 'value', 'text');
    }
}

?>