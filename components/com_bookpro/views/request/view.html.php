<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 84 2012-08-17 07:16:08Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');
AImporter::model('airports', 'country', 'tours');
AImporter::helper('tour');
class BookProViewRequest extends JViewLegacy {

    function display($tpl = null) {
    	
    	
    	$user = JFactory::getUser();
    	$app = JFactory::getApplication();
    	
    	//if ($user->get('guest') == 0) {
    		//$app->redirect('index.php?option=com_bookpro&view=review');
    	//}
    	
    	
        $this->assignRef('tour', $this->getListTour($this->obj->obj_id));
        $this->assignRef('country', $this->getCountrySelectBox($this->obj->country_id));
        parent::display($tpl);
    }

    function getListTour($select, $field = 'obj_id', $autoSubmit = false) {
        AImporter::model('tours');
        $model = new BookProModelTours();
        $lists = $model->getData();
        return AHtmlFrontEnd::getFilterSelect($field, JText::_('Please select your trip '), $lists, $select, $autoSubmit, 'id="obj_id"', 'id', 'title');
    }
	function getCountrySelectBox($select)
	{
		$fullList = TourHelper::getCountryData();
		//var_dump($fullList);
		return AHtmlFrontEnd::getFilterSelect('country_id', JText::_('COM_BOOKPRO_SELECT_COUNTRY'), $fullList, $select, false, '', 'id', 'country_name');
	}
    
}

?>