<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 22 2012-07-07 07:56:02Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed models
AImporter::model("hotel",'categories','countries','airports');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request','image','document');
AImporter::js('view-images');
AHtmlFrontEnd::importIcons();


class BookProViewRegisterHotel extends JViewLegacy
{
   	
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $model = new BookProModelHotel();
        $model->setId(ARequest::getCid());
        $obj = &$model->getObject();
        $this->_displayForm($tpl, $obj);
               
	    }

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
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
        $this->assignRef('categories',$this->getCategorySelect($obj->category_id));
        //$this->assignRef('countries',$this->getCountrySelect($obj->country_id));
        $this->assignRef('cities', $this->getCitySelect($obj->city_id));
        AImporter::helper('facility');
        $facilities=FacilityHelper::getFacilitiesSelectedByhotelId($obj->id);
        $this->assignRef('facilities', $facilities);
        $this->assignRef('obj', $obj);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }
 	function getCategorySelect($select){
    	 $model = new BookProModelCategories();
    	 $param=array();
    	 $param['type']='5';
    	 $model->init($param);
    	 $list=$model->getData();
    	 return AHtmlFrontEnd::getFilterSelect('category_id', 'Select Category', $list, $select, $autoSubmit, '', 'id', 'title');
    }
    function getCountrySelect($select){
    	 $model = new BookProModelCountries();
    	 $lists = array('order'=>'id');
    	 $model->init($lists);
    	 $list=$model->getData();
    	 return AHtmlFrontEnd::getFilterSelect('country_id', 'Select Country', $list, $select, $autoSubmit, '', 'id', 'country_name');
    	
    }
	function getCitySelect($select){
    	 $model = new BookProModelAirports();
    	 $lists = array('order'=>'id');
    	 $model->init($lists);
    	 $list=$model->getData();
    	 return AHtmlFrontEnd::getFilterSelect('city_id', 'Select City', $list, $select, $autoSubmit, '', 'id', 'title');
    	
    }
    function getTimeSelect($name,$select){
    		$start = "8:00";
    		$end = "20:30";
    		$option=array();
    		$tStart = strtotime($start);
    		$tEnd = strtotime($end);
    		$tNow = $tStart;
    		while($tNow <= $tEnd){
    			$option[]=JHTML::_('select.option',date("g:i A",$tNow),date("g:i A",$tNow));
    			//JHtmlSelect::option(date("H:i",$tNow),date("H:i",$tNow));
    			$tNow = strtotime('+60 minutes',$tNow);
    		}
    		return JHtml::_('select.genericlist',$option,$name,'class="input-small inline"','value','text',$select);
    	
    }
  

    
}

?>