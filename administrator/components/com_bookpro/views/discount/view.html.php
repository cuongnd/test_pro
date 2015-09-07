<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed models
AImporter::model("applications");
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request');
AHtml::importIcons();





class BookProViewDiscount extends BookproJViewLegacy
{
   	
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $model = new BookProModelDiscount();
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
            $obj->bind($data);
            
        }
        
        if (! $obj->id && ! $error) {
            $obj->init();
        }
        JFilterOutput::objectHTMLSafe($obj);
        $document->setTitle($obj->title);
        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */
        $this->assignRef('app', $this->getAppSelectBox($obj->app_id));
        $this->assignRef('obj', $obj);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }

    function getAppSelectBox($select)
    {
    	$model = new BookProModelApplications();
    	$fullList = $model->getData();
    	return AHtml::getFilterSelect('app_id', 'Select Application', $fullList, $select, false, '', 'code', 'code');
    }
}

?>