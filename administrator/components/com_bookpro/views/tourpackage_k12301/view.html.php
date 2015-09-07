<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 45 2012-07-12 10:42:37Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');


//import needed models
AImporter::model('tours','packagetypes','roomtypes','packagehotels','hotels');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request');
AHtml::importIcons();

if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_site_tour_package_list_');
}
class BookProViewTourPackage extends BookproJViewLegacy
{

	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		/* @var $mainframe JApplication */
		$document = &JFactory::getDocument();
		/* @var $document JDocument */
		$model = new BookProModelTourPackage();
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
			$tour_id=JRequest::getInt('tour_id',null);
			if($tour_id)
				$obj->tour_id=$tour_id;
		}
		JFilterOutput::objectHTMLSafe($obj);
		$document->setTitle($obj->title);
		$params = JComponentHelper::getParams(OPTION);
		$this->assignRef("tours",$this->getTourBox($obj->tour_id));
		$this->assignRef("tourgroups",$this->getTourGroupBox($obj->min_person,$obj->tour_id));
		$this->assignRef("packagetypes",$this->getPackageTypeBox($obj->packagetype_id));

		if($obj->id){
		 $rtmodel=new BookProModelRoomTypes();
		 $roomtypesselect=$rtmodel->getRoomTypesByPakage($obj->id);        
            
		}

		$this->assignRef("roomtypes",$this->getRoomTypeBox($roomtypesselect)); 
        
        if($obj->id){
            $htsmodel       =   new BookProModelPackageHotels();
            $hotelsselect   =   $htsmodel->getHotelsByPakage($obj->id);     
            
           // var_dump($hotelsselect); die;     
        }

        $this->assignRef("hotels",$this->getHotelBox($hotelsselect));   
          

		$this->assignRef('obj', $obj);
		$this->assignRef('params', $params);
		parent::display($tpl);
	}

	function getTourBox($select, $field = 'tour_id', $autoSubmit = false){
		 
		$model = new BookProModelTours();
		$lists = $model->getData();
		return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_TOUR_SELECT'), $lists, $select, $autoSubmit, '', 'id', 'title');
	}

	function getTourGroupBox($select,$tour_id){
		AImporter::model('tour');
		$model = new BookProModelTour();
		$model->setId($tour_id);
		$tour = $model->getObject();
		$group=explode(';',trim($tour->pax_group));
		$option=array();
		for ($i = 0; $i < count($group); $i++) {
			 
			$option[]=JHTML::_('select.option',$group[$i] ,$group[$i]);
			 
		}
		return JHtmlSelect::genericlist($option, 'min_person','','value','text',$select);
	}

	function getPackageTypeBox($select, $field = 'packagetype_id', $autoSubmit = false){
		 
		$model = new BookProModelPackageTypes();
		$lists = $model->getData();
		return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_PACKAGETYPE_SELECT'), $lists, $select, $autoSubmit, 'class="required"', 'id', 'title');
	}       
	function getRoomTypeBox($roomtypesselect){
		$model = new BookProModelRoomTypes();
		$list=$model->getData();
		return AHtml::bootrapCheckBoxList($list,'roomtypes[]','',$roomtypesselect,'id', 'title');         
		 
	}
    
	function getHotelBox($select){
		$model = new BookProModelHotels();       
        $list=$model->getData();
        return AHtml::bootrapCheckBoxList($list,'packagehotels[]','',$select,'id', 'title'); 
	}
	function getHotelSelected($tour_id){
		$model=new BookProModelPackageHotel($tour_id);
		$lists= array('tour_id' => $tour_id);
		$model->init($lists);
		$fullList = $model->getData();
		return $fullList;
	}

}

?>