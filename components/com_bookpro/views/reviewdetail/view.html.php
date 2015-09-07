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
AImporter::model('review');
AImporter::helper('tour');

class BookProViewReviewdetail extends JViewLegacy {

	function display($tpl = null) {
		$id=JRequest::getVar('id');
		$this->obj=TourHelper::getReviewObject($id);
//var_dump(count($this->obj)); die;	
		$this->assignRef('tour', $this->getTourName($this->obj->obj_id));
		$this->assignRef('rank', $this->getRank($this->obj->rank));
		$this->assignRef('cart', $cart);
		parent::display($tpl);
	}

	function getTourName($id) {
		if($id){
			AImporter::model('tour');
			$model = new BookProModelTour();
			$model->setId($id);
			$Tour=$model->getObject();
			return $Tour->title;
		}else{
			return '';
		}
	}
	function getRank($rank)
	{
		$urlimage = JURI::root() . 'components/com_bookpro/assets/images/';
		$reutrn='';
		if($rank==1)$reutrn = '<label class="radio inline">
		
		   				<img src="'.$urlimage.'1star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_POOR').' 				
		   			</label>';
		if($rank==2)$reutrn = '<label class="radio inline">
		   				
		   				<img src="'.$urlimage.'2star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_FAIR').' 
		   			</label>';
		if($rank==3)$reutrn = '<label class="radio inline">
						
						<img src="'.$urlimage.'3star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_GOOD').'   			
		   			</label>';
		if($rank==4)$reutrn = '<label class="radio inline">
		   				
		   				<img src="'.$urlimage.'4star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_VERY_GOOD').'
		   			</label>';
		if($rank==5)$reutrn = '<label class="radio inline">
		   				
		   				<img src="'.$urlimage.'5star.png" alt="star"><br/>'.JText::_('COM_BOOKPRO_REVIEW_EXCELLENT').'
		   			</label>';
		return $reutrn;
	}

}

?>