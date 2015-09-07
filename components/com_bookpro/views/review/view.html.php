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
class BookProViewReview extends JViewLegacy {

	function display($tpl = null) {
		$id=JRequest::getVar('id');
		if($id){
			$reviewModel = new BookProModelReview();
			$reviewModel->setId($id);
			$this->obj=$reviewModel->getObject();
		}
		
		$app = JFactory::getApplication();
		if ($this->obj->state!=0) {
			$app->redirect('index.php?option=com_bookpro&view=reviews&=Itemid'.JRequest::getVar('Itemid'));
		}
			
		$cart = JModelLegacy::getInstance('ReviewCart', 'bookpro');
		$cart->load();
		$input=JFactory::getApplication()->input;
		$mainframe = &JFactory::getApplication();
			
		//$jform = JRequest::get('post');
		if($this->jform){
			$cart->obj_id = $this->jform->obj_id;
			$cart->firstname = $this->jform->firstname;
			$cart->lastname = $this->jform->lastname;
			$cart->email = $this->jform->email;
			$cart->date = $this->jform->date;
			$cart->country_id = $this->jform->country_id;
			$cart->saveToSession();
		}
			
		$this->assignRef('tour', $this->getListTour($this->obj->obj_id?$this->obj->obj_id:$cart->obj_id));
		$this->assignRef('country', $this->getCountrySelectBox($this->obj->country_id?$this->obj->country_id:$cart->country_id));

		$this->assignRef('rank', $this->getRank($this->obj));
		$this->assignRef('cart', $cart);
		parent::display($tpl);
	}

	function getListTour($select, $field = 'obj_id', $autoSubmit = false) {
		AImporter::model('tours');
		$model = new BookProModelTours();
		$lists = $model->getData();
		return AHtmlFrontEnd::getFilterSelect($field, JText::_('Please select your trip '), $lists, $select, $autoSubmit, '', 'id', 'title');
	}
	function getRank($obj)
	{
		$checked1=$checked2=$checked3=$checked4=$checked5='';
		if($obj){
			$checkedname = "checked{$obj->rank}";
			$$checkedname='checked="checked"';

		}
		$urlimage = JURI::root() . 'components/com_bookpro/assets/images/';
		$reutrn = '<label class="radio inline">
		   				<input class="rank" type="radio" name="rank" value="1" '.$checked1.'/> 
		   				<img src="'.$urlimage.'1star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_POOR').' 				
		   			</label>
		   			<label class="radio inline">
		   				<input class="rank" type="radio" name="rank" value="2" '.$checked2.'/>
		   				<img src="'.$urlimage.'2star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_FAIR').' 
		   			</label>
		   			<label class="radio inline">
						<input class="rank" type="radio" name="rank" value="3" '.$checked3.'/>
						<img src="'.$urlimage.'3star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_GOOD').'   			
		   			</label>
		   			<label class="radio inline">
		   				<input class="rank" type="radio" name="rank" value="4" '.$checked4.'/>
		   				<img src="'.$urlimage.'4star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_VERY_GOOD').'
		   			</label>
		   			<label class="radio inline">
		   				<input class="rank" type="radio" name="rank" value="5" '.$checked5.'/>
		   				<img src="'.$urlimage.'5star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_EXCELLENT').'
		   			</label>';
		return $reutrn;
	}
	function getCountrySelectBox($select)
	{
		$fullList = TourHelper::getCountryData();
		//var_dump($fullList);
		return AHtmlFrontEnd::getFilterSelect('country_id', JText::_('COM_BOOKPRO_SELECT_COUNTRY'), $fullList, $select, false, '', 'id', 'country_name');
	}

}

?>